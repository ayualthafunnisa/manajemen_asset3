<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            InstansiSeeder::class,
            UserSeeder::class,
            LicenseSeeder::class,
            KategoriSeeder::class,
            LokasiAssetSeeder::class,
            AssetSeeder::class,
        ]);
    }
}