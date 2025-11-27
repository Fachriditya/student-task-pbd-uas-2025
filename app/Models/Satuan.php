<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'satuan';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'idsatuan';

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
        'nama_satuan',
        'status',
    ];

    /**
     * Mendapatkan semua Barang yang memiliki Satuan ini.
     */
    public function barangs()
    {
        return $this->hasMany(Barang::class, 'idsatuan', 'idsatuan');
    }
}