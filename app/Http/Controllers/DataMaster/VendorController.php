<?php

namespace App\Http\Controllers\DataMaster;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function index() 
    {
        $vendors = DB::select("SELECT * FROM view_vendor_default");
        
        return view('datamaster.vendor.index', [
            'vendors' => $vendors, 
            'title' => 'Data Vendor Aktif'
        ]);
    }

    public function showAll()
    {
        $vendors = DB::select("SELECT * FROM view_vendor_all");
        
        return view('datamaster.vendor.index', [
            'vendors' => $vendors,
            'title' => 'Data Vendor All'
        ]);
    }

    public function create()
    {
        return view('datamaster.vendor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'badan_hukum' => 'required|in:P,C,U,F', 
            'status'      => 'required|in:A,N', 
        ]);

        $nama_vendor = $request->nama_vendor;
        $badan_hukum = $request->badan_hukum;
        $status      = $request->status;

        DB::insert(
            "INSERT INTO vendor (nama_vendor, badan_hukum, status) VALUES (?, ?, ?)", 
            [$nama_vendor, $badan_hukum, $status]
        );

        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil ditambahkan');
    }

    public function edit($id)
    {
        $vendor = DB::selectOne("SELECT * FROM vendor WHERE idvendor = ?", [$id]);

        if (!$vendor) {
            return redirect()->route('vendor.index')->with('error', 'Vendor tidak ditemukan');
        }

        return view('datamaster.vendor.edit', ['vendor' => $vendor]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'badan_hukum' => 'required|in:P,C,U,F',
            'status'      => 'required|in:A,N',
        ]);

        $nama_vendor = $request->nama_vendor;
        $badan_hukum = $request->badan_hukum;
        $status      = $request->status;

        DB::update(
            "UPDATE vendor SET nama_vendor = ?, badan_hukum = ?, status = ? WHERE idvendor = ?", 
            [$nama_vendor, $badan_hukum, $status, $id]
        );

        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil diperbarui');
    }

    public function destroy($id)
    {
        try {
            DB::delete("DELETE FROM vendor WHERE idvendor = ?", [$id]);
            return redirect()->route('vendor.index')->with('success', 'Vendor berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('vendor.index')->with('error', 'Gagal menghapus vendor. Data ini mungkin sedang digunakan dalam transaksi.');
        }
    }
}