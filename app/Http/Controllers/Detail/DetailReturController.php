<?php

namespace App\Http\Controllers\Detail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailReturController extends Controller
{
    public function index()
    {
        $details = DB::select("SELECT * FROM view_detail_retur ORDER BY idretur DESC, iddetail_retur ASC");

        return view('detail.retur.index', [
            'title'   => 'Laporan Detail Retur Barang',
            'details' => $details
        ]);
    }
}