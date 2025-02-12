<?php

namespace Database\Seeders;

use App\Models\BentukPembelajaran;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            JurusanSeeder::class,
            ProdiSeeder::class,
            UserSeeder::class,
            KurikulumSeeder::class,
            IeaSeeder::class,
            IpteksSeeder::class,
            PengetahuanSeeder::class,
            BentukPembelajaranSeeder::class,
            MetodePembelajaranSeeder::class,
            FormulasiCpaSeeder::class,
            KnowledgeDimensionSedeer::class,
            DosenSeeder::class,
        ]);
    }
}
