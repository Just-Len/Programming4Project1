<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'lodging_id',
        'customer_id',
        'status_id',
        'startDate',
        'endDate',
    ];
    // Esto son relaciones dentro de la base de datos 
    
    public function lodging()
    {
        return $this->belongsTo(Lodging::class);
    }

   
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
