<?php

namespace App\Http\Controllers\Detail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailPengadaanController extends Controller
{
    public function index()
    {
        $details = DB::select("SELECT * FROM view_detail_pengadaan ORDER BY idpengadaan DESC, iddetail_pengadaan ASC");

        return view('detail.pengadaan.index', [
            'title'   => 'Laporan Detail Pengadaan Barang',
            'details' => $details
        ]);
    }
}