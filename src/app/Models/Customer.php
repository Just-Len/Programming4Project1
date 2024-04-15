<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';

    protected $primaryKey = 'customer_id';
    
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'fisrtName',
        'lastName',
        'phoneNumber',
        'emailAddress',
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
