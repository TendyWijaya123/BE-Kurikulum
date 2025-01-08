<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role; // Import Role model
use App\Models\Prodi; // Import Prodi model
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run()
    {
        $roleAdmin = Role::where('name', 'admin')->first();

        $prodiD3TeknikKimia = Prodi::where('name', 'D-3 Teknik Kimia')->first();

        if ($roleAdmin && $prodiD3TeknikKimia) {
            for ($i = 1; $i <= 15; $i++) {
                User::create([
                    'name' => 'Admin Prodi ' . $i,
                    'email' => 'adminprodi' . $i . '@example.com',
                    'password' => Hash::make('password123'),
                    'role_id' => $roleAdmin->id,
                    'prodi_id' => $prodiD3TeknikKimia->id,
                ]);
            }
        }

        if ($roleAdmin) {
            for ($i = 1; $i <= 15; $i++) {
                User::create([
                    'name' => 'Admin Tanpa Prodi ' . $i,
                    'email' => 'admintanpaprodi' . $i . '@example.com',
                    'password' => Hash::make('password123'),
                    'role_id' => $roleAdmin->id,
                    'prodi_id' => null,  // Admin tanpa prodi_id
                ]);
            }
        }
    }
}
