<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Instansi;

class InstansiSeeder extends Seeder
{
    public function run(): void
    {
        $instansis = [
            [
                'KodeInstansi'      => 'SKL-001',
                'NPSN'              => '10200001',
                'NamaSekolah'       => 'SMA Negeri 1 Medan',
                'JenjangSekolah'    => 'SMA',
                'provinsi_code'     => '12',        // Sumatera Utara
                'kota_code'         => '1275',      // Kota Medan
                'kecamatan_code'    => '1275010',   // Medan Kota
                'kelurahan_code'    => '1275010001',
                'KodePos'           => '20111',
                'EmailSekolah'      => 'sman1medan@sch.id',
                'Logo'              => null,
                'NamaKepalaSekolah' => 'Drs. Ahmad Fauzi, M.Pd',
                'NIPKepalaSekolah'  => '196504151990031001',
                'TanggalBerdiri'    => '1950-08-17',
                'Status'            => 'aktif',
            ],
            [
                'KodeInstansi'      => 'SKL-002',
                'NPSN'              => '10200002',
                'NamaSekolah'       => 'SMK Negeri 2 Medan',
                'JenjangSekolah'    => 'SMK',
                'provinsi_code'     => '12',
                'kota_code'         => '1275',
                'kecamatan_code'    => '1275020',
                'kelurahan_code'    => '1275020001',
                'KodePos'           => '20212',
                'EmailSekolah'      => 'smkn2medan@sch.id',
                'Logo'              => null,
                'NamaKepalaSekolah' => 'Hj. Siti Rahmawati, S.Pd, MM',
                'NIPKepalaSekolah'  => '197003201995032002',
                'TanggalBerdiri'    => '1965-01-10',
                'Status'            => 'aktif',
            ],
            [
                'KodeInstansi'      => 'SKL-003',
                'NPSN'              => '10200003',
                'NamaSekolah'       => 'SMP Negeri 3 Binjai',
                'JenjangSekolah'    => 'SMP',
                'provinsi_code'     => '12',
                'kota_code'         => '1272',      // Kota Binjai
                'kecamatan_code'    => '1272010',
                'kelurahan_code'    => '1272010001',
                'KodePos'           => '20711',
                'EmailSekolah'      => 'smpn3binjai@sch.id',
                'Logo'              => null,
                'NamaKepalaSekolah' => 'Budi Santoso, S.Pd',
                'NIPKepalaSekolah'  => '197512082000031003',
                'TanggalBerdiri'    => '1975-07-01',
                'Status'            => 'aktif',
            ],
        ];

        foreach ($instansis as $data) {
            Instansi::create($data);
        }

        $this->command->info('✅ InstansiSeeder selesai: ' . count($instansis) . ' instansi dibuat.');
    }
}