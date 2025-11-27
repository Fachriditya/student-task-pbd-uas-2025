<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengadaan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pengadaan';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'idpengadaan';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false; // Karena hanya punya 'timestamp', bukan created_at/updated_at

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'timestamp',
        'user_iduser',
        'status',
        'vendor_idvendor',
        'subtotal_nilai',
        'ppn',
        'total_nilai',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'timestamp' => 'datetime',
    ];
}