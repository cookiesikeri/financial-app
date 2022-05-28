<?php

namespace Database\Seeders;

use App\Models\AccountType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class AccountTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       // AccountType::truncate();

        foreach ($this->accounts as $account) {
            $exist = AccountType::where('name', $account)->exists();
            if (!$exist) {
                AccountType::create([
                    'name' => $account,
                ]);
            }
        }
    }

    /**
     * @var array
     */
    protected $accounts = [
        'Unverified',
        'Ordinary',
        'Classic',
        'Premium'
    ];
}
