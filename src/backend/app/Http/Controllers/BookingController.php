<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingStatus;
use Illuminate\Http\Request;
use App\Utils\JsonResponses;

class BookingController
{
    public function index()
    {
        $data = Booking::all();
        return JsonResponses::ok(
            'Todos los registros de las reservas',
            $data
        );
    }

    public function indexBookingStatus()
    {
        return JsonResponses::ok(
            'Todos los registros de los estados de reserva',
            BookingStatus::all()
        );
    }

    public function store(Request $request)
    {
        $data_input = $request->input('data', null);
        if ($data_input) {
            $data = json_decode($data_input, true);
            $rules = [
                'lodging_id' => 'required|exists:lodging',
                'customer_id' => 'required|exists:customer',
                'status_id' => 'required|exists:booking_status,booking_status_id',
                'start_date' => 'required|date_format:Y-m-d|after_or_equal:today',
                'end_date' => 'required|date_format:Y-m-d|after:start_date'
            ];
            $isValid = validator($data, $rules);
            if (!$isValid->fails()) {
                $booking = new Booking();
                $booking->lodging_id = $data['lodging_id'];
                $booking->customer_id = $data['customer_id'];
                $booking->status_id = $data['status_id'];
                $booking->start_date = $data['start_date'];
                $booking->end_date = $data['end_date'];
                $booking->save();
                $response = JsonResponses::created(
                    'Reserva creada',
                    'booking',
                    $booking
                );
            } else {
                $response = JsonResponses::notAcceptable(
                    'Datos inválidos',
                    'errors',
                    $isValid->errors()
                );
            }
        } else {
            $response = JsonResponses::badRequest('No se encontró el objeto data');
        }

        return $response;
    }
    
    public function show($id)
    {
        $data = Booking::find($id);
        if (is_object($data)) {
            $data = $data->load('lodging');
            $data = $data->load('customer');
            $response = JsonResponses::ok(
                'Datos de la reserva',
                $data,
            );
        } else {
            $response = JsonResponses::notFound('Recurso no encontrado');
        }
        return $response;
    }

    
    public function destroy($id = null)
    {
        if (isset($id)) {
            $deleted = Booking::where('booking_id', $id)->delete();
            if ($deleted) {
                $response = JsonResponses::ok('Reserva eliminada');
            } else {
                $response = JsonResponses::badRequest(
                    'No se pudo eliminar el recurso, compruebe que exista'
                );
            }
        } else {
            $response = JsonResponses::notAcceptable(
                'Falta el identificador del recurso a eliminar'
            );
        }
        return $response;
    }

    public function update(Request $request)
    {
        $data_input = $request->input('data', null);
        if ($data_input) {
            $data = json_decode($data_input, true);
            $data = array_map('trim', $data);
                $rules = [
                    'booking_id' => 'required|numeric',
                ];
                $isValid = validator($data, $rules);
                if (!$isValid->fails()) {
                    $booking = booking::find($data['booking_id']);
                    if(is_object($booking)){
                        if(isset($data['start_date'])){
                            $booking->start_date = $data['start_date'];
                        }
                        if(isset($data['end_date'])){
                            $booking->end_date = $data['end_date'];
                        }
                        $booking->save();
                        $response = JsonResponses::ok('Reserva actualizada');
                    }else{
                        $response = JsonResponses::notAcceptable(
                            'No se encontro la reserva',
                            'errors',
                            $isValid->errors()
                        );
                    }
                } else {
                    $response = JsonResponses::notAcceptable(
                        'Debe ingresar el ID de una reserva existente y válida',
                        'errors',
                        $isValid->errors()
                    );
                }
        }else{
            $response = JsonResponses::badRequest(
                'Debe ingresar la informacion en el formato correcto'
            );
        }
        return $response;
    }
}