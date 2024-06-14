<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingStatus;
use App\Models\Lodging;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Utils\JsonResponses;
use DateTime;

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

    public function storePayment(Request $request)
    {
        $bookingId = $request->route('booking_id');
        $booking = Booking::find($bookingId);

        if (!$booking) {
            return JsonResponses::notFound("No existe un alojamiento con el identificador especificado.");
        }

        $rawData = $request->input('data', null);
        if ($rawData) {
            $data = json_decode($rawData, true);
            $rules = [
                'date' => 'required|date_format:Y-m-d|after_or_equal:today',
            ];
            $validation = validator($data, $rules);
            if (!$validation->fails()) {
                $lodging = Lodging::find($booking->lodging_id);
                $startDate = new DateTime($booking->start_date);
                $endDate = new DateTime($booking->end_date);
                $interval = $endDate->diff($startDate);
                $days = $interval->format("%a");

                $booking->status_id = BookingStatus::CONFIRMED;

                $payment = new Payment();
                $payment->booking_id= $bookingId;
                $payment->total_amount= $lodging->per_night_price * $days;
                $payment->date= $data['date'];
                
                $payment->save();
                $booking->save();
                $response = JsonResponses::created(
                    'Pago realizado con éxito.',
                    $payment
                );
            } else {
                $response = JsonResponses::notAcceptable(
                    'Datos inválidos',
                    'errors',
                    $validation->errors()
                );
            }
        } else {
            $response = JsonResponses::badRequest('No se encontró el objeto data');
        }

        return $response;
    }
    
    public function show($customerId)
    {
        $data = Booking::with(['lodging', 'customer', 'bookingStatus'])
            ->where('customer_id', $customerId)
            ->get();

        if ($data->isEmpty()) {
            return JsonResponses::ok("No existen reservas", []);
        }

        $formattedData = $data->map(function($booking) {
            return [
                'booking_id' => $booking->booking_id,
                'lodging' => $booking->lodging ? $booking->lodging->name : null,
                'customer' => $booking->customer ? $booking->customer->user_name :null ,
                'status' => $booking->bookingStatus ? $booking->bookingStatus->type : null,
                'start_date' => $booking->start_date,
                'end_date' => $booking->end_date
            ];
        });

        return JsonResponses::ok('Datos de las reservas', $formattedData);
    }


    
    public function destroy(Request $request)
    {
        $dataRaw = $request->input('data');

        if (isset($dataRaw)) {
            $data = json_decode($dataRaw, true);

            $deleted = Booking::whereIn('booking_id', $data)->delete();
            if ($deleted == count($data)) {
                $response = JsonResponses::ok('Reservas eliminadas.');
            }
            else if ($deleted > 0) {
                $response = JsonResponses::ok('Reservas eliminadas. Algunas de los identificadores especificados no correspondieron a reservas.');
            }
            else {
                $response = JsonResponses::badRequest(
                    'No se pudo eliminar el recurso, compruebe que exista'
                );
            }
        } else {
            $response = JsonResponses::notAcceptable(
                'No se encontró el elemento \'data\' en la solicitud.'
            );
        }

        return $response;
    }
    public function destroySingle($bookingId)
    {
        $booking = Booking::find($bookingId);

        if ($booking) {
            // Eliminar la reserva
            $booking->delete();
            return JsonResponses::ok('Reserva eliminada.');
        } else {
            return JsonResponses::badRequest('No se encontró la reserva.');
        }
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