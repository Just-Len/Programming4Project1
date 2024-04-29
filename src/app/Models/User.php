<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'user';

    protected $primaryKey = 'name';
    
    public $timestamps = false;

    protected $fillable = [
        'name',
        'role_id',
        'password',
        'email_address'
    ];

    protected $hidden = [
        'password'
    ];


}
