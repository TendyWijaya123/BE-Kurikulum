<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Daftar role dan guard_name yang akan dibuat
        $roles = [
            ['name' => 'Admin Penyusun Kurikulum', 'guard_name' => 'user'],
            ['name' => 'Penyusun Kurikulum', 'guard_name' => 'user'],
            ['name' => 'P2MPP', 'guard_name' => 'user'],
            ['name' => 'Dosen', 'guard_name' => 'dosen'],
            ['name' => 'Admin RPS', 'guard_name' => 'dosen'],
            ['name' => 'Ketua Prodi', 'guard_name' => 'dosen']
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate($role);
        }
    }
}
