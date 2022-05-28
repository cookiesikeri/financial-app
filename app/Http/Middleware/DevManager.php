<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DevManager
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

        $auth = $request->header('dev-auth');

        return $next($request);
//        switch (env('APP_ENV')) {
//            case 'local':
//                return $this->localStagingEnv($auth,$next,$request);
//                break;
//            case 'staging':
//                return $this->localStagingEnv($auth,$next,$request);
//                break;
//            case 'production':
//                return $this->prodEnv($auth,$next,$request);
//                break;
//        }


    }

    private function localStagingEnv($auth,$next,$request)
    {
        if(in_array($auth,config('dev.transave'))){
            return $next($request);
        } else {
            Http::post('https://hooks.slack.com/services/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',[
                'text' => "Unknown trying to access - ".$request->getPathInfo(),
                'username' => 'Intruder Alert!!!! (sandbox and local)',
                'icon_emoji' => ':boom:'
            ]);
            return response()->json(['BOT' => 'Intruder','message' => 'Unauthorized access'],401);
        }
    }



    private function prodEnv($auth,$next,$request)
    {
        $converter = substr_replace(substr_replace(substr_replace($auth,env('TS_hidden_2'),env('TS_secret_2'),env('TS_count_2')),env('TS_hidden_1'),env('TS_secret_1'),env('TS_count_1')),env('TS_hidden_3'),env('TS_secret_3'),env('TS_count_3'));
        if($converter === env('TS_Security')){
            return $next($request);
        } else {
            Http::post('https://hooks.slack.com/services/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',[
                'text' => "Unknown trying to access - ".$request->getPathInfo(),
                'username' => 'Intruder Alert!!!! (live)',
                'icon_emoji' => ':boom:'
            ]);
            return response()->json(['BOT' => 'Intruder!!!','message' => 'Unauthorized access'],401);
        }
    }
}
