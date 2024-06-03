<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Utils\JsonResponses;

class AdministratorApiAuthMiddleware extends ApiAuthMiddleware
{
    #[\Override]
    public function handle(Request $request, Closure $next): Response
    {
        $userName = $request->route('name');
        
        if ($customer = User::find($userName)) {
            $user = $customer->user;
        }
        else {
            return JsonResponses::notFound('No existe un cliente con el identificador especificado.');
        }

        return $this->checkUserTokenInfo($request, $next, $user, true);
    }
}
