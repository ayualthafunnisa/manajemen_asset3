<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenghapusanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('penghapusans')->insert([
            [
                'assetID' => 1,
                'InstansiID' => 1,
                'no_surat_penghapusan' => 'SK-001/2025',
                'tanggal_pengajuan' => '2025-02-01',
                'jenis_penghapusan' => 'rusak_total',
                'alasan_penghapusan' => 'kerusakan_permanen',
                'nilai_buku' => 5000000,
                'deskripsi_penghapusan' => 'Laptop rusak total akibat korsleting.',
                'diajukan_oleh' => 3,
                'status_penghapusan' => 'draft',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}