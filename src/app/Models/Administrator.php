<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administrator extends Model
{
    protected $table = 'administrator';

    protected $primaryKey = 'administrator_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'first_name',
        'lastName',
        'phoneNumber',
        'emailAddress'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
