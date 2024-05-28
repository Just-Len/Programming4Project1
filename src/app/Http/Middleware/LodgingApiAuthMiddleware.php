<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Lodging;
use App\Utils\JsonResponses;

class LodgingApiAuthMiddleware extends ApiAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $lodgingId = $request->route('lodging_id');
        
        if ($lodging = Lodging::with('lessor.user')->find($lodgingId)) {
            $user = $lodging->lessor->user;
        }
        else {
            return JsonResponses::notFound('No existe un alojamiento con el identificador especificado.');
        }

        return $this->checkUserTokenInfo($request, $next, $user);
    }
}
