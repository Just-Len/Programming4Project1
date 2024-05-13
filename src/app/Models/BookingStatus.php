<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BookingStatus extends Model
{
    const CREATED   = 1;
    const CONFIRMED = 2;
    const CANCELLED = 3;
    const FINISHED  = 4;

    protected $table = 'booking_status';
    protected $primaryKey = 'booking_status_id';
    public $timestamps = false;

    protected $fillable = [
        'type'
    ];
}
