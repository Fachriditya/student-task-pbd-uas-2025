<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualans = DB::select("SELECT * FROM view_penjualan ORDER BY created_at DESC");
        
        return view('transaksi.penjualan.index', [
            'title'      => 'Data Penjualan',
            'penjualans' => $penjualans
        ]);
    }

    public function show(string $id)
    {
        $penjualan = DB::selectOne("SELECT * FROM view_penjualan WHERE idpenjualan = ?", [$id]);

        $details = DB::select(
            "SELECT 
                dp.harga_satuan, 
                dp.jumlah, 
                dp.sub_total, 
                b.nama as nama_barang, 
                b.idbarang 
            FROM 
                detail_penjualan dp
            JOIN 
                barang b ON dp.idbarang = b.idbarang
            WHERE 
                dp.penjualan_idpenjualan = ?",
            [$id]
        );
        
        if (!$penjualan) {
            abort(404, 'Data Penjualan tidak ditemukan');
        }

        return view('transaksi.penjualan.show', [
            'title'     => 'Detail Penjualan #' . $penjualan->idpenjualan,
            'penjualan' => $penjualan,
            'details'   => $details
        ]);
    }

    public function create()
    {
        $barangs = DB::select("
            SELECT 
                b.idbarang, 
                b.nama, 
                func_hitung_harga_jual(b.idbarang) as harga_jual,
                COALESCE((
                    SELECT stock 
                    FROM kartu_stok 
                    WHERE idbarang = b.idbarang 
                    ORDER BY idkartu_stok DESC 
                    LIMIT 1
                ), 0) as stok_saat_ini
            FROM barang b 
            WHERE b.status = 1
        ");

        $marginAktif = DB::selectOne("SELECT persen FROM margin_penjualan WHERE status = 1 ORDER BY idmargin_penjualan DESC LIMIT 1");
        $persenMargin = $marginAktif ? ($marginAktif->persen * 100) : 0;

        return view('transaksi.penjualan.create', [
            'title'        => 'Input Penjualan Baru',
            'barangs'      => $barangs,
            'persenMargin' => $persenMargin
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'           => 'required|array|min:1',
            'items.*.idbarang'=> 'required|integer',
            'items.*.jumlah'  => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $id_user = Auth::id();
            $created_at = now();
            
            $marginAktif = DB::selectOne("SELECT idmargin_penjualan FROM margin_penjualan WHERE status = 1 ORDER BY idmargin_penjualan DESC LIMIT 1");
            
            if (!$marginAktif) {
                throw new \Exception("Tidak ada Margin Penjualan yang aktif. Hubungi Superadmin untuk setting margin.");
            }
            $id_margin = $marginAktif->idmargin_penjualan;

            DB::insert(
                "INSERT INTO penjualan (created_at, subtotal_nilai, ppn, total_nilai, iduser, idmargin_penjualan) 
                 VALUES (?, 0, 0, 0, ?, ?)",
                [$created_at, $id_user, $id_margin]
            );

            $id_penjualan = DB::getPdo()->lastInsertId();
            $total_subtotal = 0;

            foreach ($request->input('items') as $item) {
                $id_barang = $item['idbarang'];
                $jumlah_jual = $item['jumlah'];

                $stokData = DB::selectOne("SELECT stock FROM kartu_stok WHERE idbarang = ? ORDER BY idkartu_stok DESC LIMIT 1", [$id_barang]);
                $stokSekarang = $stokData ? $stokData->stock : 0;

                if ($jumlah_jual > $stokSekarang) {
                    throw new \Exception("Stok barang ID " . $id_barang . " tidak cukup. Stok tersedia: " . $stokSekarang);
                }

                $hargaData = DB::selectOne("SELECT func_hitung_harga_jual(?) as harga", [$id_barang]);
                $harga_satuan = $hargaData->harga;

                $sub_total = $harga_satuan * $jumlah_jual;

                DB::insert(
                    "INSERT INTO detail_penjualan (harga_satuan, jumlah, sub_total, penjualan_idpenjualan, idbarang) 
                     VALUES (?, ?, ?, ?, ?)",
                    [$harga_satuan, $jumlah_jual, $sub_total, $id_penjualan, $id_barang]
                );

                $total_subtotal += $sub_total;
            }

            $result_ppn = DB::selectOne("SELECT func_hitung_ppn(?) AS ppn_value", [$total_subtotal]);
            $ppn_dihitung = $result_ppn->ppn_value;

            $total_nilai = $total_subtotal + $ppn_dihitung;

            DB::update(
                "UPDATE penjualan SET subtotal_nilai = ?, ppn = ?, total_nilai = ? WHERE idpenjualan = ?",
                [$total_subtotal, $ppn_dihitung, $total_nilai, $id_penjualan]
            );

            DB::commit();
            return redirect()->route('transaksi.penjualan.index')->with('success', 'Penjualan berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan penjualan: ' . $e->getMessage());
        }
    }
}