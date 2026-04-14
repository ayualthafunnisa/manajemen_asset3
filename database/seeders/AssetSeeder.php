<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asset;
use App\Models\Instansi;
use App\Models\Kategori;
use App\Models\LokasiAsset;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        $instansis = Instansi::all();
        $total = 0;

        foreach ($instansis as $instansi) {
            $kategoris = Kategori::withoutGlobalScopes()
                ->where('InstansiID', $instansi->InstansiID)
                ->get()
                ->keyBy('NamaKategori');

            $lokasis = LokasiAsset::withoutGlobalScopes()
                ->where('InstansiID', $instansi->InstansiID)
                ->get()
                ->keyBy('NamaLokasi');

            $assets = $this->templateAssets($instansi->InstansiID, $kategoris, $lokasis);

            foreach ($assets as $asset) {
                Asset::withoutGlobalScopes()->create($asset);
                $total++;
            }
        }

        $this->command->info("✅ AssetSeeder selesai: {$total} asset dibuat.");
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Nilai enum sesuai migration:
    //   sumber_perolehan : pembelian | hibah | bantuan_pemerintah | produksi_sendiri | sewa
    //   kondisi          : baik | rusak_ringan | rusak_berat | tidak_berfungsi | hilang
    //   status_asset     : aktif | non_aktif | dipinjam | diperbaiki | tersimpan | dihapus
    // ─────────────────────────────────────────────────────────────────────────
    private function templateAssets(int $instansiID, $kategoris, $lokasis): array
    {
        $elID  = $kategoris['Peralatan Elektronik']?->KategoriID;
        $mbID  = $kategoris['Mebel & Perabot']?->KategoriID;
        $olID  = $kategoris['Peralatan Olahraga']?->KategoriID;
        $labID = $kategoris['Peralatan Laboratorium']?->KategoriID;
        $bkID  = $kategoris['Buku & Pustaka']?->KategoriID;

        $kepalasID = $lokasis['Ruang Kepala Sekolah']?->LokasiID;
        $tuID      = $lokasis['Ruang Tata Usaha']?->LokasiID;
        $kelasID   = $lokasis['Ruang Kelas']?->LokasiID;
        $labKomID  = $lokasis['Laboratorium Komputer']?->LokasiID;
        $labIpaID  = $lokasis['Laboratorium IPA']?->LokasiID;
        $perpusID  = $lokasis['Perpustakaan']?->LokasiID;
        $lapID     = $lokasis['Lapangan Olahraga']?->LokasiID;

        return [
            // ── Elektronik ────────────────────────────────────────────────
            [
                'InstansiID'        => $instansiID,
                'KategoriID'        => $elID,
                'LokasiID'          => $labKomID,
                'kode_asset'        => 'AST-' . $instansiID . '-0001',
                'nama_asset'        => 'Laptop Lenovo ThinkPad E15',
                'merk'              => 'Lenovo',
                'serial_number'     => 'SN-LNV-' . $instansiID . '-' . rand(10000, 99999),
                'sumber_perolehan'  => 'pembelian',
                'tanggal_perolehan' => '2022-03-15',
                'nilai_perolehan'   => 12000000,
                'nilai_residu'      => 1200000,
                'umur_ekonomis'     => 5,
                'kondisi'           => 'baik',
                'status_asset'      => 'aktif',
                'jumlah'            => 20,
                'satuan'            => 'unit',
                'vendor'            => 'PT. Lenovo Indonesia',
                'gambar_asset'      => null,
                'dokumen_pendukung' => null,
                'keterangan'        => 'Laptop untuk lab komputer siswa',
            ],
            [
                'InstansiID'        => $instansiID,
                'KategoriID'        => $elID,
                'LokasiID'          => $kelasID,
                'kode_asset'        => 'AST-' . $instansiID . '-0002',
                'nama_asset'        => 'Proyektor Epson EB-X51',
                'merk'              => 'Epson',
                'serial_number'     => 'SN-EPS-' . $instansiID . '-' . rand(10000, 99999),
                'sumber_perolehan'  => 'pembelian',
                'tanggal_perolehan' => '2021-07-20',
                'nilai_perolehan'   => 7500000,
                'nilai_residu'      => 750000,
                'umur_ekonomis'     => 5,
                'kondisi'           => 'baik',
                'status_asset'      => 'aktif',
                'jumlah'            => 10,
                'satuan'            => 'unit',
                'vendor'            => 'CV. Epson Distributor',
                'gambar_asset'      => null,
                'dokumen_pendukung' => null,
                'keterangan'        => 'Proyektor untuk kegiatan belajar mengajar di kelas',
            ],
            [
                'InstansiID'        => $instansiID,
                'KategoriID'        => $elID,
                'LokasiID'          => $tuID,
                'kode_asset'        => 'AST-' . $instansiID . '-0003',
                'nama_asset'        => 'Printer Canon iP2870',
                'merk'              => 'Canon',
                'serial_number'     => 'SN-CAN-' . $instansiID . '-' . rand(10000, 99999),
                'sumber_perolehan'  => 'pembelian',
                'tanggal_perolehan' => '2023-01-10',
                'nilai_perolehan'   => 850000,
                'nilai_residu'      => 85000,
                'umur_ekonomis'     => 4,
                'kondisi'           => 'baik',
                'status_asset'      => 'aktif',
                'jumlah'            => 3,
                'satuan'            => 'unit',
                'vendor'            => 'Toko Komputer Sejahtera',
                'gambar_asset'      => null,
                'dokumen_pendukung' => null,
                'keterangan'        => 'Printer untuk keperluan administrasi TU',
            ],
            [
                'InstansiID'        => $instansiID,
                'KategoriID'        => $elID,
                'LokasiID'          => $kepalasID,
                'kode_asset'        => 'AST-' . $instansiID . '-0004',
                'nama_asset'        => 'Laptop ASUS VivoBook 14',
                'merk'              => 'ASUS',
                'serial_number'     => 'SN-ASUS-' . $instansiID . '-' . rand(10000, 99999),
                'sumber_perolehan'  => 'pembelian',
                'tanggal_perolehan' => '2023-06-01',
                'nilai_perolehan'   => 9500000,
                'nilai_residu'      => 950000,
                'umur_ekonomis'     => 5,
                'kondisi'           => 'baik',
                'status_asset'      => 'aktif',
                'jumlah'            => 1,
                'satuan'            => 'unit',
                'vendor'            => 'PT. Datascrip',
                'gambar_asset'      => null,
                'dokumen_pendukung' => null,
                'keterangan'        => 'Laptop kepala sekolah',
            ],

            // ── Mebel & Perabot ───────────────────────────────────────────
            [
                'InstansiID'        => $instansiID,
                'KategoriID'        => $mbID,
                'LokasiID'          => $kelasID,
                'kode_asset'        => 'AST-' . $instansiID . '-0005',
                'nama_asset'        => 'Meja Belajar Siswa',
                'merk'              => 'Olympic',
                'serial_number'     => null,
                'sumber_perolehan'  => 'pembelian',
                'tanggal_perolehan' => '2019-08-01',
                'nilai_perolehan'   => 350000,
                'nilai_residu'      => 35000,
                'umur_ekonomis'     => 8,
                'kondisi'           => 'baik',
                'status_asset'      => 'aktif',
                'jumlah'            => 150,
                'satuan'            => 'unit',
                'vendor'            => 'UD. Mebel Jaya',
                'gambar_asset'      => null,
                'dokumen_pendukung' => null,
                'keterangan'        => 'Meja belajar siswa untuk ruang kelas',
            ],
            [
                'InstansiID'        => $instansiID,
                'KategoriID'        => $mbID,
                'LokasiID'          => $kelasID,
                'kode_asset'        => 'AST-' . $instansiID . '-0006',
                'nama_asset'        => 'Kursi Siswa Plastik',
                'merk'              => 'Chitose',
                'serial_number'     => null,
                'sumber_perolehan'  => 'pembelian',
                'tanggal_perolehan' => '2019-08-01',
                'nilai_perolehan'   => 150000,
                'nilai_residu'      => 15000,
                'umur_ekonomis'     => 8,
                'kondisi'           => 'baik',
                'status_asset'      => 'aktif',
                'jumlah'            => 150,
                'satuan'            => 'unit',
                'vendor'            => 'UD. Mebel Jaya',
                'gambar_asset'      => null,
                'dokumen_pendukung' => null,
                'keterangan'        => 'Kursi siswa untuk ruang kelas',
            ],
            [
                'InstansiID'        => $instansiID,
                'KategoriID'        => $mbID,
                'LokasiID'          => $tuID,
                'kode_asset'        => 'AST-' . $instansiID . '-0007',
                'nama_asset'        => 'Lemari Arsip Besi',
                'merk'              => 'Brother',
                'serial_number'     => null,
                'sumber_perolehan'  => 'pembelian',
                'tanggal_perolehan' => '2020-05-15',
                'nilai_perolehan'   => 2500000,
                'nilai_residu'      => 250000,
                'umur_ekonomis'     => 10,
                'kondisi'           => 'baik',
                'status_asset'      => 'aktif',
                'jumlah'            => 4,
                'satuan'            => 'unit',
                'vendor'            => 'Toko Perlengkapan Kantor Maju',
                'gambar_asset'      => null,
                'dokumen_pendukung' => null,
                'keterangan'        => 'Lemari arsip untuk penyimpanan dokumen TU',
            ],

            // ── Peralatan Laboratorium ────────────────────────────────────
            [
                'InstansiID'        => $instansiID,
                'KategoriID'        => $labID,
                'LokasiID'          => $labIpaID,
                'kode_asset'        => 'AST-' . $instansiID . '-0008',
                'nama_asset'        => 'Mikroskop Biologi',
                'merk'              => 'Olympus',
                'serial_number'     => 'SN-MKR-' . $instansiID . '-' . rand(10000, 99999),
                'sumber_perolehan'  => 'bantuan_pemerintah',
                'tanggal_perolehan' => '2018-09-01',
                'nilai_perolehan'   => 4500000,
                'nilai_residu'      => 450000,
                'umur_ekonomis'     => 10,
                'kondisi'           => 'baik',
                'status_asset'      => 'aktif',
                'jumlah'            => 10,
                'satuan'            => 'unit',
                'vendor'            => 'PT. Alat Lab Indonesia',
                'gambar_asset'      => null,
                'dokumen_pendukung' => null,
                'keterangan'        => 'Mikroskop untuk praktikum biologi',
            ],
            [
                'InstansiID'        => $instansiID,
                'KategoriID'        => $labID,
                'LokasiID'          => $labIpaID,
                'kode_asset'        => 'AST-' . $instansiID . '-0009',
                'nama_asset'        => 'Set Peralatan Kimia Dasar',
                'merk'              => 'Pyrex',
                'serial_number'     => null,
                'sumber_perolehan'  => 'pembelian',
                'tanggal_perolehan' => '2021-01-20',
                'nilai_perolehan'   => 3000000,
                'nilai_residu'      => 300000,
                'umur_ekonomis'     => 5,
                'kondisi'           => 'baik',
                'status_asset'      => 'aktif',
                'jumlah'            => 5,
                'satuan'            => 'unit',
                'vendor'            => 'UD. Kimia Sains',
                'gambar_asset'      => null,
                'dokumen_pendukung' => null,
                'keterangan'        => 'Glassware dan set peralatan kimia dasar',
            ],
            [
                'InstansiID'        => $instansiID,
                'KategoriID'        => $labID,
                'LokasiID'          => $labIpaID,
                'kode_asset'        => 'AST-' . $instansiID . '-0010',
                'nama_asset'        => 'Teleskop Astronomi',
                'merk'              => 'Celestron',
                'serial_number'     => 'SN-TEL-' . $instansiID . '-' . rand(10000, 99999),
                'sumber_perolehan'  => 'hibah',
                'tanggal_perolehan' => '2020-11-10',
                'nilai_perolehan'   => 8000000,
                'nilai_residu'      => 800000,
                'umur_ekonomis'     => 10,
                'kondisi'           => 'rusak_ringan',
                'status_asset'      => 'diperbaiki',
                'jumlah'            => 1,
                'satuan'            => 'unit',
                'vendor'            => 'PT. Optika Sains',
                'gambar_asset'      => null,
                'dokumen_pendukung' => null,
                'keterangan'        => 'Teleskop hibah dari dinas pendidikan, sedang diperbaiki',
            ],

            // ── Peralatan Olahraga ────────────────────────────────────────
            [
                'InstansiID'        => $instansiID,
                'KategoriID'        => $olID,
                'LokasiID'          => $lapID,
                'kode_asset'        => 'AST-' . $instansiID . '-0011',
                'nama_asset'        => 'Bola Basket',
                'merk'              => 'Spalding',
                'serial_number'     => null,
                'sumber_perolehan'  => 'pembelian',
                'tanggal_perolehan' => '2022-08-10',
                'nilai_perolehan'   => 350000,
                'nilai_residu'      => 0,
                'umur_ekonomis'     => 3,
                'kondisi'           => 'baik',
                'status_asset'      => 'aktif',
                'jumlah'            => 8,
                'satuan'            => 'unit',
                'vendor'            => 'Toko Olahraga Champion',
                'gambar_asset'      => null,
                'dokumen_pendukung' => null,
                'keterangan'        => 'Bola basket untuk ekstrakurikuler',
            ],
            [
                'InstansiID'        => $instansiID,
                'KategoriID'        => $olID,
                'LokasiID'          => $lapID,
                'kode_asset'        => 'AST-' . $instansiID . '-0012',
                'nama_asset'        => 'Net Bola Voli',
                'merk'              => 'Mikasa',
                'serial_number'     => null,
                'sumber_perolehan'  => 'pembelian',
                'tanggal_perolehan' => '2021-03-05',
                'nilai_perolehan'   => 500000,
                'nilai_residu'      => 0,
                'umur_ekonomis'     => 4,
                'kondisi'           => 'baik',
                'status_asset'      => 'aktif',
                'jumlah'            => 2,
                'satuan'            => 'unit',
                'vendor'            => 'Toko Olahraga Champion',
                'gambar_asset'      => null,
                'dokumen_pendukung' => null,
                'keterangan'        => 'Net dan tiang voli',
            ],

            // ── Buku & Pustaka ────────────────────────────────────────────
            [
                'InstansiID'        => $instansiID,
                'KategoriID'        => $bkID,
                'LokasiID'          => $perpusID,
                'kode_asset'        => 'AST-' . $instansiID . '-0013',
                'nama_asset'        => 'Buku Teks Matematika Kelas X',
                'merk'              => null,
                'serial_number'     => null,
                'sumber_perolehan'  => 'bantuan_pemerintah',
                'tanggal_perolehan' => '2023-07-15',
                'nilai_perolehan'   => 75000,
                'nilai_residu'      => 0,
                'umur_ekonomis'     => 5,
                'kondisi'           => 'baik',
                'status_asset'      => 'aktif',
                'jumlah'            => 100,
                'satuan'            => 'unit',
                'vendor'            => 'Penerbit Erlangga',
                'gambar_asset'      => null,
                'dokumen_pendukung' => null,
                'keterangan'        => 'Buku teks pelajaran Matematika kelas X kurikulum Merdeka',
            ],
        ];
    }
}