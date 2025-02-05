<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Prodi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run()
    {
        $roleAdmin = Role::where('name', 'admin')->first();

        if ($roleAdmin) {
            // Membuat user untuk setiap prodi
            $prodis = Prodi::all();
            foreach ($prodis as $prodi) {
                User::create([
                    'name' => $prodi->name,
                    'email' => Str::slug($prodi->name, '.') . '@polban.ac.id',
                    'password' => Hash::make('password123'),
                    'role_id' => $roleAdmin->id,
                    'prodi_id' => $prodi->id,
                ]);
            }

            // Membuat tiga user tanpa prodi
            $usersWithoutProdi = [
                ['name' => 'P2MPP', 'email' => 'admin.p2mpp@polban.ac.id'],
                ['name' => 'WD 1', 'email' => 'admin.wd1@polban.ac.id'],
                ['name' => 'Admin', 'email' => 'admin@polban.ac.id'],
            ];

            foreach ($usersWithoutProdi as $userData) {
                User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make('password123'),
                    'role_id' => $roleAdmin->id,
                    'prodi_id' => null,  // Tanpa prodi
                ]);
            }
        }
    }
}
