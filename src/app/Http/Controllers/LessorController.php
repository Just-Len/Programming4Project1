<?php

namespace App\Http\Controllers;

use App\Models\Lessor;
use Illuminate\Http\Request;

class LessorController extends Controller
{
    /**
     * Metodo GET
     */
    public function index()
    {
        $data=Lessor::all();
        $response=array(
            "status"=>200,
            "message"=>"Todos los registros de arrendadores",
            "data"=>$data
        );
        return response()->json($response,200);    
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
                $response=array(
                    'status'=>201,
                    'message'=>'Arrendador creada',
                    'lessor'=>$lessor
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
        $data=Lessor::find($id);
        if(is_object($data)){
            $data=$data->load('user');
            $response=array(
                'status'=>200,
                'message'=>'Datos del arrendador',
                'Lessor'=>$data
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
            $deleted=Lessor::where('booking_id',$id)->delete();
            if($deleted){
                $response=array(
                    'status'=>200,
                    'message'=>'Reserva eliminada',                    
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
