<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataBillAddition extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Service::count() < 1) {
            DB::table('service')->insert([
                'name' => 'Glo',
                'status' => 1,
                'service_charge' => 0.00,
                'convenience_fee' => 0.00,
                'commission' => 0.00,
                'service_type_id' => 2,
                'api_id' => 1,
                'minimum_value' => 0,
                'maximum_value' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
