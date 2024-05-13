<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\JwtAuth;
use App\Utils\JsonResponses;

class ApiAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $jwt = new JwtAuth();
        $token = $request->bearerToken();
        $logged = $jwt->checkToken($token, true);
        if ($logged) {
            return $next($request);
        } else {
            return JsonResponses::unauthorized('No tiene privilegios para acceder a este recurso');
        }
    }
}
