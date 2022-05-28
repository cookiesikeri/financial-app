<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;

class awthJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        \Log::info('authorization token');
        $token = substr($request->header('Authorization'), 7);
        \Log::info($token);
        try {
            JWTAuth::setToken($token);
            $user = JWTAuth::toUser(JWTAuth::getToken());
            if ( empty($user) ) {
                return response()->json(['error', 'User not Found!'], 419);
            } else if ( $user->role_id != 3) {
                return response()->json(['error', 'This account is not associated to any vendor profile.'], 419);
            }
        } catch (\Exception $e) {
            if ($e instanceof Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['error'=>'Token is Invalid'], 419);
            }else if ($e instanceof Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['error'=>'Token is Expired'], 419);
            } else {
                return response()->json(['error'=>'Something is wrong'], 419);
            }
        }

        return $next($request);
    }
}
