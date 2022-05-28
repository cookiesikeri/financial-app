<?php

namespace App\Http\Middleware;

use App\Exceptions\WithdrawalLimitException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionLimit
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws WithdrawalLimitException
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check() && Auth::user()->shutdown_level == 0)
        {
            if(Auth::user()->totalSumOfDailyWithdrawals() <= Auth::user()->withdrawal_limit)
            {
                return $next($request);
            }else{
                throw new WithdrawalLimitException();
            }
        }
        abort(404);
    }
}
