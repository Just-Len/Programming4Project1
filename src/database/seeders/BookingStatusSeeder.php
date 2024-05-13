<?php

namespace Database\Seeders;

use App\Models\BookingStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingStatusSeeder extends Seeder
{
    public function run(): void
    {
        $bookingStatus = BookingStatus::first();
        if(!$bookingStatus){
            BookingStatus::insert([
                [
                    'role_id' => BookingStatus::CREATED,
                    'type'=> 'Created',
                ],
                [
                    'role_id' => BookingStatus::CONFIRMED,
                    'type'=> 'Confirmed',
                ],
                [
                    'role_id' => BookingStatus::CANCELLED,
                    'type'=> 'Cancelled',
                ],
                [
                    'role_id' => BookingStatus::FINISHED,
                    'type'=> 'Finished',
                ]
            ]);
        }
    }
}
