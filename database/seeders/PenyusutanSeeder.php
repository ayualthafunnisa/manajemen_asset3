<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenyusutanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('penyusutans')->insert([
            [
                'assetID' => 10,
                'InstansiID' => 3,
                'tahun' => 2025,
                'bulan' => 1,
                'periode_awal' => '2025-01-01',
                'periode_akhir' => '2025-01-31',
                'nilai_awal' => 10000000,
                'nilai_penyusutan' => 1800000,
                'nilai_akhir' => 8200000,
                'akumulasi_penyusutan' => 1800000,
                'metode' => 'garis_lurus',
                'persentase_penyusutan' => 20,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}