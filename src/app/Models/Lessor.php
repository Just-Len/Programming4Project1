<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lessor extends Model
{
    protected $table = 'lessor';
    
    protected $primaryKey = 'lessor_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'firstName',
        'lastName',
        'phoneNumber',
        'emailAddress'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
