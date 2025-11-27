<?php

namespace App\Http\Controllers\Detail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailPenjualanController extends Controller
{
    public function index()
    {
        $details = DB::select("SELECT * FROM view_detail_penjualan ORDER BY idpenjualan DESC, iddetail_penjualan ASC");

        return view('detail.penjualan.index', [
            'title'   => 'Laporan Detail Penjualan (Per Item)',
            'details' => $details
        ]);
    }
}