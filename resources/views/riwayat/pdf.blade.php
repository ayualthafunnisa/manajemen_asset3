<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Perbaikan | {{ $perbaikan->kode_perbaikan }}</title>
    <style>
        /* ===== PDF PAGE SETUP ===== */
        @page {
            size: A4 portrait;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        html, body {
            width: 210mm;
            min-height: 297mm;
            font-family: 'DejaVu Sans', 'Arial', 'Helvetica Neue', sans-serif;
            font-size: 12px;
            color: #1e2a3a;
            background: #ffffff;
        }

        /* ===== WRAPPER UTAMA ===== */
        .laporan {
            width: 100%;
            min-height: 297mm;
            background: #ffffff;
            display: flex;
            flex-direction: column;
        }

        /* ===== HEADER ===== */
        .header-bw {
            background: #1e2a3a;
            color: #ffffff;
            padding: 22px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 4px solid #000000;
            page-break-inside: avoid;
        }

        .judul-laporan h1 {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
            color: #ffffff;
        }

        .judul-laporan .sub-instansi {
            font-size: 10px;
            font-weight: 400;
            opacity: 0.75;
            letter-spacing: 0.3px;
        }

        .kode-badge-bw {
            background: rgba(255,255,255,0.1);
            padding: 10px 22px;
            border: 1px solid rgba(255,255,255,0.3);
            text-align: center;
            min-width: 160px;
        }

        .kode-badge-bw .label-kode {
            font-size: 9px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,0.7);
            margin-bottom: 4px;
        }

        .kode-badge-bw .kode-angka {
            font-size: 18px;
            font-weight: 800;
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
            color: #ffffff;
        }

        /* ===== KONTEN UTAMA ===== */
        .content-bw {
            padding: 24px 30px;
            flex: 1;
        }

        /* ===== STATUS GRID - 4 kolom sejajar ===== */
        .status-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            border: 1px solid #dde4ed;
        }

        .status-grid-row {
            display: table-row;
        }

        .status-card {
            display: table-cell;
            padding: 12px 16px;
            background: #f8fafc;
            border: 1px solid #dde4ed;
            vertical-align: top;
            width: 25%;
        }

        .status-label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #4b5563;
            margin-bottom: 6px;
        }

        .status-value {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
        }

        .badge-bw {
            display: inline-block;
            padding: 4px 12px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            background: #eef2f6;
            color: #1e293b;
            border: 1px solid #cbd5e1;
        }

        .badge-bw-success { background: #e6edf5; border-left: 3px solid #2c3e50; }
        .badge-bw-danger  { background: #f1f3f5; border-left: 3px solid #4b5563; }
        .badge-bw-warning { background: #f1f3f5; border-left: 3px solid #5b6e8c; }
        .badge-bw-info    { background: #eef2f8; border-left: 3px solid #2c3e50; }

        /* ===== SECTION TITLE ===== */
        .section-title-bw {
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 2px solid #cdd6e4;
            margin: 20px 0 14px 0;
            padding-bottom: 7px;
            page-break-after: avoid;
        }

        .section-title-bw h3 {
            font-size: 13px;
            font-weight: 700;
            color: #1e2a3a;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .section-icon-bw {
            font-size: 14px;
        }

        /* ===== INFO ASET - TABLE LAYOUT (aman di PDF) ===== */
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 5px 0;
            vertical-align: top;
            width: 50%;
        }

        .info-table td:first-child {
            padding-right: 15px;
        }

        .info-item-bw {
            border-bottom: 1px solid #edf2f7;
            padding-bottom: 7px;
            margin-bottom: 2px;
        }

        .info-label-bw {
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            color: #5a687c;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .info-value-bw {
            font-size: 11px;
            font-weight: 600;
            color: #1f2c40;
            background: #f8fbfd;
            padding: 3px 10px;
            display: inline-block;
            border-left: 2px solid #b0c0d4;
        }

        /* ===== BOX TEKS ===== */
        .box-teks {
            background: #fefefe;
            border: 1px solid #e2e8f0;
            padding: 12px 16px;
            font-size: 11px;
            line-height: 1.6;
            color: #1e2f44;
            page-break-inside: avoid;
        }

        .alert-bw {
            background: #f5f6f8;
            border-left: 4px solid #4b5563;
            padding: 10px 16px;
            margin-top: 10px;
            font-size: 11px;
            color: #2c3e50;
            page-break-inside: avoid;
        }

        /* ===== KOMPONEN BIAYA - TABLE ===== */
        .komponen-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .komponen-table td {
            padding: 5px 8px;
            vertical-align: top;
            width: 50%;
        }

        .komponen-card {
            background: #f8fbff;
            border: 1px solid #dce8f2;
            padding: 12px 16px;
            page-break-inside: avoid;
        }

        .label-kecil {
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #5b6e8c;
            margin-bottom: 5px;
        }

        .nilai-tebal {
            font-weight: 800;
            font-size: 13px;
            color: #111827;
        }

        /* ===== FOTO - SIDE BY SIDE TABLE ===== */
        .foto-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            page-break-inside: avoid;
        }

        .foto-table td {
            padding: 0 10px;
            vertical-align: top;
            width: 50%;
        }

        .foto-table td:first-child { padding-left: 0; }
        .foto-table td:last-child  { padding-right: 0; }

        .foto-card {
            border: 1px solid #dde5ef;
            background: #ffffff;
            page-break-inside: avoid;
        }

        .foto-judul {
            background: #f2f6fc;
            padding: 8px 14px;
            font-weight: 700;
            font-size: 10px;
            color: #1e2a3a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #dde5ef;
        }

        .foto-container {
            padding: 14px;
            text-align: center;
            background: #ffffff;
        }

        .foto-container img {
            max-width: 100%;
            max-height: 220px;
            width: auto;
            height: auto;
            object-fit: contain;
            border: 1px solid #eef2f8;
        }

        .foto-kosong {
            background: #f8fafc;
            border: 1px solid #dde5ef;
            padding: 20px;
            text-align: center;
            font-size: 11px;
            color: #6f7d95;
        }

        /* ===== CATATAN TAMBAHAN ===== */
        .catatan-wrapper {
            margin-top: 12px;
        }

        /* ===== FOOTER ===== */
        .footer-bw {
            border-top: 2px solid #cdd6e4;
            padding: 18px 30px 24px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            background: #ffffff;
            page-break-inside: avoid;
        }

        .meta-cetak {
            font-size: 8px;
            color: #6f7d95;
            line-height: 1.6;
        }

        .ttd-area {
            text-align: center;
            min-width: 180px;
        }

        .ttd-garis {
            border-top: 1px solid #8d9bb0;
            padding-top: 10px;
            margin-top: 50px;
            font-weight: 700;
            font-size: 11px;
            color: #1e2a3a;
        }

        .ttd-area span {
            font-size: 9px;
            font-weight: normal;
            color: #5d6e8a;
        }

        /* ===== PAGE BREAK CONTROL ===== */
        .no-break {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
<div class="laporan">

    <!-- ===== HEADER ===== -->
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

    <!-- ===== KONTEN ===== -->
    <div class="content-bw">

        <!-- STATUS GRID (4 kolom menggunakan table layout) -->
        <div class="no-break">
            <div class="status-grid">
                <div class="status-grid-row">
                    <div class="status-card">
                        <div class="status-label">STATUS</div>
                        <div class="status-value">
                            <span class="badge-bw badge-bw-{{ $perbaikan->badge_status }}">
                                {{ $perbaikan->label_status }}
                            </span>
                        </div>
                    </div>
                    <div class="status-card">
                        <div class="status-label">MULAI PERBAIKAN</div>
                        <div class="status-value">
                            {{ $perbaikan->mulai_perbaikan ? $perbaikan->mulai_perbaikan->format('d M Y H:i') : '—' }}
                        </div>
                    </div>
                    <div class="status-card">
                        <div class="status-label">SELESAI PERBAIKAN</div>
                        <div class="status-value">
                            {{ $perbaikan->selesai_perbaikan ? $perbaikan->selesai_perbaikan->format('d M Y H:i') : '—' }}
                        </div>
                    </div>
                    <div class="status-card">
                        <div class="status-label">BIAYA AKTUAL</div>
                        <div class="status-value">
                            @if($perbaikan->biaya_aktual)
                                Rp {{ number_format($perbaikan->biaya_aktual, 0, ',', '.') }}
                            @else
                                —
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 1: INFORMASI ASET & KERUSAKAN -->
        <div class="section-title-bw">
            <span class="section-icon-bw">&#128203;</span>
            <h3>Informasi Aset &amp; Kerusakan</h3>
        </div>
        <table class="info-table">
            <tr>
                <td>
                    <div class="info-item-bw">
                        <div class="info-label-bw">Kode Laporan</div>
                        <div class="info-value-bw">{{ $perbaikan->kerusakan->kode_laporan ?? '-' }}</div>
                    </div>
                </td>
                <td>
                    <div class="info-item-bw">
                        <div class="info-label-bw">Nama Aset</div>
                        <div class="info-value-bw">{{ $perbaikan->kerusakan->asset->nama_asset ?? '-' }}</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="info-item-bw">
                        <div class="info-label-bw">Kode Aset</div>
                        <div class="info-value-bw">{{ $perbaikan->kerusakan->asset->kode_asset ?? '-' }}</div>
                    </div>
                </td>
                <td>
                    <div class="info-item-bw">
                        <div class="info-label-bw">Kategori</div>
                        <div class="info-value-bw">{{ $perbaikan->kerusakan->asset->kategori->NamaKategori ?? '-' }}</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="info-item-bw">
                        <div class="info-label-bw">Lokasi</div>
                        <div class="info-value-bw">{{ $perbaikan->kerusakan->lokasi->NamaLokasi ?? '-' }}</div>
                    </div>
                </td>
                <td>
                    <div class="info-item-bw">
                        <div class="info-label-bw">Jenis Kerusakan</div>
                        <div class="info-value-bw">{{ ucfirst($perbaikan->kerusakan->jenis_kerusakan ?? '-') }}</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="info-item-bw">
                        <div class="info-label-bw">Dilaporkan Oleh</div>
                        <div class="info-value-bw">{{ $perbaikan->kerusakan->pelapor->name ?? '-' }}</div>
                    </div>
                </td>
                <td>
                    <div class="info-item-bw">
                        <div class="info-label-bw">Tanggal Laporan</div>
                        <div class="info-value-bw">{{ $perbaikan->kerusakan->tanggal_laporan?->format('d M Y') ?? '-' }}</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="info-item-bw">
                        <div class="info-label-bw">Estimasi Biaya Awal</div>
                        <div class="info-value-bw">
                            {{ $perbaikan->kerusakan->estimasi_biaya ? 'Rp ' . number_format($perbaikan->kerusakan->estimasi_biaya, 0, ',', '.') : '-' }}
                        </div>
                    </div>
                </td>
                <td></td>
            </tr>
        </table>

        <!-- SECTION 2: TINDAKAN PERBAIKAN -->
        <div class="section-title-bw">
            <span class="section-icon-bw">&#9881;</span>
            <h3>Tindakan Perbaikan</h3>
        </div>
        <div class="box-teks">
            {{ $perbaikan->tindakan_perbaikan ?: 'Tidak ada tindakan perbaikan yang tercatat.' }}
        </div>

        @if($perbaikan->catatan_perbaikan)
        <div class="catatan-wrapper">
            <div class="info-label-bw" style="margin-bottom: 5px;">CATATAN TAMBAHAN</div>
            <div class="box-teks" style="background:#fcfcfd;">{{ $perbaikan->catatan_perbaikan }}</div>
        </div>
        @endif

        @if($perbaikan->alasan_tidak_bisa)
        <div class="alert-bw">
            <strong>&#9940; TIDAK DAPAT DIPERBAIKI:</strong> {{ $perbaikan->alasan_tidak_bisa }}
        </div>
        @endif

        <!-- SECTION 3: KOMPONEN & BIAYA (jika ada) -->
        @if($perbaikan->komponen_diganti || $perbaikan->biaya_aktual)
        <div class="section-title-bw">
            <span class="section-icon-bw">&#128297;</span>
            <h3>Komponen &amp; Realisasi Biaya</h3>
        </div>
        <table class="komponen-table">
            <tr>
                @if($perbaikan->komponen_diganti)
                <td>
                    <div class="komponen-card">
                        <div class="label-kecil">KOMPONEN YANG DIGANTI</div>
                        <div class="nilai-tebal">{{ $perbaikan->komponen_diganti }}</div>
                    </div>
                </td>
                @endif
                @if($perbaikan->biaya_aktual)
                <td>
                    <div class="komponen-card">
                        <div class="label-kecil">BIAYA AKTUAL (FINAL)</div>
                        <div class="nilai-tebal">Rp {{ number_format($perbaikan->biaya_aktual, 0, ',', '.') }}</div>
                    </div>
                </td>
                @endif
            </tr>
        </table>
        @endif

        <!-- SECTION 4: DOKUMENTASI FOTO -->
        <div class="section-title-bw">
            <span class="section-icon-bw">&#128247;</span>
            <h3>Dokumentasi Perbaikan</h3>
        </div>

        @if(($perbaikan->kerusakan->foto_kerusakan ?? null) || ($perbaikan->foto_sesudah ?? null))
        <table class="foto-table">
            <tr>
                @if($perbaikan->kerusakan && $perbaikan->kerusakan->foto_kerusakan)
                <td>
                    <div class="foto-card">
                        <div class="foto-judul">&#128248; Sebelum Perbaikan</div>
                        <div class="foto-container">
                            <img src="{{ public_path('storage/' . $perbaikan->kerusakan->foto_kerusakan) }}" alt="Foto Sebelum">
                        </div>
                    </div>
                </td>
                @endif
                @if($perbaikan->foto_sesudah)
                <td>
                    <div class="foto-card">
                        <div class="foto-judul">&#128248; Setelah Perbaikan</div>
                        <div class="foto-container">
                            <img src="{{ public_path('storage/' . $perbaikan->foto_sesudah) }}" alt="Foto Sesudah">
                        </div>
                    </div>
                </td>
                @endif
            </tr>
        </table>
        @else
        <div class="foto-kosong">
            Tidak ada lampiran foto untuk perbaikan ini.
        </div>
        @endif

    </div>
    <!-- /content-bw -->

    <!-- ===== FOOTER ===== -->
    <div class="footer-bw">
        <div class="meta-cetak">
            Dicetak: {{ now()->format('d F Y, H:i') }}<br>
            Sistem Informasi Manajemen Perbaikan Aset
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