<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dosens')->insert([
            [
                'nip' => '123456789',
                'nama' => 'Dr. Ahmad Fauzy',
                'email' => 'ahmad.fauzy@example.com',
                'password' => Hash::make('password123'),
                'jenis_kelamin' => 'L',
                'is_active' => true,
                'jurusan_id' => 2,
                'kode' => 'DF001A', // 6 karakter
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '987654321',
                'nama' => 'Dr. Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'password' => Hash::make('password123'),
                'jenis_kelamin' => 'L',
                'is_active' => false,
                'jurusan_id' => 1,
                'kode' => 'DF002B', // 6 karakter
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '456789123',
                'nama' => 'Dr. Siti Aisyah',
                'email' => 'siti.aisyah@example.com',
                'password' => Hash::make('password123'),
                'jenis_kelamin' => 'P',
                'is_active' => true,
                'jurusan_id' => 3,
                'kode' => 'DF003C', // 6 karakter
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
