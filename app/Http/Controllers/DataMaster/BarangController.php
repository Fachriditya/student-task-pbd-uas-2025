<?php

namespace App\Http\Controllers\DataMaster;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    public function index() 
    {
        $barangs = DB::select("SELECT * FROM view_barang_default");
        
        return view('datamaster.barang.index', [
            'barangs' => $barangs, 
            'title' => 'Data Barang Aktif'
        ]);
    }

    public function showAll() 
    {
        $barangs = DB::select("SELECT * FROM view_barang_all");

        return view('datamaster.barang.index', [
            'barangs' => $barangs,
            'title' => 'Data Barang All'
        ]);
    }

    public function create()
    {
        $satuans = DB::select("SELECT * FROM satuan WHERE status = 1");
        return view('datamaster.barang.create', ['satuans' => $satuans]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis'    => 'required|in:D,C,L,A',
            'nama'     => 'required|string|max:255',
            'idsatuan' => 'required|integer|exists:satuan,idsatuan',
            'harga'    => 'required|integer|min:0',
            'status'   => 'required|in:1,0',
        ]);

        $jenis    = $request->jenis;
        $nama     = $request->nama;
        $idsatuan = $request->idsatuan;
        $harga    = $request->harga;
        $status   = $request->status;

        DB::insert(
            "INSERT INTO barang (jenis, nama, idsatuan, harga, status) VALUES (?, ?, ?, ?, ?)", 
            [$jenis, $nama, $idsatuan, $harga, $status]
        );

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit($id)
    {
        $barang = DB::selectOne("SELECT * FROM barang WHERE idbarang = ?", [$id]);
        $satuans = DB::select("SELECT * FROM satuan WHERE status = 1");

        if (!$barang) {
            return redirect()->route('barang.index')->with('error', 'Barang tidak ditemukan');
        }

        return view('datamaster.barang.edit', [
            'barang' => $barang,
            'satuans' => $satuans
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis'    => 'required|in:D,C,L,A',
            'nama'     => 'required|string|max:255',
            'idsatuan' => 'required|integer|exists:satuan,idsatuan',
            'harga'    => 'required|integer|min:0',
            'status'   => 'required|in:1,0',
        ]);

        $jenis    = $request->jenis;
        $nama     = $request->nama;
        $idsatuan = $request->idsatuan;
        $harga    = $request->harga;
        $status   = $request->status;

        DB::update(
            "UPDATE barang SET jenis = ?, nama = ?, idsatuan = ?, harga = ?, status = ? WHERE idbarang = ?", 
            [$jenis, $nama, $idsatuan, $harga, $status, $id]
        );

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui');
    }

    public function destroy($id)
    {
        try {
            DB::delete("DELETE FROM barang WHERE idbarang = ?", [$id]);
            return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('barang.index')->with('error', 'Gagal menghapus barang. Data ini mungkin sedang digunakan dalam transaksi.');
        }
    }
}