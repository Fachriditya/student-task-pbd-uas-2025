<?php

namespace App\Http\Controllers\DataMaster;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = DB::select('SELECT * FROM view_user'); 
        return view('datamaster.user.index', ['users' => $users]);
    }

    public function create()
    {
        return view('datamaster.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:user,username',
            'password' => 'required|min:6',
        ]);

        $username = $request->username;
        $password = Hash::make($request->password);
        
        $idrole = 2; 

        $cekRole = DB::selectOne("SELECT idrole FROM role WHERE idrole = ?", [$idrole]);
        
        if (!$cekRole) {
             return redirect()->back()->with('error', 'Role Default tidak ditemukan.');
        }

        DB::insert("INSERT INTO user (username, password, idrole) VALUES (?, ?, ?)", [$username, $password, $idrole]);

        return redirect()->route('admin.user.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = DB::selectOne("SELECT * FROM user WHERE iduser = ?", [$id]);
        $roles = DB::select('SELECT * FROM role');

        if (!$user) {
            return redirect()->route('admin.user.index')->with('error', 'User tidak ditemukan');
        }

        return view('datamaster.user.edit', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|unique:user,username,' . $id . ',iduser',
        ]);

        $username = $request->username;
        
        $roleLama = DB::selectOne("SELECT idrole FROM user WHERE iduser = ?", [$id]);
        $idrole   = $roleLama->idrole;

        if ($request->filled('password')) {
            $password = Hash::make($request->password);
            DB::update("UPDATE user SET username = ?, password = ?, idrole = ? WHERE iduser = ?", [$username, $password, $idrole, $id]);
        } else {
            DB::update("UPDATE user SET username = ?, idrole = ? WHERE iduser = ?", [$username, $idrole, $id]);
        }

        return redirect()->route('admin.user.index')->with('success', 'User berhasil diperbarui');
    }

    public function destroy($id)
    {
        DB::delete("DELETE FROM user WHERE iduser = ?", [$id]);
        return redirect()->route('admin.user.index')->with('success', 'User berhasil dihapus');
    }
}