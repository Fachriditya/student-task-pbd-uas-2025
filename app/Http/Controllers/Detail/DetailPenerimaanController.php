<?php

namespace App\Http\Controllers\Detail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailPenerimaanController extends Controller
{
    public function index()
    {
        $details = DB::select("SELECT * FROM view_detail_penerimaan ORDER BY idpenerimaan DESC, iddetail_penerimaan ASC");

        return view('detail.penerimaan.index', [
            'title'   => 'Laporan Detail Penerimaan Barang',
            'details' => $details
        ]);
    }
}