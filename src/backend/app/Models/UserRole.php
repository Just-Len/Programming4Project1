<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    const ADMINISTRATOR = 1;
    const CUSTOMER      = 2;
    const LESSOR        = 3;

    protected $table = 'user_role';
    
    public $timestamps = false;
    
    protected $fillable = [
        'role_id',
        'type'
    ];
    
}
