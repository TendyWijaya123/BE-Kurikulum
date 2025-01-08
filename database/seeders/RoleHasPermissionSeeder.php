<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RoleHasPermissionSeeder extends Seeder
{
    public function run()
    {
        $adminRole = Role::firstWhere('name', 'admin');

        $permissions = Permission::all();

        if ($adminRole) {
            $adminRole->permissions()->sync($permissions);
        }
    }
}
