<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('assets')->insert([
            [
                'InstansiID' => 1,
                'KategoriID' => 3,
                'LokasiID' => 1,
                'kode_asset' => 'AST-100',
                'nama_asset' => 'Laptop ASUS',
                'merk' => 'ASUS',
                'tipe_model' => 'VivoBook',
                'serial_number' => 'SN123457',
                'sumber_perolehan' => 'pembelian',
                'tanggal_perolehan' => '2024-01-01',
                'nilai_perolehan' => 10000000,
                'nilai_residu' => 1000000,
                'umur_ekonomis' => 5,
                'kondisi' => 'baik',
                'status_asset' => 'aktif',
                'jumlah' => 1,
                'satuan' => 'unit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}