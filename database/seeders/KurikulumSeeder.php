<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kurikulum;

class KurikulumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kurikulum::create([
            'tahun_awal' => 2020,
            'tahun_akhir' => 2024,
            'is_active' => true,
            'prodi_id' => 1,
        ]);
    }
}
