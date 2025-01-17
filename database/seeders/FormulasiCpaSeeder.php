<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormulasiCpaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Kategori Kognitif
            ['kode' => 'C1', 'deskripsi' => 'Pengetahuan', 'kategori' => 'C'],
            ['kode' => 'C2', 'deskripsi' => 'Pemahaman', 'kategori' => 'C'],
            ['kode' => 'C3', 'deskripsi' => 'Penerapan', 'kategori' => 'C'],
            ['kode' => 'C4', 'deskripsi' => 'Analisis', 'kategori' => 'C'],
            ['kode' => 'C5', 'deskripsi' => 'Sintesis', 'kategori' => 'C'],
            ['kode' => 'C6', 'deskripsi' => 'Evaluasi', 'kategori' => 'C'],

            // Kategori Psikomotorik
            ['kode' => 'P1', 'deskripsi' => 'Pengamatan', 'kategori' => 'P'],
            ['kode' => 'P2', 'deskripsi' => 'Persiapan', 'kategori' => 'P'],
            ['kode' => 'P3', 'deskripsi' => 'Respon Terbimbing', 'kategori' => 'P'],
            ['kode' => 'P4', 'deskripsi' => 'Mekanisme', 'kategori' => 'P'],
            ['kode' => 'P5', 'deskripsi' => 'Kompleksitas', 'kategori' => 'P'],
            ['kode' => 'P6', 'deskripsi' => 'Adaptasi', 'kategori' => 'P'],

            // Kategori Afektif
            ['kode' => 'A1', 'deskripsi' => 'Menerima', 'kategori' => 'A'],
            ['kode' => 'A2', 'deskripsi' => 'Menanggapi', 'kategori' => 'A'],
            ['kode' => 'A3', 'deskripsi' => 'Menilai', 'kategori' => 'A'],
            ['kode' => 'A4', 'deskripsi' => 'Organisasi', 'kategori' => 'A'],
            ['kode' => 'A5', 'deskripsi' => 'Karakterisasi', 'kategori' => 'A'],
        ];

        DB::table('formulasi_cpas')->insert($data);
    }
}
