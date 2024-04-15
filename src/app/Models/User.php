<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'user';

    protected $primaryKey = 'user_id';
    
    protected $timestamps = false;

    protected $fillable = [
        'username',
        'role_id',
        'password',
    ];

    protected $hidden = [
        'password'
    ];


}
