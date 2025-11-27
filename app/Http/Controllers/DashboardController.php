<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $data = [
            'nama_user'     => $user->username,
            'total_user'    => DB::table('user')->count(),
            'total_role'    => DB::table('role')->count(),
            'total_vendor'  => DB::table('vendor')->where('status', 'A')->count(),
            'total_barang'  => DB::table('barang')->where('status', 1)->count(),
            'total_satuan'  => DB::table('satuan')->where('status', 1)->count(),
            'total_margin'  => DB::table('margin_penjualan')->where('status', 1)->count(),
            'total_pengadaan'   => DB::table('pengadaan')->count(),
            'total_penerimaan'  => DB::table('penerimaan')->count(),
            'total_penjualan'   => DB::table('penjualan')->count(),
            'total_retur'       => DB::table('retur')->count(),
        ];

        return view('dashboard.index', $data);
    }
}