<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ItexBasicAuth
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
        $token = explode(' ',$request->header('Authorization'));

        $manual = base64_encode(env('ITEXUSERNAME').':'.env('ITEXPASSWORD'));
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        if(count($token) < 2){
            header('HTTP/1.1 401 Authorization Required');
            header('WWW-Authenticate: Basic realm="Access denied"');
            exit;
        }

        if ($token[1] !== $manual) {
            header('HTTP/1.1 401 Authorization Required');
            header('WWW-Authenticate: Basic realm="Access denied"');
            exit;
        } else {
            return $next($request);
        }
    }
}
