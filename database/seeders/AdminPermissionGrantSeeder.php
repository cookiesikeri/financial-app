<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminPermissionGrantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = Permission::select('name')->get();

        $admin = Admin::where('email','admin@transave.com.ng')->first();

        $admin->assignRole('admin');
        $role = Role::findByName('admin','admin');

        foreach ($permissions as $permission){
            $role->givePermissionTo($permission->name);
        }

    }
}
