<?php

namespace App\Http\Controllers;

use App\Models\Lodging;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Utils\JsonResponses;

class LodgingController
{
    public function index()
    {
        $data = Lodging::all();
        return JsonResponses::ok(
            "Todos los registros de los alojamientos",
            $data,
        );
    }

    public function store(Request $request)
    {
        $data_input = $request->input('data', null);
        if ($data_input) {
            $data = json_decode($data_input, true);
            $data = array_map('trim', $data);
            $rules = [
                'lessor_id' => 'required|exists:lessor',
                'name' => 'required|string|max:150',
                'description' => 'required|string|max:1000',
                'address' => 'required|string|max:300',
                'per_night_price' => 'required|decimal:2,6',
                'available_rooms' => 'required|integer'
            ];
            $validation = validator($data, $rules);
            if (!$validation->fails()) {
                $lodging = new Lodging();
                $lodging->lessor_id = $data['lessor_id'];
                $lodging->name = $data['name'];
                $lodging->description = $data['description'];
                $lodging->address = $data['address'];
                $lodging->per_night_price = $data['per_night_price'];
                $lodging->available_rooms = $data['available_rooms'];
                $lodging->save();

                $response = JsonResponses::created(
                    'Alojamiento creado',
                    'lodging',
                    $lodging
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

    public function show($id)
    {
        $data = Lodging::find($id);
        if (is_object($data)) {
            $data = $data->load('lessor');

            $response = JsonResponses::ok(
                'Datos del alojamiento',
                $data,
                'lodging'
            );
        } else {
            $response = JsonResponses::notFound(
                'Recurso no encontrado'
            );
        }
        return $response;
    }

    public function destroy($id = null)
    {
        if (isset($id)) {
            if(Booking::where('lodging_id', $id)->count() < 1){
                $deleted = Lodging::where('lodging_id', $id)->delete();
                if ($deleted) {
                    $response = JsonResponses::ok('Alojamiento eliminado');
                } else {
                    $response = JsonResponses::badRequest(
                        'No se pudo eliminar el recurso, compruebe que exista'
                    );
                }
            }else{
                $response = JsonResponses::badRequest(
                    'No se puede eliminar este alojamiento, aun tiene reservas activas'
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
                    'lodging_id' => 'required|numeric',
                ];
                $validation = validator($data, $rules);
                if (!$validation->fails()) {
                    $lodging = Lodging::find($data['lodging_id']);
                    if(is_object($lodging)){
                        if(isset($data['name'])){
                            $lodging->name = $data['name'];
                        }
                        if(isset($data['description'])){
                            $lodging->description = $data['description'];
                        }
                        if(isset($data['address'])){
                            $lodging->address = $data['address'];
                        }
                        if(isset($data['per_night_price'])){
                            $lodging->per_night_price = $data['per_night_price'];
                        }
                        if(isset($data['available_rooms'])){
                            $lodging->available_rooms = $data['available_rooms'];
                        }
                        $lodging->save();
                        $response = JsonResponses::ok('Alojamiento actualizado');
                    }else{
                        $response = JsonResponses::notAcceptable(
                            'No se encontró el alojamiento',
                            'errors',
                            $validation->errors()
                        );
                    }
                } else {
                    $response = JsonResponses::notAcceptable(
                        'Debe ingresar el ID de un alojamiento existente y válido',
                        'errors',
                        $validation->errors()
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
