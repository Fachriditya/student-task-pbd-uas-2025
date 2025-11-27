<?php

namespace App\Http\Controllers\DataMaster;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MarginPenjualanController extends Controller
{
    public function index() 
    {
        $margins = DB::select("SELECT * FROM view_margin_penjualan_default");
        
        return view('datamaster.margin-penjualan.index', [
            'margins' => $margins, 
            'title' => 'Data Margin Penjualan Aktif'
        ]);
    }

    public function showAll() 
    {
        $margins = DB::select("SELECT * FROM view_margin_penjualan_all");

        return view('datamaster.margin-penjualan.index', [
            'margins' => $margins,
            'title' => 'Data Margin Penjualan All'
        ]);
    }

    public function create()
    {
        return view('datamaster.margin-penjualan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'persen' => 'required|numeric|min:0',
            'status' => 'required|in:1,0',
        ]);

        $persen = $request->persen / 100; 
        $status = $request->status;
        $iduser = Auth::id();
        $created_at = now();

        DB::insert(
            "INSERT INTO margin_penjualan (persen, status, iduser, created_at) VALUES (?, ?, ?, ?)", 
            [$persen, $status, $iduser, $created_at]
        );

        return redirect()->route('margin.index')->with('success', 'Margin Penjualan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $margin = DB::selectOne("SELECT * FROM margin_penjualan WHERE idmargin_penjualan = ?", [$id]);

        if (!$margin) {
            return redirect()->route('margin.index')->with('error', 'Data Margin tidak ditemukan');
        }

        return view('datamaster.margin-penjualan.edit', ['margin' => $margin]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'persen' => 'required|numeric|min:0',
            'status' => 'required|in:1,0',
        ]);

        $persen = $request->persen / 100;
        $status = $request->status;
        $iduser = Auth::id(); 
        $updated_at = now();

        DB::update(
            "UPDATE margin_penjualan SET persen = ?, status = ?, iduser = ?, updated_at = ? WHERE idmargin_penjualan = ?", 
            [$persen, $status, $iduser, $updated_at, $id]
        );

        return redirect()->route('margin.index')->with('success', 'Margin Penjualan berhasil diperbarui');
    }

    public function destroy($id)
    {
        try {
            DB::delete("DELETE FROM margin_penjualan WHERE idmargin_penjualan = ?", [$id]);
            return redirect()->route('margin.index')->with('success', 'Margin Penjualan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('margin.index')->with('error', 'Gagal menghapus data. Margin ini mungkin sudah digunakan dalam transaksi penjualan.');
        }
    }
}