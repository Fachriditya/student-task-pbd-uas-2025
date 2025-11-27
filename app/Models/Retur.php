<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retur extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'retur';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'idretur';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'created_at',
        'idpenerimaan',
        'iduser',
    ];

    /**
     * Define the "created at" column name.
     *
     * @var string
     */
    const CREATED_AT = 'created_at';

    /**
     * Define the "updated at" column name.
     *
     * @var null
     */
    const UPDATED_AT = null;
}