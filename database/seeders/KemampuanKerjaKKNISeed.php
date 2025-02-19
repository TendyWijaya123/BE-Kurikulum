<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KemampuanKerjaKKNISeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kemampuan_kerja_kkni')->insert([
            [
                'level' => 9,
                'kemampuan_kerja_kkni' => 'Mampu mengembangkan pengetahuan, teknologi, dan/atau seni baru di dalam bidang keilmuannya atau praktek profesionalnya melalui riset, hingga menghasilkan karya kreatif, original, dan teruji.',
                'jenjang' => 'Doktor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 8,
                'kemampuan_kerja_kkni' => 'Mampu mengembangkan pengetahuan, teknologi, dan/atau seni di dalam bidang keilmuannya atau praktek profesionalnya melalui riset, hingga menghasilkan karya inovatif dan teruji.',
                'jenjang' => 'Magister',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 7,
                'kemampuan_kerja_kkni' => 'Mampu merencanakan dan mengelola sumberdaya di bawah tanggung jawabnya, dan mengevaluasi secara komprehensif kerjanya dengan memanfaatkan ilmu pengetahuan, teknologi, dan/atau seni untuk menghasilkan langkah-langkah pengembangan strategis organisasi.',
                'jenjang' => 'Profesi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 6,
                'kemampuan_kerja_kkni' => 'Mampu mengaplikasikan bidang keahliannya dan memanfaatkan ilmu pengetahuan, teknologi, dan/atau seni pada bidangnya dalam penyelesaian masalah serta mampu beradaptasi terhadap situasi yang dihadapi.',
                'jenjang' => 'Sarjana',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 5,
                'kemampuan_kerja_kkni' => 'Mampu menyelesaikan pekerjaan berlingkup luas, memilih metode yang sesuai dari beragam pilihan yang sudah maupun belum baku dengan menganalisis data, serta mampu menunjukkan kinerja dengan mutu dan kuantitas yang terukur.',
                'jenjang' => 'Diploma 3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 4,
                'kemampuan_kerja_kkni' => 'Mampu menyelesaikan tugas berlingkup luas dan kasus spesifik dengan menganalisis informasi secara terbatas, memilih metode yang sesuai dari beberapa pilihan yang baku, serta mampu menunjukkan kinerja dengan mutu dan kuantitas yang terukur.',
                'jenjang' => 'Diploma 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 3,
                'kemampuan_kerja_kkni' => 'Mampu melaksanakan serangkaian tugas spesifik, dengan menerjemahkan informasi dan menggunakan alat, berdasarkan sejumlah pilihan prosedur kerja, serta mampu menunjukkan kinerja dengan mutu dan kuantitas yang terukur, yang sebagian merupakan hasil kerja sendiri dengan pengawasan tidak langsung.',
                'jenjang' => 'Diploma 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
