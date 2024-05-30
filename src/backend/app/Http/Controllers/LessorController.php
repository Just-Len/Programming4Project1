<?php

namespace App\Http\Controllers;

use App\Models\Lessor;
use App\Utils\JsonResponses;

class LessorController
{
    public function show($id = null)
    {
        if (!$id) {
            $data = Lessor::all();
            $response = JsonResponses::ok(
                "Todos los registros de arrendadores",
                $data
        );
        }
        else {
            $lessor = Lessor::find($id);
            if ($lessor) {
                $data = $lessor->load('user');
                $response = JsonResponses::ok(
                    'Datos del arrendador',
                    $data,
                    'lessor'
                );
            } else {
                $response = JsonResponses::notFound('Recurso no encontrado');
            }
        }
        return $response;
    }
}