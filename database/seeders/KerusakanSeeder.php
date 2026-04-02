<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KerusakanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kerusakans')->insert([
            [
                'assetID' => 1,
                'InstansiID' => 1,
                'LokasiID' => 1,
                'kode_laporan' => 'LAP-001',
                'tanggal_laporan' => '2025-01-10',
                'tanggal_kerusakan' => '2025-01-09',
                'jenis_kerusakan' => 'berat',
                'deskripsi_kerusakan' => 'Laptop tidak bisa menyala.',
                'prioritas' => 'tinggi',
                'status_perbaikan' => 'dilaporkan',
                'dilaporkan_oleh' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}