<?php

namespace App\Traits;

use App\Models\User;
use App\Models\Wallet;

trait ManagesCommission
{
    public function secureCommission($amount)
    {
        $support = User::on('mysql::read')->where('phone', config('support.phone'))->first();

        if(!empty($support)){
            $wallet = Wallet::on('mysql::write')
                ->where('user_id',$support->id)->first();

            $wallet->update([
                'balance' => (double) $wallet->balance + (double) $amount
            ]);
        }
    }
}
