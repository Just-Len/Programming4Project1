<?php

namespace App\Http\Controllers;

use App\Models\Lessor;
use Illuminate\Http\Request;
use App\Utils\JsonResponses;

class LessorController
{
    public function index()
    {
        $data = Lessor::all();
        return JsonResponses::ok(
            "Todos los registros de arrendadores",
            $data
        );
    }

    public function store(Request $request)
    {
        $data_input = $request->input('data', null);
        if ($data_input) {
            $data = json_decode($data_input, true);
            $data = array_map('trim', $data);
            $rules = [
                'user_name' => 'required|alpha_num|exist:user|max:50',
                'first_name' => 'required|string|max:50',
                'last_name' => 'required|string|max:100',
                'phone_number' => 'required|numeric|max:25',
            ];
            $isValid = validator($data, $rules);
            if (!$isValid->fails()) {
                $lessor = new Lessor();
                $lessor->user_name = $data('user_name');
                $lessor->first_name = $data('fist_name');
                $lessor->last_name = $data('last_name');
                $lessor->phone_number = $data('phone_number');
                $lessor->save();
                $response = JsonResponses::created(
                    'Arrendador creado',
                    'lessor',
                    $lessor
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
        $data = Lessor::find($id);
        if (is_object($data)) {
            $data = $data->load('user');
            $response = JsonResponses::ok(
                'Datos del arrendador',
                $data,
                'lessor'
            );
        } else {
            $response = JsonResponses::notFound('Recurso no encontrado');
        }
        return $response;
    }

    public function destroy($id = null)
    {
        if (isset($id)) {
            $deleted = Lessor::where('lessor_id', $id)->delete();
            if ($deleted) {
                $response = JsonResponses::ok('Arrendador eliminado');
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
                    'lessor_id' => 'required|numeric',
                ];
                $isValid = validator($data, $rules);
                if (!$isValid->fails()) {
                    $lessor = Lessor::find($data['lessor_id']);
                    if(is_object($lessor)){
                        if(isset($data['first_name'])){
                            $lessor->first_name = $data['first_name'];
                        }
                        if(isset($data['last_name'])){
                            $lessor->last_name = $data['last_name'];
                        }
                        if(isset($data['phone_number'])){
                            $lessor->phone_number = $data['phone_number'];
                        }
                        $lessor->save();
                        $response = JsonResponses::ok('Arrendador actualizado');
                    }else{
                        $response = JsonResponses::notAcceptable(
                            'No se encontro el arrendador',
                            'errors',
                            $isValid->errors()
                        );
                    }
                } else {
                    $response = JsonResponses::notAcceptable(
                        'Debe ingresar el ID de un arrendador existente y válido',
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
