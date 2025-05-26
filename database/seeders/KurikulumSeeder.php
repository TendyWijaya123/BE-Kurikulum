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
                'is_sksu' => 'belum',
                'is_bk' => 'belum',
                'is_ipteks' => 'belum',
                'is_perancangan_cpl' => 'belum',
                'is_vmt' => 'belum',
                'is_cpl_ppm_vm' => 'belum',
                'is_matriks_cpl_ppm' => 'belum',
                'is_matriks_cpl_iea' => 'belum',
                'is_pengatahuan' => 'belum',
                'is_matriks_cpl_p' => 'belum',
                'is_peta_kompetensi' => 'belum',
                'is_materi_pembelajaran' => 'belum',
                'is_matriks_p_mp' => 'belum',
                'is_matriks_p_mp_mk' => 'belum',
                'is_mata_kuliah' => 'belum',
                'is_jejaring_mata_kuliah' => 'belum',
                'is_matriks_cpl_mk' => 'belum',
                'prodi_id' => $prodi->id,
            ]);
        }
    }
}
