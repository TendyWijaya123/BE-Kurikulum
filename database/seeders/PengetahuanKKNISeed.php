<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengetahuanKKNISeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pengetahuan_kkni')->insert([
            [
                'level' => 9,
                'pengetahuan_kkni' => 'Mampu memecahkan permasalahan sains, teknologi, dan/atau seni di dalam bidang keilmuannya melalui pendekatan inter, multi atau transdisipliner.',
                'jenjang' => 'Doktor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 8,
                'pengetahuan_kkni' => 'Mampu memecahkan permasalahan ilmu pengetahuan, teknologi, dan/atau seni di dalam bidang keilmuannya melalui pendekatan inter atau multidisipliner.',
                'jenjang' => 'Magister',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 7,
                'pengetahuan_kkni' => 'Mampu memecahkan permasalahan sains, teknologi, dan/atau seni di dalam bidang keilmuannya melalui pendekatan monodisipliner.',
                'jenjang' => 'Profesi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 6,
                'pengetahuan_kkni' => 'Menguasai konsep teoritis bidang pengetahuan tertentu secara umum dan konsep teoritis bagian khusus dalam bidang pengetahuan tersebut secara mendalam, serta mampu memformulasikan penyelesaian masalah prosedural.',
                'jenjang' => 'Sarjana',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 5,
                'pengetahuan_kkni' => 'Menguasai konsep teoritis bidang pengetahuan tertentu secara umum, serta mampu memformulasikan penyelesaian masalah prosedural.',
                'jenjang' => 'Diploma 3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 4,
                'pengetahuan_kkni' => 'Menguasai beberapa prinsip dasar bidang keahlian tertentu dan mampu menyelaraskan dengan permasalahan faktual di bidang kerjanya.',
                'jenjang' => 'Diploma 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 3,
                'pengetahuan_kkni' => 'Memiliki pengetahuan operasional yang lengkap, prinsip-prinsip serta konsep umum yang terkait dengan fakta bidang keahlian tertentu, sehingga mampu menyelesaikan berbagai masalah yang lazim dengan metode yang sesuai.',
                'jenjang' => 'Diploma 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
