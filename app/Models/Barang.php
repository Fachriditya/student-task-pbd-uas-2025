<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'barang';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'idbarang';

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
        'jenis',
        'nama',
        'idsatuan',
        'status',
        'harga',
    ];
    
    /**
     * Mendapatkan data Satuan yang dimiliki oleh Barang.
     */
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'idsatuan', 'idsatuan');
    }
}