<?php

namespace App\Http\Controllers;

use App\Models\Lodging;
use Illuminate\Http\Request;

class LodgingController extends Controller
{
    public function index()
    {
        return Lodging::all();
        $response=array(
            "status"=>200,
            "message"=>"Todos los registros de las reservas",
            "data"=>$data
        );
        return response()->json($response,200);
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
                $response=array(
                    'status'=>201,
                    'message'=>'Reserva creada',
                    'lodging'=>$lodging
                );
            }else{
                $response=array(
                    'status'=>406,
                    'message'=>'Datos inválidos',
                    'errors'=>$isValid->errors()
                );
            }
        }else{
            $response=array(
                'status'=>400,
                'message'=>'No se encontró el objeto data'                
            );
        }
        return response()->json($response,$response['status']);
    }

    public function show($id){
        $data=Lodging::find($id);
        if(is_object($data)){
            $data=$data->load('lessor');

            $response=array(
                'status'=>200,
                'message'=>'Datos del alojamiento',
                'booking'=>$data
            );
        }else{
            $response=array(
                'status'=>404,
                'message'=>'Recurso no encontrado'                
            );
        }
        return response()->json($response,$response['status']);
    }

    public function destroy($id){
        if(isset($id)){
            $deleted=Lodging::where('lodging_id',$id)->delete();
            if($deleted){
                $response=array(
                    'status'=>200,
                    'message'=>'Alojamiento eliminado',                    
                );
            }else{
                $response=array(
                    'status'=>400,
                    'message'=>'No se pudo eliminar el recurso, compruebe que exista'                
                );
            }
        }else{
                $response=array(
                'status'=>406,
                'message'=>'Falta el identificador del recurso a eliminar'                
                );
            }
        return response()->json($response,$response['status']);
    }
}
