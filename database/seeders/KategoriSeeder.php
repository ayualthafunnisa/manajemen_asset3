<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Instansi;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $instansis = Instansi::all();

        // Kategori standar yang akan dibuat untuk setiap instansi
        $templateKategori = [
            ['KodeKategori' => 'KAT-01', 'NamaKategori' => 'Peralatan Elektronik',   'Deskripsi' => 'Komputer, laptop, proyektor, printer, dan perangkat elektronik lainnya'],
            ['KodeKategori' => 'KAT-02', 'NamaKategori' => 'Mebel & Perabot',         'Deskripsi' => 'Meja, kursi, lemari, rak buku, dan perabot kantor/kelas'],
            ['KodeKategori' => 'KAT-03', 'NamaKategori' => 'Kendaraan',               'Deskripsi' => 'Kendaraan operasional sekolah'],
            ['KodeKategori' => 'KAT-04', 'NamaKategori' => 'Peralatan Olahraga',      'Deskripsi' => 'Peralatan dan perlengkapan olahraga'],
            ['KodeKategori' => 'KAT-05', 'NamaKategori' => 'Peralatan Laboratorium',  'Deskripsi' => 'Alat dan bahan untuk laboratorium IPA, komputer, bahasa'],
            ['KodeKategori' => 'KAT-06', 'NamaKategori' => 'Buku & Pustaka',          'Deskripsi' => 'Koleksi buku perpustakaan, buku teks pelajaran'],
            ['KodeKategori' => 'KAT-07', 'NamaKategori' => 'Alat Musik',              'Deskripsi' => 'Instrumen dan perlengkapan musik sekolah'],
            ['KodeKategori' => 'KAT-08', 'NamaKategori' => 'Infrastruktur & Gedung',  'Deskripsi' => 'Bangunan, ruangan, dan fasilitas fisik sekolah'],
        ];

        $total = 0;
        foreach ($instansis as $instansi) {
            foreach ($templateKategori as $kat) {
                Kategori::create([
                    'KodeKategori' => $kat['KodeKategori'],
                    'NamaKategori' => $kat['NamaKategori'],
                    'Deskripsi'    => $kat['Deskripsi'],
                    'InstansiID'   => $instansi->InstansiID,
                ]);
                $total++;
            }
        }

        $this->command->info("✅ KategoriSeeder selesai: {$total} kategori dibuat ({$instansis->count()} instansi × " . count($templateKategori) . " kategori).");
    }
}