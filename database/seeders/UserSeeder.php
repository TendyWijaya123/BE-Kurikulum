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

        $prodis = Prodi::all();

        if ($roleAdmin && $prodis->count() > 0) {
            foreach ($prodis as $prodi) {
                User::create([
                    'name' => 'Admin ' . $prodi->name,
                    'email' => strtolower(str_replace(' ', '', $prodi->name)) . '@example.com',
                    'password' => Hash::make('password123'),
                    'role_id' => $roleAdmin->id,
                    'prodi_id' => $prodi->id,
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
