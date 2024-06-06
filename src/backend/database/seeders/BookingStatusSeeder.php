<?php
namespace Database\Seeders;

use App\Models\BookingStatus;
use Illuminate\Database\Seeder;

class BookingStatusSeeder extends Seeder
{
    public function run(): void
    {
        $bookingStatus = BookingStatus::first();
        if (!$bookingStatus) {
            BookingStatus::insert([
                [
                    'booking_status_id' => BookingStatus::CREATED,
                    'type' => 'Created',
                ],
                [
                    'booking_status_id' => BookingStatus::CONFIRMED,
                    'type' => 'Confirmed',
                ],
                [
                    'booking_status_id' => BookingStatus::CANCELLED,
                    'type' => 'Cancelled',
                ],
                [
                    'booking_status_id' => BookingStatus::FINISHED,
                    'type' => 'Finished',
                ]
            ]);
        }
    }
}
