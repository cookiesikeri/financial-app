<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use JWTAuth;

class CustomerAuthorizer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = substr($request->header('Authorization'), 7);
        JWTAuth::setToken($token);
        $user = JWTAuth::toUser(JWTAuth::getToken());
        if(!empty($user->customer_verification) && $user->customer_verification->authorized_stat !== 1){
            return response()->json(['message' => 'Sorry you can\'t perform any transaction. You\'ve not been verified, please contact Customer Service. '],403);
        }elseif ($user->shutdown_level !== 0) {
            return response()->json(['message' => 'Your account has been deactivated from making transaction. unblock or contact Customer service'], 405);
        }
        return $next($request);
    }


}
