<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // php artisan db:seed --class=UserSeeder
        //DB::table('admins')->truncate();
        $is_seeded = Admin::count() > 0 ;

        if(!$is_seeded) {
            DB::table('admins')->insert([
   
                [
                    'fname' => 'Chigo',
                    'lname' => 'Chigo',
                    'email' => 'chigo@transave.com.ng',
                    'password' => Hash::make('transave.123@'),
                ],
                [
                    'fname' => 'Support',
                    'lname' => 'Account',
                    'email' => 'support@transave.com.ng',
                    'password' => Hash::make('transave.123@'),
                ],
                [
                    'fname' => 'Info',
                    'lname' => 'Account',
                    'email' => 'info@transave.com.ng',
                    'password' => Hash::make('transave.123@'),
                ]
            ]);
        }
    }
}
