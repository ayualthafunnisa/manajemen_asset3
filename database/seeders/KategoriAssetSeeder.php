<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriAssetSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kategori_assets')->insert([
            [
                'KodeKategori' => 'KTG-ELK',
                'NamaKategori' => 'Elektronik',
                'Deskripsi' => 'Peralatan elektronik seperti komputer dan printer',
                'InstansiID' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'KodeKategori' => 'KTG-MBL',
                'NamaKategori' => 'Meubel',
                'Deskripsi' => 'Peralatan furniture seperti meja dan kursi',
                'InstansiID' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}