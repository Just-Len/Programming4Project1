<?php

namespace App\Http\Controllers;

use App\Models\Lodging;
use Illuminate\Http\Request;
use App\Utils\Responses;

class LodgingController extends Controller
{
    public function index()
    {
        return Lodging::all();
        return Responses::ok(
            "Todos los registros de las reservas",
            $data,
        );
    }

    public function store(Request $request){
        $data_input=$request->input('data',null);
        if($data_input){
            $data=json_decode($data_input,true);
            $rules=[
                'lessor_id'=>'required|exists:lessor',
                'name'=>'required|alpha',
                'description'=>'required',
                'address'=>'required',
                'per_night_price'=>'required|numeric',
                'available_rooms'=>'required|numeric'
            ];
            $isValid=\validator($data,$rules);
            if(!$isValid->fails()){
                $lodging = new Lodging();
                $lodging->lessor_id=$data['lessor_id'];
                $lodging->name=$data['name'];
                $lodging->description=$data['description'];
                $lodging->address=$data['address'];
                $lodging->per_night_price=$data['per_night_price'];
                $lodging->available_rooms=$data['available_rooms'];
                $lodging->save();
                $response = Responses::created(
                    'Reserva creada',
                    $lodging
                );
            }else{
                $response = Responses::notAcceptable(
                    'Datos inválidos',
                    'errors',
                    $isValid->errors()
                );
            }
        }else{
            $response = Responses::badRequest('No se encontró el objeto data');
        }
        return $response;
    }

    public function show($id){
        $data=Lodging::find($id);
        if(is_object($data)){
            $data=$data->load('lessor');

            $response = Responses::ok(
                'Datos del alojamiento',
                $data,
                'lodging' 
            );
        }else{
            $response = Responses::notFound(
                'Recurso no encontrado'                
            );
        }
        return $response;
    }

    public function destroy($id){
        if(isset($id)){
            $deleted=Lodging::where('lodging_id',$id)->delete();
            if($deleted){
                $response = Responses::ok('Alojamiento eliminado');
            }else{
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
