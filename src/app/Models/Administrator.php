<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administrator extends Model
{
    protected $table = 'administrator';

    protected $primaryKey = 'administrator_id';

    public $timestamps = false;

    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'phone_number',
        'email_address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
