<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class SuspensionMiddleware
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
            if(!empty($user) && $user->status !== 1){
                return response()->json(['message' => 'Your Account has been deactivated, please contact Admin'],403);
            }

        return $next($request);
    }
}
