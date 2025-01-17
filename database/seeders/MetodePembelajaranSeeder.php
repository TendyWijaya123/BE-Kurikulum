<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MetodePembelajaran;

class MetodePembelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama' => 'Pbl'],
            ['nama' => 'Case Study'],
            ['nama' => 'Magang'],
            ['nama' => 'Ceramah'],
            ['nama' => 'Diskusi'],
            ['nama' => 'Demonstrasi'],
            ['nama' => 'Ekspositori'],
        ];

        foreach ($data as $item) {
            MetodePembelajaran::updateOrCreate(['nama' => $item['nama']], $item);
        }
    }
}
