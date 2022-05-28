<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminSeeder::class);
        $this->call(EmploymentStatusTableSeeder::class);
        $this->call(IncomeTableSeeder::class);
        $this->call(LGATableSeeder::class);
        $this->call(QualificationTableSeeder::class);
        $this->call(ResidentialStatusTableSeeder::class);
        $this->call(AccountTypeTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(IdCardTableSeeder::class);
    }

    public function boot()
    {
        $this->call(AdminSeeder::class);
    }
}
