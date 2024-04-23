<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';

    protected $primaryKey = 'customer_id';
    
    public $timestamps = false;

    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'phone_number',
        'email_address',
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
