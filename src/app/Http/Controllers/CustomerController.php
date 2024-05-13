<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Utils\JsonResponses;

class CustomerController
{
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
                    'customer'
                );
            } else {
                $response = JsonResponses::notFound('Recurso no encontrado');
            }
        }
        return $response;
    }

    public function showBookings($id)
    {
        $lessor = Customer::find($id);
        if ($lessor) {
            $data = Booking::where('customer_id', $id)->with('payment')->get();
            $response = JsonResponses::ok(
                'Todas las reservas del cliente',
                $data,
                'bookings'
            );
        } else {
            $response = JsonResponses::notFound('Recurso no encontrado');
        }

        return $response;
    }
}
