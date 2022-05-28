<?php

namespace Database\Seeders;

use App\Models\AccountNumber;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SupportTableSeeder extends Seeder
{
    public const ACCOUNTS = [
        [
            'name' =>  'VFD Microfinance Bank Limited',
            'number' => 1109249890
        ],

        [
            'name' =>  'Wallet ID',
            'number' => 5190222200
        ],

    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::updateOrCreate([
            'phone' => config('support.phone'),
            'email' => config('support.email')
        ],[
            'name' => config('support.name'),
            'phone' => config('support.phone'),
            'email' => config('support.email'),
            'password' => Hash::make(config('support.password')),
            'withdrawal_limit' => 0.00,
        ]);

        $wallet =  Wallet::updateOrCreate([
            'user_id' => $user->id
        ],[
            'user_id' => $user->id
        ]);

        foreach (self::ACCOUNTS as $account) {
            AccountNumber::updateOrCreate([
                'account_name' => $account['name'],
                'wallet_id' => $wallet->id
            ],[
                'wallet_id' => $wallet->id,
                'account_name' => $account['name'],
                'account_number' => $account['number'],
            ]);
        }
    }
}
