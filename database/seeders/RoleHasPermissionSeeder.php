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

        $p2mppRole = Role::firstOrCreate(['name' => 'p2mpp']);
        if ($p2mppRole) {
            $p2mppRole->permissions()->sync($permissions);
        }
    }
}
