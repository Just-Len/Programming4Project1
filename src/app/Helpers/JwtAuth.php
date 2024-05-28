<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use App\Models\User;
use App\Utils\Data;

class JwtAuth
{
    private $key;
    function __construct()
    {
        $this->key = "AThreeWithLemons";
    }

    public function getToken($name, $password)
    {
        $data = null;
        $pass = Data::hash($password);
        $user = User::find($name);
        if (is_object($user) && $user->password == $pass) {
            $token = array(
                'iss' => $user->name,
                'email' => $user->email_address,
                'role_id' => $user->role_id,
                'last_logout' => $user->last_logout,
                'iat' => time(), // Unix timestamp in *seconds*
                'exp' => time() + 3_6000 // +1 hour 
            );
            $data = JWT::encode($token, $this->key, "HS256");
        }

        return $data;
    }

    public function checkToken($jwt, $getId = false)
    {
        $authFlag = false;

        if (isset($jwt)) {
            try {
                $decoded = JWT::decode($jwt, new Key($this->key, 'HS256'));
            } catch (\DomainException | ExpiredException $ex) {
                $authFlag = false;
            }

            if (!empty($decoded) && is_object($decoded) && isset($decoded->iss)) {
                $authFlag = true;
            }

            if ($getId && $authFlag) {
                return $decoded;
            }
        }
    }
}
