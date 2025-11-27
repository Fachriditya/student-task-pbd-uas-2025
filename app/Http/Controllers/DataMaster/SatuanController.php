<?php

namespace App\Http\Controllers\DataMaster;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SatuanController extends Controller
{
    public function index() 
    {
        $satuans = DB::select("SELECT * FROM view_satuan_default");
        
        return view('datamaster.satuan.index', [
            'satuans' => $satuans, 
            'title' => 'Data Satuan Aktif'
        ]);
    }

    public function showAll() 
    {
        $satuans = DB::select("SELECT * FROM view_satuan_all");

        return view('datamaster.satuan.index', [
            'satuans' => $satuans,
            'title' => 'Data Satuan All'
        ]);
    }

    public function create()
    {
        return view('datamaster.satuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:50|unique:satuan,nama_satuan',
            'status'      => 'required|in:1,0',
        ]);

        $nama_satuan = $request->nama_satuan;
        $status      = $request->status;

        DB::insert(
            "INSERT INTO satuan (nama_satuan, status) VALUES (?, ?)", 
            [$nama_satuan, $status]
        );

        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $satuan = DB::selectOne("SELECT * FROM satuan WHERE idsatuan = ?", [$id]);

        if (!$satuan) {
            return redirect()->route('satuan.index')->with('error', 'Satuan tidak ditemukan');
        }

        return view('datamaster.satuan.edit', ['satuan' => $satuan]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:50|unique:satuan,nama_satuan,' . $id . ',idsatuan',
            'status'      => 'required|in:1,0',
        ]);

        $nama_satuan = $request->nama_satuan;
        $status      = $request->status;

        DB::update(
            "UPDATE satuan SET nama_satuan = ?, status = ? WHERE idsatuan = ?", 
            [$nama_satuan, $status, $id]
        );

        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil diperbarui');
    }

    public function destroy($id)
    {
        try {
            DB::delete("DELETE FROM satuan WHERE idsatuan = ?", [$id]);
            return redirect()->route('satuan.index')->with('success', 'Satuan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('satuan.index')->with('error', 'Gagal menghapus satuan. Data ini sedang digunakan oleh Barang.');
        }
    }
}