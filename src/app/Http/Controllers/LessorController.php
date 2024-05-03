<?php

namespace App\Http\Controllers;

use App\Models\Lessor;
use Illuminate\Http\Request;
use App\Utils\Responses;

class LessorController extends Controller
{
    /**
     * Metodo GET
     */
    public function index()
    {
        $data=Lessor::all();
        return Responses::ok(
            "Todos los registros de arrendadores",
            $data);
    }

    /**
     * Metodo POST
     */
    public function store(Request $request){
        $data_input=$request->input('data',null);
        if($data_input){
            $data=json_decode($data_input,true);
            $data=array_map('trim',$data);
            $rules=[
                'user_name'=>'required|alpha|exist:user',
                'first_name'=>'required|alpha',
                'last_name'=>'required|alpha',
                'phone_number'=>'required|numeric',
                'email_address'=>'required|alpha|email:rfc,dns'
            ];
            $isValid=\validator($data,$rules);
            if(!$isValid->fails()){
                $lessor = new Lessor();
                $lessor->user_name=$data('user_name');
                $lessor->first_name=$data('fist_name');
                $lessor->last_name=$data('last_name');
                $lessor->phone_number=$data('phone_number');
                $lessor->email_address=$data('email_address');
                $lessor->save();
                $response= Responses::created(
                    'Arrendador creada',
                    'lessor',
                    $lessor
                );
            } else {
                $response = Responses::notAcceptable(
                    'Datos inválidos',
                    'errors',
                    $isValid->errors()
                );
            }
        } else {
            $response = Responses::badRequest('No se encontró el objeto data');
        }

        return $response;
    }

    public function show($id){
        $data=Lessor::find($id);
        if(is_object($data)){
            $data = $data->load('user');
            $response = Responses::ok(
                'Datos del arrendador',
                $data,
                'lessor'
            );
        }else{
            $response = Responses::notFound('Recurso no encontrado');
        }
        return $response;
    }

    public function destroy($id){
        if(isset($id)){
            $deleted=Lessor::where('booking_id',$id)->delete();
            if($deleted){
                $response=Responses::ok('Reserva eliminada');
            } else {
                $response = Responses::badRequest(
                    'No se pudo eliminar el recurso, compruebe que exista'                
                );
            }
        }else{
            $response = Responses::notAcceptable(
                'Falta el identificador del recurso a eliminar'                
            );
        }
        return $response;
    }
}
