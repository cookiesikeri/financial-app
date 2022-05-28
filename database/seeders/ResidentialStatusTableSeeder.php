<?php

namespace Database\Seeders;

use App\Models\ResidentialStatus;
use Illuminate\Database\Seeder;

class ResidentialStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->statuses as $status ) {
            $exist = ResidentialStatus::on('mysql::read')->where('status', $status)->exists();
            if (!$exist) {
                ResidentialStatus::on('mysql::write')->create([
                    'status' => $status,
                ]);
            }
        }
    }
    protected $statuses = ['House Owner', 'Tenant', 'Others'];
}
