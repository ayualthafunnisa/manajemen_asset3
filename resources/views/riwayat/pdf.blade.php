<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Perbaikan | {{ $perbaikan->kode_perbaikan }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* GLOBAL - MONOCHROME PROFESSIONAL */
        body {
            background: #e8eef2;
            font-family: 'Inter', 'Segoe UI', 'Roboto', 'Helvetica Neue', sans-serif;
            padding: 40px 24px;
            color: #1e2a3a;
        }

        /* container laporan premium B/W */
        .laporan {
            max-width: 1200px;
            margin: 0 auto;
            background: #ffffff;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.08);
            border-radius: 0px;
        }

        /* HEADER hitam putih tegas */
        .header-bw {
            background: #1e2a3a;
            color: white;
            padding: 28px 36px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            border-bottom: 4px solid #000000;
        }
        .judul-laporan h1 {
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.3px;
            margin-bottom: 6px;
            color: #ffffff;
        }
        .judul-laporan .sub-instansi {
            font-size: 12px;
            font-weight: 400;
            opacity: 0.8;
            letter-spacing: 0.2px;
        }
        .kode-badge-bw {
            background: rgba(255,255,255,0.08);
            padding: 10px 24px;
            border: 1px solid rgba(255,255,255,0.25);
            text-align: center;
        }
        .kode-badge-bw .label-kode {
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 500;
            letter-spacing: 1.5px;
        }
        .kode-badge-bw .kode-angka {
            font-size: 20px;
            font-weight: 800;
            font-family: 'Courier New', monospace;
            letter-spacing: 0.5px;
        }

        /* KONTEN UTAMA */
        .content-bw {
            padding: 32px 36px;
        }

        /* STATUS BAR - abu-abu profesional */
        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 36px;
        }
        .status-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 16px 14px;
            transition: all 0.1s;
        }
        .status-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #4b5563;
            margin-bottom: 8px;
        }
        .status-value {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }
        .badge-bw {
            display: inline-block;
            padding: 5px 14px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            background: #eef2f6;
            color: #1e293b;
            border: 1px solid #cbd5e1;
        }
        .badge-bw-success { background: #e9ecef; border-left: 3px solid #2c3e50; }
        .badge-bw-danger { background: #f1f3f5; border-left: 3px solid #4b5563; }
        .badge-bw-warning { background: #f1f3f5; border-left: 3px solid #5b6e8c; }
        .badge-bw-info { background: #eef2f8; border-left: 3px solid #2c3e50; }

        /* SECTION HEADER MONO */
        .section-title-bw {
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 2px solid #d1d9e8;
            margin: 28px 0 18px 0;
            padding-bottom: 8px;
        }
        .section-title-bw h3 {
            font-size: 17px;
            font-weight: 700;
            color: #1e2a3a;
            letter-spacing: -0.2px;
        }
        .section-icon-bw {
            font-size: 20px;
            font-weight: 400;
        }

        /* GRID INFORMASI ASET - RAPI */
        .info-dua-kolom {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 18px 30px;
            background: #ffffff;
        }
        .info-item-bw {
            border-bottom: 1px solid #edf2f7;
            padding-bottom: 8px;
        }
        .info-label-bw {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            color: #5a687c;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .info-value-bw {
            font-size: 13px;
            font-weight: 600;
            color: #1f2c40;
            background: #f9fbfd;
            padding: 4px 10px;
            display: inline-block;
            border-radius: 0px;
            border-left: 2px solid #bac8dc;
        }

        /* BOX DESKRIPSI */
        .box-teks {
            background: #fefefe;
            border: 1px solid #e2e8f0;
            padding: 16px 20px;
            font-size: 13px;
            line-height: 1.55;
            color: #1e2f44;
        }
        .alert-bw {
            background: #f7f7f9;
            border-left: 4px solid #4b5563;
            padding: 12px 18px;
            margin-top: 14px;
            font-size: 12px;
            color: #2c3e50;
        }

        /* KOMPONEN BIAYA */
        .komponen-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin: 8px 0 8px;
        }
        .komponen-card {
            flex: 1;
            background: #fafcff;
            border: 1px solid #e2edf5;
            padding: 14px 18px;
        }
        .label-kecil {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #5b6e8c;
            margin-bottom: 6px;
        }
        .nilai-tebal {
            font-weight: 800;
            font-size: 14px;
            color: #111827;
        }

        /* FOTO SECTION - KONTROL LEBAR, HITAM PUTIH PROFESIONAL */
        .foto-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-top: 12px;
        }
        .foto-card {
            flex: 1;
            min-width: 240px;
            background: #ffffff;
            border: 1px solid #e4e9f0;
            box-shadow: none;
        }
        .foto-judul {
            background: #f4f7fc;
            padding: 10px 16px;
            font-weight: 700;
            font-size: 12px;
            color: #1e2a3a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #dee4ed;
        }
        .foto-container {
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #ffffff;
        }
        .foto-container img {
            max-width: 100%;
            max-height: 260px;
            width: auto;
            height: auto;
            object-fit: contain;
            filter: grayscale(0%);
            border: 1px solid #eef2f8;
        }

        /* FOOTER TTD profesional */
        .footer-bw {
            border-top: 2px solid #dce3ec;
            padding: 24px 36px 32px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: flex-end;
            background: #ffffff;
        }
        .meta-cetak {
            font-size: 9px;
            color: #6f7d95;
        }
        .ttd-area {
            text-align: center;
            min-width: 200px;
        }
        .ttd-garis {
            border-top: 1px solid #8d9bb0;
            padding-top: 12px;
            margin-top: 32px;
            font-weight: 700;
            font-size: 12px;
            color: #1e2a3a;
        }
        .ttd-area span {
            font-size: 9px;
            font-weight: normal;
            color: #5d6e8a;
        }

        /* TIDAK ADA WARNA CERAH, SEMUA SKALA ABU/ PUTIH/HITAM */
        @media (max-width: 700px) {
            .content-bw {
                padding: 20px;
            }
            .header-bw {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
<div class="laporan">
    <!-- HEADER HITAM PUTIH ELEGAN -->
    <div class="header-bw">
        <div class="judul-laporan">
            <h1>LAPORAN PERBAIKAN ASET</h1>
            <div class="sub-instansi">{{ $perbaikan->kerusakan->instansi->nama_instansi ?? 'DINAS / INSTANSI MANAJEMEN ASET' }}</div>
        </div>
        <div class="kode-badge-bw">
            <div class="label-kode">KODE PERBAIKAN</div>
            <div class="kode-angka">{{ $perbaikan->kode_perbaikan }}</div>
        </div>
    </div>

    <div class="content-bw">
        <!-- STATUS BAR MONOCHROME -->
        <div class="status-grid">
            <div class="status-card">
                <div class="status-label">STATUS</div>
                <div class="status-value">
                    <span class="badge-bw badge-bw-{{ $perbaikan->badge_status }}">{{ $perbaikan->label_status }}</span>
                </div>
            </div>
            <div class="status-card">
                <div class="status-label">MULAI PERBAIKAN</div>
                <div class="status-value">{{ $perbaikan->mulai_perbaikan ? $perbaikan->mulai_perbaikan->format('d M Y H:i') : '—' }}</div>
            </div>
            <div class="status-card">
                <div class="status-label">SELESAI PERBAIKAN</div>
                <div class="status-value">{{ $perbaikan->selesai_perbaikan ? $perbaikan->selesai_perbaikan->format('d M Y H:i') : '—' }}</div>
            </div>
            <div class="status-card">
                <div class="status-label">BIAYA AKTUAL</div>
                <div class="status-value">
                    @if($perbaikan->biaya_aktual)
                        <span style="font-weight:800;">Rp {{ number_format($perbaikan->biaya_aktual,0,',','.') }}</span>
                    @else
                        —
                    @endif
                </div>
            </div>
        </div>

        <!-- SECTION 1: INFORMASI ASET & KERUSAKAN -->
        <div class="section-title-bw">
            <span class="section-icon-bw">📋</span>
            <h3>INFORMASI ASET & KERUSAKAN</h3>
        </div>
        <div class="info-dua-kolom">
            <div class="info-item-bw"><div class="info-label-bw">Kode Laporan</div><div class="info-value-bw">{{ $perbaikan->kerusakan->kode_laporan ?? '-' }}</div></div>
            <div class="info-item-bw"><div class="info-label-bw">Nama Aset</div><div class="info-value-bw">{{ $perbaikan->kerusakan->asset->nama_asset ?? '-' }}</div></div>
            <div class="info-item-bw"><div class="info-label-bw">Kode Aset</div><div class="info-value-bw">{{ $perbaikan->kerusakan->asset->kode_asset ?? '-' }}</div></div>
            <div class="info-item-bw"><div class="info-label-bw">Kategori</div><div class="info-value-bw">{{ $perbaikan->kerusakan->asset->kategori->nama_kategori ?? '-' }}</div></div>
            <div class="info-item-bw"><div class="info-label-bw">Lokasi</div><div class="info-value-bw">{{ $perbaikan->kerusakan->lokasi->nama_lokasi ?? '-' }}</div></div>
            <div class="info-item-bw"><div class="info-label-bw">Jenis Kerusakan</div><div class="info-value-bw">{{ ucfirst($perbaikan->kerusakan->jenis_kerusakan ?? '-') }}</div></div>
            <div class="info-item-bw"><div class="info-label-bw">Dilaporkan Oleh</div><div class="info-value-bw">{{ $perbaikan->kerusakan->pelapor->name ?? '-' }}</div></div>
            <div class="info-item-bw"><div class="info-label-bw">Tanggal Laporan</div><div class="info-value-bw">{{ $perbaikan->kerusakan->tanggal_laporan?->format('d M Y') ?? '-' }}</div></div>
            <div class="info-item-bw"><div class="info-label-bw">Estimasi Biaya Awal</div><div class="info-value-bw">{{ $perbaikan->kerusakan->estimasi_biaya ? 'Rp ' . number_format($perbaikan->kerusakan->estimasi_biaya,0,',','.') : '-' }}</div></div>
        </div>

        <!-- TINDAKAN PERBAIKAN -->
        <div class="section-title-bw">
            <span class="section-icon-bw">⚙️</span>
            <h3>TINDAKAN PERBAIKAN</h3>
        </div>
        <div class="box-teks">
            {{ $perbaikan->tindakan_perbaikan ?: 'Tidak ada tindakan perbaikan yang tercatat.' }}
        </div>
        @if($perbaikan->catatan_perbaikan)
        <div style="margin-top: 14px;">
            <div class="info-label-bw" style="margin-bottom: 5px;">CATATAN TAMBAHAN</div>
            <div class="box-teks" style="background:#fcfcfd;">{{ $perbaikan->catatan_perbaikan }}</div>
        </div>
        @endif
        @if($perbaikan->alasan_tidak_bisa)
        <div class="alert-bw">
            <strong>⛔ TIDAK DAPAT DIPERBAIKI:</strong> {{ $perbaikan->alasan_tidak_bisa }}
        </div>
        @endif

        <!-- KOMPONEN & BIAYA (jika ada) -->
        @if($perbaikan->komponen_diganti || $perbaikan->biaya_aktual)
        <div class="section-title-bw">
            <span class="section-icon-bw">🔩</span>
            <h3>KOMPONEN & REALISASI BIAYA</h3>
        </div>
        <div class="komponen-wrapper">
            @if($perbaikan->komponen_diganti)
            <div class="komponen-card">
                <div class="label-kecil">KOMPONEN YANG DIGANTI</div>
                <div class="nilai-tebal">{{ $perbaikan->komponen_diganti }}</div>
            </div>
            @endif
            @if($perbaikan->biaya_aktual)
            <div class="komponen-card">
                <div class="label-kecil">BIAYA AKTUAL (FINAL)</div>
                <div class="nilai-tebal" style="color:#1e2a3a;">Rp {{ number_format($perbaikan->biaya_aktual,0,',','.') }}</div>
            </div>
            @endif
        </div>
        @endif

        <!-- DOKUMENTASI FOTO : TIDAK LEBAR, TATA LETAK PROFESIONAL, HITAM PUTIH -->
        @if(($perbaikan->kerusakan->foto_kerusakan ?? null) || ($perbaikan->foto_sesudah ?? null))
        <div class="section-title-bw">
            <span class="section-icon-bw">📷</span>
            <h3>DOKUMENTASI PERBAIKAN</h3>
        </div>
        <div class="foto-gallery">
            @if($perbaikan->kerusakan && $perbaikan->kerusakan->foto_kerusakan)
            <div class="foto-card">
                <div class="foto-judul">SEBELUM PERBAIKAN</div>
                <div class="foto-container">
                    <img src="{{ public_path('storage/' . $perbaikan->kerusakan->foto_kerusakan) }}" alt="Foto Sebelum"
                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22200%22%20height%3D%22150%22%20viewBox%3D%220%200%20200%20150%22%3E%3Crect%20width%3D%22200%22%20height%3D%22150%22%20fill%3D%22%23f4f6fa%22%2F%3E%3Ctext%20x%3D%2250%25%22%20y%3D%2250%25%22%20dominant-baseline%3D%22middle%22%20text-anchor%3D%22middle%22%20fill%3D%22%236c7a91%22%3EGambar tidak tersedia%3C%2Ftext%3E%3C%2Fsvg%3E';">
                </div>
            </div>
            @endif
            @if($perbaikan->foto_sesudah)
            <div class="foto-card">
                <div class="foto-judul">SETELAH PERBAIKAN</div>
                <div class="foto-container">
                    <img src="{{ public_path('storage/' . $perbaikan->foto_sesudah) }}" alt="Foto Sesudah"
                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22200%22%20height%3D%22150%22%20viewBox%3D%220%200%20200%20150%22%3E%3Crect%20width%3D%22200%22%20height%3D%22150%22%20fill%3D%22%23f4f6fa%22%2F%3E%3Ctext%20x%3D%2250%25%22%20y%3D%2250%25%22%20dominant-baseline%3D%22middle%22%20text-anchor%3D%22middle%22%20fill%3D%22%236c7a91%22%3EGambar tidak tersedia%3C%2Ftext%3E%3C%2Fsvg%3E';">
                </div>
            </div>
            @endif
        </div>
        @else
        <div class="section-title-bw">
            <span class="section-icon-bw">🖼️</span>
            <h3>DOKUMENTASI</h3>
        </div>
        <div class="box-teks" style="text-align: center; color: #6f7d95;">
            Tidak ada lampiran foto untuk perbaikan ini.
        </div>
        @endif
    </div>

    <!-- FOOTER TTD & METADATA -->
    <div class="footer-bw">
        <div class="meta-cetak">
            Dicetak: {{ now()->format('d F Y H:i') }} &nbsp;|&nbsp; Sistem Informasi Perbaikan Aset
        </div>
        <div class="ttd-area">
            <div class="ttd-garis">
                {{ $perbaikan->teknisi->name ?? 'Teknisi Pelaksana' }}<br>
                <span>Teknisi / Penanggung Jawab</span>
            </div>
        </div>
    </div>
</div>
</body>
</html>