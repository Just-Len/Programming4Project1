<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $data=Booking::all();
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
                'lodging_id'=>'required|exists:lodging',
                'customer_id'=>'required|exists:customer',
                'status_id'=>'required|alpha|exists:booking_status,booking_status_id',
                'start_date'=>'required|date_format:Y-m-d H:i',
                'end_date'=>'required|date_format:Y-m-d H:i'
            ];
            $isValid=\validator($data,$rules);
            if(!$isValid->fails()){
                $booking=new Booking();
                $booking->lodging_id=$data['lodging_id'];
                $booking->customer_id=$data['customer_id'];
                $booking->status_id=$data['status_id'];
                $booking->start_date=$data['start_date'];
                $booking->end_date=$data['end_date'];
                $booking->save();
                $response=array(
                    'status'=>201,
                    'message'=>'Reserva creada',
                    'booking'=>$booking
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
        $data=Booking::find($id);
        if(is_object($data)){
            $data=$data->load('lodging');
            $data=$data->load('customer');
            $response=array(
                'status'=>200,
                'message'=>'Datos de la reserva',
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
            $deleted=Booking::where('booking_id',$id)->delete();
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
