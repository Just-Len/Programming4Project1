<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Customer;
use App\Utils\JsonResponses;

class CustomerApiAuthMiddleware extends ApiAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $customerId = $request->route('customer_id');
        
        if ($customer = Customer::with('user')->find($customerId)) {
            $user = $customer->user;
        }
        else {
            return JsonResponses::notFound('No existe un cliente con el identificador especificado.');
        }

        return $this->checkUserTokenInfo($request, $next, $user);
    }
}
