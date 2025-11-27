<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KartuStok extends Model
{
    use HasFactory;

    protected $table = 'kartu_stok';
    protected $primaryKey = 'idkartu_stok';
    public $timestamps = false;

    protected $fillable = [
        'jenis_transaksi',
        'masuk',
        'keluar',
        'stock',
        'created_at',
        'idtransaksi',
        'idbarang',
    ];
}