<?php

namespace App\Http\Controllers\DataMaster;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        $roles = DB::select("SELECT * FROM role");
        return view('datamaster.role.index', ['roles' => $roles]);
    }

    public function create()
    {
        return view('datamaster.role.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_role' => 'required|unique:role,nama_role',
        ]);

        $nama_role = $request->nama_role;

        DB::insert("INSERT INTO role (nama_role) VALUES (?)", [$nama_role]);

        return redirect()->route('admin.role.index')->with('success', 'Role berhasil ditambahkan');
    }

    public function edit($id)
    {
        $role = DB::selectOne("SELECT * FROM role WHERE idrole = ?", [$id]);

        if (!$role) {
            return redirect()->route('admin.role.index')->with('error', 'Role tidak ditemukan');
        }

        return view('datamaster.role.edit', ['role' => $role]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_role' => 'required|unique:role,nama_role,' . $id . ',idrole',
        ]);

        $nama_role = $request->nama_role;

        DB::update("UPDATE role SET nama_role = ? WHERE idrole = ?", [$nama_role, $id]);

        return redirect()->route('admin.role.index')->with('success', 'Role berhasil diperbarui');
    }

    public function destroy($id)
    {
        try {
            DB::delete("DELETE FROM role WHERE idrole = ?", [$id]);
            return redirect()->route('admin.role.index')->with('success', 'Role berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.role.index')->with('error', 'Gagal menghapus role. Pastikan tidak ada user yang menggunakan role ini.');
        }
    }
}