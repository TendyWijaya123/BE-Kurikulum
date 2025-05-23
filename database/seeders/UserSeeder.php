<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Prodi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run()
    {

        $prodis = Prodi::all();

        if ($prodis->count() > 0) {
            foreach ($prodis as $prodi) {
                $user = User::create([
                    'name' => 'Admin ' . $prodi->name,
                    'email' => strtolower(str_replace(' ', '', $prodi->name)) . '@polban.ac.id',
                    'username' => strtolower(str_replace(' ', '', $prodi->name)),
                    'password' => Hash::make('password123'),
                    'prodi_id' => $prodi->id,
                ]);
                $user->assignRole('Penyusun Kurikulum');
            }

            $usersWithoutProdi = [
                ['name' => 'P2MPP', 'email' => 'admin.p2mpp@polban.ac.id', 'username' => 'p2mpp'],
                ['name' => 'WD 1', 'email' => 'admin.wd1@polban.ac.id', ],
                ['name' => 'Admin', 'email' => 'admin@polban.ac.id', 'username' => 'admin'],
            ];

            $prodiTeknikKimia = Prodi::where('name', "D3 Teknik Kimia")->first();
            foreach ($usersWithoutProdi as $userData) {
                $user = User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'username' => $userData['username'] ?? Str::lower(str_replace(' ', '', $userData['name'])),
                    'password' => Hash::make('password123'),
                    'prodi_id' => $prodiTeknikKimia->id,
                ]);
                $user->assignRole('P2MPP');
            }
        }
    }
}
