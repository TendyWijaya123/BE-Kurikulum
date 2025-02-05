<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kurikulum;
use App\Models\Prodi;

class KurikulumSeeder extends Seeder
{
    /**
     * Jalankan database seeder.
     */
    public function run(): void
    {
        // Ambil semua prodi
        $prodis = Prodi::all();

        // Tambahkan kurikulum untuk setiap prodi
        foreach ($prodis as $prodi) {
            Kurikulum::create([
                'tahun_awal' => 2025,
                'tahun_akhir' => 2030,
                'is_active' => true,
                'prodi_id' => $prodi->id,
            ]);
        }
    }
}
