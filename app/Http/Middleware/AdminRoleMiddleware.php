<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Traits\ManagesResponse;

class AdminRoleMiddleware
{
    use ManagesResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('admin')->check())
        {
            if(Auth::guard('admin')->user()->isAdmin())
            {
                return $next($request);
            }else{
                return $this->sendError('Only admins can access this info.');
            }
        }

        /* try {
            $admin = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return $this->sendError('Token is Invalid');
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return $this->sendError('Token is Expired');
            }else{
                return $this->sendError('Authorization Token not found');
            }
        } */

        //return $next($request);
    }
}
