<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class JwtAuth{
    private $key;
    function __construct(){
        $this->key="AThreeWithLemons";
    }

    public function getToken($name,$password){
        $pass=hash('sha256',$password);
        $user=User::where(['name'=>$name,'password'=>$pass])->first();
        if(is_object($user)){
            $token=array(
                'iss'=>$user->name,
                'email'=>$user->email_address,
                'role_id'=>$user->role_id,
                'iat'=>time(),
                'exp'=>time()+(5000)
            );
            $data = JWT::encode($token,$this->key,"HS256");
        }else{
            $data=array(
                'status'=>401,
                'message'=>'Datos de autenticacion incorrectos'
            );
        }
        return $data;
    }

    public function checkToken($jwt,$getId=false){
        $authFlag=false;
        if(isset($jwt)){
            try{
                $decoded=JWT::decode($jwt,new Key($this->key,'HS256'));
            }catch(\DomainException $ex){
                $authFlag = false;
            }catch(ExpiredException $ex){
                $authFlag = false;
            }
            if(!empty($decoded)&&is_object($decoded)&&isset($decoded->iss)){
                $authFlag = true;
            }
            if($getId && $authFlag){
                return $decoded;
            }
        }
    }
}