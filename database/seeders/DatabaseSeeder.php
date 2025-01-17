<?php

namespace Database\Seeders;

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
            PermissionSeeder::class,
            RoleSeeder::class,
            RoleHasPermissionSeeder::class,
            JurusanSeeder::class,
            ProdiSeeder::class,
            UserSeeder::class,
            KurikulumSeeder::class,
            IeaSeeder::class,
            IpteksSeeder::class,
            PengetahuanSeeder::class
        ]);
    }
}
