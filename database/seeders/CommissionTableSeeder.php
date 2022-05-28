<?php

namespace Database\Seeders;

use App\Models\AccountNumber;
use App\Models\Commission;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CommissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Commission::updateOrCreate([
            'type' => 'pos'
        ],[
            'type' => 'pos',
            'percent' => 0.0055
        ]);
    }
}
