<?php

namespace App\Http\Controllers;

use App\Models\Lodging;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
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

    public function indexBooking($id)
    {
        $bookings = Booking::where('lodging_id', $id)->with('payment')->get();

        return JsonResponses::ok(
            'Todos los registros de las reservas del alojamiento',
            $bookings,
            'bookings'
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

    public function destroy($lodgingId)
    {
        if (Booking::where('lodging_id', $lodgingId)->count() < 1) {
            $lodging = Lodging::find($lodgingId);

            $lodging->delete();
            $response = JsonResponses::ok('Alojamiento eliminado.');
        }
        else {
            $response = JsonResponses::badRequest(
                'No se puede eliminar este alojamiento, aun tiene reservas activas.'
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
                if (is_object($lodging)) {
                    if (isset($data['name'])) {
                        $lodging->name = $data['name'];
                    }
                    if (isset($data['description'])) {
                        $lodging->description = $data['description'];
                    }
                    if (isset($data['address'])) {
                        $lodging->address = $data['address'];
                    }
                    if (isset($data['per_night_price'])) {
                        $lodging->per_night_price = $data['per_night_price'];
                    }
                    if (isset($data['available_rooms'])) {
                        $lodging->available_rooms = $data['available_rooms'];
                    }
                    $lodging->save();
                    $response = JsonResponses::ok('Alojamiento actualizado');
                } else {
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
        } else {
            $response = JsonResponses::badRequest(
                'Debe ingresar la informacion en el formato correcto'
            );
        }
        return $response;
    }

    public function deleteImage($lodgingId)
    {
        $lodging = Lodging::find($lodgingId);

        if ($lodging->image) {
            $fileName = $lodging->image;
            Storage::disk('lodgings')->delete($fileName);
            $lodging->image = null;
            $lodging->save();

            $response = JsonResponses::ok(
                'Imagen eliminada con éxito.',
            );
        } else {
            $response = JsonResponses::notFound('El alojamiento no tiene una imagen.');
        }
        return $response;
    }

    public function uploadImage(Request $request)
    {
        $lodgingId = $request->route('lodging_id');
        $lodging = Lodging::find($lodgingId);

        $validation = validator($request->all(), ['file' => 'required|image|mimes:jpg,png,jpeg,svg']);
        if (!$validation->fails()) {
            $image = $request->file('file');
            $fileName = Str::uuid() . "." . $image->getClientOriginalExtension();

            Storage::disk('lodgings')->put($fileName, File::get($image));
            $lodging->image = $fileName;
            $lodging->save();

            $response = JsonResponses::created(
                'Imagen guardada.',
                'filename',
                $fileName
            );
        } else {
            $response = JsonResponses::notAcceptable(
                'No se encontró el archivo en la solicitud.',
                'errors',
                $validation->errors()
            );
        }
        return $response;
    }

    public function getImage($lodgingId)
    {
        if (!($lodging = Lodging::find($lodgingId))) {
            return JsonResponses::notFound('No existe un alojamiento con el identificador especificado.');
        }

        if (!$lodging->image) {
            return JsonResponses::notFound('El alojamiento no tiene una imagen.');
        }

        $fileName = $lodging->image;
        if ($fileName) {
            $exist = Storage::disk('lodgings')->exists($fileName);
            if ($exist) {
                $filePath = Storage::disk('lodgings')->path($fileName);
                $response = response()->file($filePath);
            } else {
                $response = JsonResponses::notFound(
                    'La imagen no existe.'
                );
            }
        }
        else {
            $response = JsonResponses::notFound('El alojamiento no tiene una imagen.');
        }
        return $response;
    }
}
