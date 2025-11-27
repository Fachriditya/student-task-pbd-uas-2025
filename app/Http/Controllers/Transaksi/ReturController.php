<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReturController extends Controller
{
    public function index()
    {
        $dataRetur = DB::select("SELECT * FROM view_retur ORDER BY created_at DESC");
        
        return view('transaksi.retur.index', [
            'title'  => 'Data Retur Pembelian',
            'returs' => $dataRetur
        ]);
    }

    public function show(string $id)
    {
        $retur = DB::selectOne("SELECT * FROM view_retur WHERE idretur = ?", [$id]);

        $details = DB::select(
            "SELECT 
                dr.jumlah, 
                dr.alasan, 
                b.nama as nama_barang,
                dp.barang_idbarang as idbarang
            FROM detail_retur dr
            JOIN detail_penerimaan dp ON dr.iddetail_penerimaan = dp.iddetail_penerimaan
            JOIN barang b ON dp.barang_idbarang = b.idbarang
            WHERE dr.idretur = ?",
            [$id]
        );
        
        if (!$retur) {
            abort(404, 'Data Retur tidak ditemukan');
        }

        return view('transaksi.retur.show', [
            'title'   => 'Detail Retur #' . $retur->idretur,
            'retur'   => $retur,
            'details' => $details
        ]);
    }

    public function create(Request $request)
    {
        $penerimaans = DB::select("
            SELECT 
                p.idpenerimaan, 
                p.idpengadaan, 
                p.created_at, 
                v.nama_vendor,
                p.status 
            FROM penerimaan p
            JOIN pengadaan pg ON p.idpengadaan = pg.idpengadaan
            JOIN vendor v ON pg.vendor_idvendor = v.idvendor
            WHERE p.status IN ('S', 'P')
            ORDER BY p.idpenerimaan DESC
        ");

        $selectedPenerimaan = null;
        $detailBarang = [];

        if ($request->has('idpenerimaan')) {
            $id = $request->query('idpenerimaan');
            
            $selectedPenerimaan = DB::selectOne("
                SELECT p.*, v.nama_vendor 
                FROM penerimaan p
                JOIN pengadaan pg ON p.idpengadaan = pg.idpengadaan
                JOIN vendor v ON pg.vendor_idvendor = v.idvendor
                WHERE p.idpenerimaan = ?
            ", [$id]);

            if ($selectedPenerimaan) {
                $rawDetails = DB::select("
                    SELECT 
                        dp.iddetail_penerimaan,
                        dp.barang_idbarang as idbarang,
                        b.nama as nama_barang,
                        dp.jumlah_terima,
                        COALESCE((
                            SELECT SUM(dr.jumlah) 
                            FROM detail_retur dr 
                            WHERE dr.iddetail_penerimaan = dp.iddetail_penerimaan
                        ), 0) as qty_sudah_retur
                    FROM detail_penerimaan dp 
                    JOIN barang b ON dp.barang_idbarang = b.idbarang 
                    WHERE dp.idpenerimaan = ?", 
                    [$id]
                );

                foreach ($rawDetails as $item) {
                    $sisa = $item->jumlah_terima - $item->qty_sudah_retur;
                    if ($sisa > 0) {
                        $item->sisa_bisa_retur = $sisa;
                        $detailBarang[] = $item;
                    }
                }
            }
        }

        return view('transaksi.retur.create', [
            'title'              => 'Input Retur Pembelian',
            'penerimaans'        => $penerimaans,
            'selectedPenerimaan' => $selectedPenerimaan,
            'detailBarang'       => $detailBarang
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'idpenerimaan' => 'required|integer|exists:penerimaan,idpenerimaan',
            'items'        => 'required|array|min:1',
            'items.*.iddetail_penerimaan' => 'required|integer',
            'items.*.jumlah_retur'        => 'nullable|integer|min:0',
            'items.*.alasan'              => 'nullable|string|max:200',
        ]);

        try {
            DB::beginTransaction();

            $id_penerimaan = $request->idpenerimaan;
            $id_user       = Auth::id();
            $created_at    = now();

            $validItems = array_filter($request->input('items'), function($i) {
                return isset($i['jumlah_retur']) && $i['jumlah_retur'] > 0;
            });

            if (empty($validItems)) {
                throw new \Exception("Harap isi jumlah retur minimal pada 1 barang.");
            }

            DB::insert(
                "INSERT INTO retur (created_at, idpenerimaan, iduser) VALUES (?, ?, ?)",
                [$created_at, $id_penerimaan, $id_user]
            );

            $id_retur = DB::getPdo()->lastInsertId();

            foreach ($validItems as $item) {
                $iddetail_penerimaan = $item['iddetail_penerimaan'];
                $jumlah_retur = $item['jumlah_retur'];
                $alasan = $item['alasan'] ?? '-';

                $dataAsli = DB::selectOne("SELECT jumlah_terima, barang_idbarang FROM detail_penerimaan WHERE iddetail_penerimaan = ?", [$iddetail_penerimaan]);
                
                $historyRetur = DB::selectOne("SELECT SUM(jumlah) as total FROM detail_retur WHERE iddetail_penerimaan = ?", [$iddetail_penerimaan])->total ?? 0;
                
                $sisa_bisa_retur = $dataAsli->jumlah_terima - $historyRetur;

                if ($jumlah_retur > $sisa_bisa_retur) {
                    throw new \Exception("Gagal! Barang ID " . $dataAsli->barang_idbarang . " over-return. Maksimal: " . $sisa_bisa_retur);
                }

                $id_barang = $dataAsli->barang_idbarang;
                $stokTerakhir = DB::selectOne("SELECT stock FROM kartu_stok WHERE idbarang = ? ORDER BY idkartu_stok DESC LIMIT 1", [$id_barang])->stock ?? 0;
                
                if ($jumlah_retur > $stokTerakhir) {
                     throw new \Exception("Gagal! Stok di gudang tidak cukup untuk melakukan retur. (Stok Fisik: $stokTerakhir, Mau Retur: $jumlah_retur)");
                }

                DB::insert(
                    "INSERT INTO detail_retur (jumlah, alasan, idretur, iddetail_penerimaan) VALUES (?, ?, ?, ?)",
                    [$jumlah_retur, $alasan, $id_retur, $iddetail_penerimaan]
                );
            }

            DB::commit();
            return redirect()->route('transaksi.retur.index')->with('success', 'Retur berhasil disimpan. Stok gudang otomatis berkurang.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan retur: ' . $e->getMessage());
        }
    }
}