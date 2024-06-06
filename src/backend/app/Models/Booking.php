<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'booking';

    protected $primaryKey='booking_id';

    public $timestamps=false;

    protected $fillable = [
        'lodging_id',
        'customer_id',
        'status_id',
        'start_date',
        'end_date'
    ];

    public function lodging()
    {
        return $this->belongsTo(Lodging::class, 'lodging_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function bookingStatus()
    {
        return $this->belongsTo(BookingStatus::class, 'status_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'booking_id');
    }
}
