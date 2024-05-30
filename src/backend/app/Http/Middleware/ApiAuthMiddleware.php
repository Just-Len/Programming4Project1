<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\JwtAuth;
use App\Models\User;
use App\Utils\JsonResponses;

class ApiAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $userName = $request->route('name');
        $user = User::find($userName);
        
        if (!$user) {
            return JsonResponses::notFound('No existe un usuario con el nombre especificado.');
        }
    
        return $this->checkUserTokenInfo($request, $next, $user);
    }

    protected function checkUserTokenInfo($request, $next, $user)
    {
        $jwt = new JwtAuth();
        $token = $request->bearerToken();
        $tokenInfo = $jwt->checkToken($token, true);

        if ($tokenInfo) {
            if ($user && ($tokenInfo->iss != $user->name || $tokenInfo->last_logout != $user->last_logout)) {
                return JsonResponses::forbidden('No tiene los privilegios necesarios para acceder a este recurso.');
            }

            return $next($request);
        } else {
            return JsonResponses::unauthorized('No está autorizado a realizar esta acción.');
        }
    }
}