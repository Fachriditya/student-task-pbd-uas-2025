<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenerimaan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'detail_penerimaan';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'iddetail_penerimaan';

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
        'idpenerimaan',
        'barang_idbarang',
        'jumlah_terima',
        'harga_satuan_terima',
        'sub_total_terima',
    ];
}