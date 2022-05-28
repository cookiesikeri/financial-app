<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use JWTAuth;

class BvnValidation
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
        if(!empty($user) && $user->bvn === null){
            return response()->json(['message' => 'please update your bvn'],403);
        }

        return $next($request);
    }
}
