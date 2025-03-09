<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BentukPembelajaran;

class BentukPembelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama' => 'Kuliah'],
            ['nama' => 'Penugasan'],
            ['nama' => 'Praktikum'],
        ];

        foreach ($data as $item) {
            BentukPembelajaran::updateOrCreate(['nama' => $item['nama']], $item);
        }
    }
}
