<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Dosen;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dosens = [
            [
                'nip' => '123456789',
                'nama' => 'Dr. Ahmad Fauzy',
                'email' => 'ahmad.fauzy@example.com',
                'username' => 'ahmadfauzy',
                'password' => Hash::make('password123'),
                'jenis_kelamin' => 'L',
                'is_active' => true,
                'jurusan_id' => 2,
                'kode' => 'DF001A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '987654321',
                'nama' => 'Dr. Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'username' => 'budisantoso',
                'password' => Hash::make('password123'),
                'jenis_kelamin' => 'L',
                'is_active' => false,
                'jurusan_id' => 1,
                'kode' => 'DF002B',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '456789123',
                'nama' => 'Dr. Siti Aisyah',
                'email' => 'siti.aisyah@example.com',
                'username' => 'sitiaisyah',
                'password' => Hash::make('password123'),
                'jenis_kelamin' => 'P',
                'is_active' => true,
                'jurusan_id' => 6,
                'kode' => 'DF003C',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($dosens as $dosenData) {
            $dosen = Dosen::create($dosenData);
            $dosen->assignRole('Dosen'); // Ganti 'dosen' sesuai nama role yang ada
        }
    }
}
