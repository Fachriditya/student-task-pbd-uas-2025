<?php

namespace App\Http\Controllers; // Namespace di root Controllers

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KartuStokController extends Controller
{
    public function index()
    {
        $kartuStok = DB::select("SELECT * FROM view_kartu_stok ORDER BY created_at DESC, idkartu_stok DESC");
        
        return view('kartu-stok.index', [
            'title'     => 'Laporan Kartu Stok',
            'kartuStok' => $kartuStok
        ]);
    }
}