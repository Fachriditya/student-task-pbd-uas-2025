<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarginPenjualan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'margin_penjualan';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'idmargin_penjualan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'persen',
        'status',
        'iduser',
    ];

    /**
     * Define the constants for the timestamp columns.
     *
     * @var string
     */
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * Mendapatkan data User yang membuat margin ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }
}