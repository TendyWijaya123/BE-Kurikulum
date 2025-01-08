<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prodi;
use App\Models\Jurusan;

class ProdiSeeder extends Seeder
{
    public function run()
    {
        // Menambahkan prodi untuk jurusan Teknik Kimia
        $jurusanTeknikKimia = Jurusan::where('nama', 'Teknik Kimia')->first();

        if ($jurusanTeknikKimia) {
            // Menambahkan beberapa program studi
            Prodi::firstOrCreate([
                'name' => 'D-3 Teknik Kimia',
                'jenjang' => 'D3',
                'kode' => 'TKD3',
                'jurusan_id' => $jurusanTeknikKimia->id,
            ]);

            Prodi::firstOrCreate([
                'name' => 'D-3 Analis Kimia',
                'jenjang' => 'D3',
                'kode' => 'TAKD3',
                'jurusan_id' => $jurusanTeknikKimia->id,
            ]);

            Prodi::firstOrCreate([
                'name' => 'D-4 Teknik Kimia Produksi Bersih',
                'jenjang' => 'D4',
                'kode' => 'TKD4',
                'jurusan_id' => $jurusanTeknikKimia->id,
            ]);
        }
    }
}
