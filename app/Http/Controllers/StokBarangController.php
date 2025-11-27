<?php

namespace App\Http\Controllers;
    
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokBarangController extends Controller
{
    public function index()
    {
        $stokBarang = DB::select("
            SELECT 
                b.idbarang,
                b.nama as nama_barang,
                s.nama_satuan,
                b.jenis,
                COALESCE((
                    SELECT stock 
                    FROM kartu_stok ks 
                    WHERE ks.idbarang = b.idbarang 
                    ORDER BY ks.idkartu_stok DESC 
                    LIMIT 1
                ), 0) as stok_akhir
            FROM barang b
            JOIN satuan s ON b.idsatuan = s.idsatuan
            WHERE b.status = 1
            ORDER BY b.nama ASC
        ");

        return view('stok-barang.index', [
            'title'      => 'Laporan Jumlah Stok Barang',
            'stokBarang' => $stokBarang
        ]);
    }
}