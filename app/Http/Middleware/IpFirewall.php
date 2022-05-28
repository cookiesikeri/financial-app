<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class IpFirewall
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public $restrictIps = [
        'ip-addr-1',
        'ip-addr-2',
        '192.168.0.1',
        '202.173.125.72',
        '192.168.0.3',
        '202.173.125.71',
        '127.0.0.1',
        '192.168.17.0/24',
        '127.0.0.1/255.255.255.255',
        '10.0.0.1-10.0.0.255',
        '172.17.*.*',
        'country:br',
        '/usr/bin/firewall/blacklisted.txt',
    ];

    public function handle(Request $request, Closure $next)
    {
        if(env('APP_ENV') === 'staging'){
            if (in_array($request->ip(), $this->restrictIps)) {
                Http::post('https://hooks.slack.com/services/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',[
                    'text' => "Unknown trying to access - ".$request->getPathInfo(),
                    'username' => 'Intruder Alert!!!! (live)',
                    'icon_emoji' => ':boom:'
                ]);
                return response()->json(['BOT' => 'Intruder!!!','message' => 'You don\'t have permission to access this application'],401);
            }
        }
        return $next($request);
    }
}
