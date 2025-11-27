<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PenerimaanController extends Controller
{
    public function index()
    {
        $dataPenerimaan = DB::select("SELECT * FROM view_penerimaan");
        
        $data = [
            'title'       => 'Data Penerimaan',
            'penerimaans' => $dataPenerimaan
        ];
        
        return view('transaksi.penerimaan.index', $data);
    }

    public function show(string $id)
    {
        $penerimaan = DB::selectOne("SELECT * FROM view_penerimaan WHERE idpenerimaan = ?", [$id]);

        $details = DB::select(
            "SELECT 
                dp.*, 
                b.nama as nama_barang,
                b.jenis 
            FROM 
                detail_penerimaan dp
            JOIN 
                barang b ON dp.barang_idbarang = b.idbarang
            WHERE 
                dp.idpenerimaan = ?", 
            [$id]
        );
        
        if (!$penerimaan) {
            abort(404, 'Data Penerimaan tidak ditemukan');
        }

        $data = [
            'title'      => 'Detail Penerimaan #' . $penerimaan->idpenerimaan,
            'penerimaan' => $penerimaan,
            'details'    => $details
        ];

        return view('transaksi.penerimaan.show', $data);
    }

    public function create(Request $request)
    {
        $pengadaans = DB::select("
            SELECT p.idpengadaan, p.nama_vendor, p.timestamp 
            FROM view_pengadaan p
            WHERE p.status IN ('I', 'P')
            AND EXISTS (
                SELECT 1
                FROM detail_pengadaan dp
                LEFT JOIN (
                    SELECT dpn.barang_idbarang, pn.idpengadaan, SUM(dpn.jumlah_terima) as total_terima
                    FROM detail_penerimaan dpn
                    JOIN penerimaan pn ON dpn.idpenerimaan = pn.idpenerimaan
                    GROUP BY dpn.barang_idbarang, pn.idpengadaan
                ) terima ON dp.idbarang = terima.barang_idbarang AND dp.idpengadaan = terima.idpengadaan
                WHERE dp.idpengadaan = p.idpengadaan
                AND (dp.jumlah - COALESCE(terima.total_terima, 0)) > 0
            )
            ORDER BY p.idpengadaan DESC
        ");

        $selectedPengadaan = null;
        $detailBarang = [];

        if ($request->has('idpengadaan')) {
            $id = $request->query('idpengadaan');
            $selectedPengadaan = DB::selectOne("SELECT * FROM view_pengadaan WHERE idpengadaan = ?", [$id]);

            if ($selectedPengadaan) {
                $rawDetails = DB::select("
                    SELECT 
                        dp.idbarang,
                        dp.jumlah as qty_pesan, 
                        dp.harga_satuan,
                        b.nama as nama_barang,
                        COALESCE((
                            SELECT SUM(dpn.jumlah_terima)
                            FROM detail_penerimaan dpn
                            JOIN penerimaan p ON dpn.idpenerimaan = p.idpenerimaan
                            WHERE p.idpengadaan = dp.idpengadaan AND dpn.barang_idbarang = dp.idbarang
                        ), 0) as qty_sudah_terima
                    FROM detail_pengadaan dp 
                    JOIN barang b ON dp.idbarang = b.idbarang 
                    WHERE dp.idpengadaan = ?", 
                    [$id]
                );

                foreach ($rawDetails as $item) {
                    $sisa = $item->qty_pesan - $item->qty_sudah_terima;
                    
                    if ($sisa > 0) {
                        $item->sisa = $sisa; 
                        $detailBarang[] = $item;
                    }
                }
            }
        }

        return view('transaksi.penerimaan.create', [
            'title'             => 'Input Penerimaan Barang',
            'pengadaans'        => $pengadaans,
            'selectedPengadaan' => $selectedPengadaan,
            'detailBarang'      => $detailBarang
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'idpengadaan' => 'required|integer|exists:pengadaan,idpengadaan',
            'items'       => 'required|array|min:1',
            'items.*.idbarang' => 'required|integer',
            'items.*.jumlah_terima' => 'required|integer|min:0', 
        ]);

        try {
            DB::beginTransaction();

            $id_pengadaan = $request->idpengadaan;
            $id_user      = Auth::id();
            $created_at   = now();
            
            $validItems = array_filter($request->input('items'), function($i) {
                return $i['jumlah_terima'] > 0;
            });

            if (empty($validItems)) {
                throw new \Exception("Harap masukkan jumlah terima minimal 1 barang.");
            }

            $status = 'S'; 

            DB::insert(
                "INSERT INTO penerimaan (created_at, status, idpengadaan, iduser) VALUES (?, ?, ?, ?)",
                [$created_at, $status, $id_pengadaan, $id_user]
            );

            $id_penerimaan = DB::getPdo()->lastInsertId();

            foreach ($validItems as $item) {
                $detailAsli = DB::selectOne(
                    "SELECT harga_satuan, jumlah FROM detail_pengadaan WHERE idpengadaan = ? AND idbarang = ?", 
                    [$id_pengadaan, $item['idbarang']]
                );
                
                if (!$detailAsli) { continue; }

                $harga_satuan = $detailAsli->harga_satuan;
                $jumlah_pesan = $detailAsli->jumlah;
                
                $sudahDiterima = DB::selectOne("
                    SELECT SUM(jumlah_terima) as total 
                    FROM detail_penerimaan dp
                    JOIN penerimaan p ON dp.idpenerimaan = p.idpenerimaan
                    WHERE p.idpengadaan = ? AND dp.barang_idbarang = ?
                ", [$id_pengadaan, $item['idbarang']])->total ?? 0;

                $jumlah_terima_sekarang = $item['jumlah_terima'];
                $sisa_boleh_diterima = $jumlah_pesan - $sudahDiterima;

                if ($jumlah_terima_sekarang > $sisa_boleh_diterima) {
                    throw new \Exception("Overshipment! Barang ID " . $item['idbarang'] . ". Sisa: " . $sisa_boleh_diterima . ", Input: " . $jumlah_terima_sekarang);
                }

                $result = DB::selectOne(
                    "SELECT func_hitung_subtotal(?, ?) AS subtotal", 
                    [$harga_satuan, $jumlah_terima_sekarang]
                );
                $subtotal_terima = $result->subtotal;

                DB::insert(
                    "INSERT INTO detail_penerimaan (idpenerimaan, barang_idbarang, jumlah_terima, harga_satuan_terima, sub_total_terima) 
                     VALUES (?, ?, ?, ?, ?)",
                    [$id_penerimaan, $item['idbarang'], $jumlah_terima_sekarang, $harga_satuan, $subtotal_terima]
                );
            }

            $totalPesan = DB::selectOne("SELECT SUM(jumlah) as total FROM detail_pengadaan WHERE idpengadaan = ?", [$id_pengadaan])->total;
            
            $totalTerima = DB::selectOne("
                SELECT SUM(dp.jumlah_terima) as total 
                FROM detail_penerimaan dp 
                JOIN penerimaan p ON dp.idpenerimaan = p.idpenerimaan 
                WHERE p.idpengadaan = ?
            ", [$id_pengadaan])->total;

            if ($totalTerima >= $totalPesan) {
                $statusFinal = 'S'; 
            } else {
                $statusFinal = 'P'; 
            }

            DB::update("UPDATE pengadaan SET status = ? WHERE idpengadaan = ?", [$statusFinal, $id_pengadaan]);
            DB::update("UPDATE penerimaan SET status = ? WHERE idpenerimaan = ?", [$statusFinal, $id_penerimaan]);

            DB::commit();
            
            return redirect()->route('transaksi.penerimaan.index')->with('success', 'Penerimaan berhasil disimpan. Status: ' . ($statusFinal == 'S' ? 'Selesai' : 'Parsial/Pending'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }
}