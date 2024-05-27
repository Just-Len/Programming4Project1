<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\JwtAuth;
use App\Models\User;
use App\Utils\JsonResponses;
use Firebase\JWT\JWT;

class ApiAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $userName = $request->route('name');
        
        if ($userName) {
            $user = User::find($userName);
        }

        $jwt = new JwtAuth();
        $token = $request->bearerToken();
        $tokenInfo = $jwt->checkToken($token, true);
        if ($tokenInfo) {
            if ($user && $tokenInfo->iss != $user->name) {
                return JsonResponses::forbidden('No tiene los privilegios necesarios para acceder a este recurso.');
            }

            return $next($request);
        } else {
            return JsonResponses::unauthorized('No está autorizado a realizar esta acción.');
        }
    }
}
