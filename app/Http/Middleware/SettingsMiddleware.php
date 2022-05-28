<?php

namespace App\Http\Middleware;

use App\Models\Settings;
use Closure;
use Illuminate\Http\Request;

class SettingsMiddleware
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
//        foreach (self::VENDOR as $vendor) {
            $settings = Settings::on('mysql::read')->where('control_type','transfer')->first();
            switch ($settings->name) {
                case 'VFD':
                    return response()->json(['meess' => 'welcome vfd']);
                    return redirect()->route('vfd-outward');
//                    break;
            case 'APPZONE':
                $addition = 2 + 3 + $request->input('amount');
//                return response()->json(['meess' => 'welcome app zone','math'=> $addition,'method' => $request->method()]);
//                return route('appZone-outward');
                return redirect('/api/bank-transfer-peace');
//                return response()->json(['message' => 'Pass through with PEACE'],201);
//                break;
            }
//        }

        return $next($request);
    }
}
