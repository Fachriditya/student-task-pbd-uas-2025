<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'iduser';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'password',
        'idrole',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Remove or comment out the password cast
     * This was causing the authentication loop
     */
    // protected $casts = [
    //     'password' => 'hashed',
    // ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'idrole', 'idrole');
    }

    public function marginPenjualans()
    {
        return $this->hasMany(MarginPenjualan::class, 'iduser', 'iduser');
    }
}