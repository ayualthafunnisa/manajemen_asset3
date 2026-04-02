<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LokasiAssetSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('lokasi_assets')->insert([
            [
                'InstansiID' => 1,
                'KodeLokasi' => 'LKS-01',
                'NamaLokasi' => 'Ruang TU',
                'Gedung' => 'Gedung A',
                'Lantai' => '1',
                'Ruangan' => 'TU-01',
                'PenanggungJawab' => 'Budi',
                'JenisLokasi' => 'ruangan',
                'Status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}