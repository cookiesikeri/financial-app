<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'level 1',
            'level 2',
            'level 3',
            'level 4',
            'admin'
        ];

        foreach ($roles as $key => $role){

            Role::updateOrCreate([
                'name' => $role,
            ],[
                'id' => $key+1,
                'name' => $role,
                'guard_name' => 'admin'
            ]);

        }
    }
}
