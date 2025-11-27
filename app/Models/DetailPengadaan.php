<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPengadaan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'detail_pengadaan';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'iddetail_pengadaan';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'harga_satuan',
        'jumlah',
        'sub_total',
        'idbarang',
        'idpengadaan',
    ];
}