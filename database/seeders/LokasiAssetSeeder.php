<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LokasiAsset;
use App\Models\Instansi;

class LokasiAssetSeeder extends Seeder
{
    public function run(): void
    {
        $instansis = Instansi::all();

        // Nilai enum sesuai migration:
        // JenisLokasi : ruangan | gudang | laboratorium | lapangan | lainnya
        // Status      : active  | maintenance | tidak_aktif | renovasi
        $templateLokasi = [
            [
                'KodeLokasi'             => 'LOK-01',
                'NamaLokasi'             => 'Ruang Kepala Sekolah',
                'PenanggungJawab'        => 'Kepala Sekolah',
                'nip'                    => null,
                'TeleponPenanggungJawab' => null,
                'JenisLokasi'            => 'ruangan',
                'Status'                 => 'active',
                'Keterangan'             => 'Ruang kerja kepala sekolah beserta tamu',
            ],
            [
                'KodeLokasi'             => 'LOK-02',
                'NamaLokasi'             => 'Ruang Tata Usaha',
                'PenanggungJawab'        => 'Kepala Tata Usaha',
                'nip'                    => null,
                'TeleponPenanggungJawab' => null,
                'JenisLokasi'            => 'ruangan',
                'Status'                 => 'active',
                'Keterangan'             => 'Ruang administrasi dan tata usaha',
            ],
            [
                'KodeLokasi'             => 'LOK-03',
                'NamaLokasi'             => 'Ruang Kelas',
                'PenanggungJawab'        => 'Wali Kelas',
                'nip'                    => null,
                'TeleponPenanggungJawab' => null,
                'JenisLokasi'            => 'ruangan',
                'Status'                 => 'active',
                'Keterangan'             => 'Ruang belajar siswa',
            ],
            [
                'KodeLokasi'             => 'LOK-04',
                'NamaLokasi'             => 'Laboratorium IPA',
                'PenanggungJawab'        => 'Kepala Lab IPA',
                'nip'                    => null,
                'TeleponPenanggungJawab' => null,
                'JenisLokasi'            => 'laboratorium',
                'Status'                 => 'active',
                'Keterangan'             => 'Laboratorium Fisika, Kimia, dan Biologi',
            ],
            [
                'KodeLokasi'             => 'LOK-05',
                'NamaLokasi'             => 'Laboratorium Komputer',
                'PenanggungJawab'        => 'Kepala Lab Komputer',
                'nip'                    => null,
                'TeleponPenanggungJawab' => null,
                'JenisLokasi'            => 'laboratorium',
                'Status'                 => 'active',
                'Keterangan'             => 'Laboratorium untuk praktikum TIK',
            ],
            [
                'KodeLokasi'             => 'LOK-06',
                'NamaLokasi'             => 'Perpustakaan',
                'PenanggungJawab'        => 'Kepala Perpustakaan',
                'nip'                    => null,
                'TeleponPenanggungJawab' => null,
                'JenisLokasi'            => 'ruangan',
                'Status'                 => 'active',
                'Keterangan'             => 'Ruang baca dan koleksi buku',
            ],
            [
                'KodeLokasi'             => 'LOK-07',
                'NamaLokasi'             => 'Lapangan Olahraga',
                'PenanggungJawab'        => 'Guru Olahraga',
                'nip'                    => null,
                'TeleponPenanggungJawab' => null,
                'JenisLokasi'            => 'lapangan',
                'Status'                 => 'active',
                'Keterangan'             => 'Lapangan upacara dan olahraga',
            ],
            [
                'KodeLokasi'             => 'LOK-08',
                'NamaLokasi'             => 'Gudang',
                'PenanggungJawab'        => 'Petugas Gudang',
                'nip'                    => null,
                'TeleponPenanggungJawab' => null,
                'JenisLokasi'            => 'gudang',
                'Status'                 => 'active',
                'Keterangan'             => 'Penyimpanan barang inventaris yang tidak aktif',
            ],
        ];

        $total = 0;
        foreach ($instansis as $instansi) {
            foreach ($templateLokasi as $lok) {
                LokasiAsset::create(array_merge($lok, [
                    'InstansiID' => $instansi->InstansiID,
                ]));
                $total++;
            }
        }

        $this->command->info("✅ LokasiAssetSeeder selesai: {$total} lokasi dibuat.");
    }
}