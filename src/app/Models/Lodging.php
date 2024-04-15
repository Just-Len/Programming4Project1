<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lodging extends Model
{
    protected $table = 'lodging';

    protected $primaryKey = 'lodging_id';

    public $timestamp = false;

    protected $fillable = [
        'lessor_id',
        'name',
        'description',
        'address',
        'perNightPrice',
        'availableRooms'
    ];

    public function lessor(){
        return $this->belongsTo(Lessor::class);
    }
}
