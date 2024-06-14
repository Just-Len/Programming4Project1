<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Utils\JsonResponses;

class CustomerController
{
    public function index()
    {
        $data = Customer::all();
        return JsonResponses::ok(
            "Todos los registros de los clientes",
            $data,
        );
    }

    public function show($id = null)
    {
        if (!$id) {
            $data = Customer::all();
            $response = JsonResponses::ok(
                "Todos los registros de clientes",
                $data
        );
        }
        else {
            $lessor = Customer::find($id);
            if ($lessor) {
                $data = $lessor->load('user');
                $response = JsonResponses::ok(
                    'Datos del cliente',
                    $data,
                );
            } else {
                $response = JsonResponses::notFound('Recurso no encontrado');
            }
        }
        return $response;
    }

    public function indexBooking($id)
    {
        $data = Booking::with(['lodging', 'customer', 'bookingStatus', 'payment'])
            ->where('customer_id', $id)
            ->get();

        if ($data->isEmpty()) {
            return JsonResponses::ok("No existen reservas");
        }

        $formattedData = $data->map(function($booking) {
            return [
                'booking_id' => $booking->booking_id,
                'lodging' => $booking->lodging ? $booking->lodging->name : null,
                'customer' => $booking->customer ? $booking->customer->user_name :null ,
                'status' => $booking->bookingStatus ? $booking->bookingStatus->type : null,
                'start_date' => $booking->start_date,
                'end_date' => $booking->end_date,
                'payment' => $booking->payment
            ];
        });

        return JsonResponses::ok('Datos de las reservas', $formattedData);
    }
}
