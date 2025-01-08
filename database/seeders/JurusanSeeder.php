<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jurusan;

class JurusanSeeder extends Seeder
{
    public function run()
    {
        // Daftar jurusan yang termasuk dalam kategori Rekayasa
        $jurusansRekayasa = [
            ['nama' => 'Teknik Sipil', 'kategori' => 'Rekayasa'],
            ['nama' => 'Teknik Mesin', 'kategori' => 'Rekayasa'],
            ['nama' => 'Teknik Refrigerasi dan Tata Udara', 'kategori' => 'Rekayasa'],
            ['nama' => 'Teknik Konversi Energi', 'kategori' => 'Rekayasa'],
            ['nama' => 'Teknik Elektro', 'kategori' => 'Rekayasa'],
            ['nama' => 'Teknik Kimia', 'kategori' => 'Rekayasa'],
            ['nama' => 'Teknik Komputer dan Informatika', 'kategori' => 'Rekayasa'],
        ];

        // Daftar jurusan yang termasuk dalam kategori Non Rekayasa
        $jurusansNonRekayasa = [
            ['nama' => 'Akuntansi', 'kategori' => 'Non Rekayasa'],
            ['nama' => 'Administrasi Niaga', 'kategori' => 'Non Rekayasa'],
            ['nama' => 'Bahasa Inggris', 'kategori' => 'Non Rekayasa'],
        ];



        // Menambahkan jurusan Rekayasa ke dalam tabel Jurusan
        foreach ($jurusansRekayasa as $jurusan) {
            Jurusan::firstOrCreate($jurusan);
        }

        // Menambahkan jurusan Non Rekayasa ke dalam tabel Jurusan
        foreach ($jurusansNonRekayasa as $jurusan) {
            Jurusan::firstOrCreate($jurusan);
        }
    }
}
