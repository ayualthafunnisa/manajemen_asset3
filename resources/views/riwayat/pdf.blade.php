<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Perbaikan {{ $perbaikan->kode_perbaikan }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1a1a2e; background: #fff; }

        .header { background: #1e40af; color: #fff; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 18px; font-weight: 700; letter-spacing: .5px; }
        .header .sub { font-size: 11px; opacity: .85; margin-top: 4px; }
        .kode-badge { background: rgba(255,255,255,.2); border-radius: 6px; padding: 8px 16px; text-align: center; }
        .kode-badge .label { font-size: 9px; text-transform: uppercase; letter-spacing: 1px; opacity: .8; }
        .kode-badge .kode { font-size: 16px; font-weight: 700; font-family: monospace; }

        .content { padding: 24px 30px; }

        .status-bar { display: flex; gap: 12px; margin-bottom: 20px; }
        .status-item { flex: 1; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; text-align: center; }
        .status-item .label { font-size: 9px; color: #64748b; text-transform: uppercase; letter-spacing: .8px; margin-bottom: 4px; }
        .status-item .value { font-size: 12px; font-weight: 700; color: #1e293b; }

        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 10px; font-weight: 700; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-danger  { background: #fee2e2; color: #991b1b; }
        .badge-warning { background: #fef9c3; color: #854d0e; }
        .badge-info    { background: #dbeafe; color: #1e40af; }

        .section { margin-bottom: 18px; }
        .section-title { font-size: 12px; font-weight: 700; color: #1e40af; border-bottom: 2px solid #bfdbfe; padding-bottom: 5px; margin-bottom: 10px; text-transform: uppercase; letter-spacing: .5px; }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; }
        .field-label { font-size: 9px; color: #64748b; text-transform: uppercase; letter-spacing: .6px; margin-bottom: 2px; }
        .field-value { font-size: 11px; font-weight: 600; color: #1e293b; }

        .box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; font-size: 11px; line-height: 1.6; color: #374151; }

        .foto-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 8px; }
        .foto-item img { width: 100%; height: 160px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0; }
        .foto-label { font-size: 9px; color: #64748b; text-transform: uppercase; letter-spacing: .6px; margin-bottom: 4px; }

        .alert-box { background: #fee2e2; border: 1px solid #fca5a5; border-radius: 6px; padding: 10px 12px; color: #991b1b; font-size: 11px; line-height: 1.5; }

        .footer { border-top: 2px solid #e2e8f0; padding: 16px 30px; display: flex; justify-content: space-between; align-items: flex-end; }
        .footer .ttd-box { text-align: center; border-top: 1px solid #374151; padding-top: 6px; margin-top: 60px; width: 180px; font-size: 10px; }
        .footer .meta { font-size: 9px; color: #94a3b8; }
    </style>
</head>
<body>

{{-- HEADER --}}
<div class="header">
    <div>
        <h1>Laporan Perbaikan Aset</h1>
        <div class="sub">{{ $perbaikan->kerusakan->instansi->nama_instansi ?? 'Sistem Manajemen Aset' }}</div>
    </div>
    <div class="kode-badge">
        <div class="label">Kode Perbaikan</div>
        <div class="kode">{{ $perbaikan->kode_perbaikan }}</div>
    </div>
</div>

<div class="content">

    {{-- Status Bar --}}
    <div class="status-bar">
        <div class="status-item">
            <div class="label">Status</div>
            <div class="value">
                <span class="badge badge-{{ $perbaikan->badge_status }}">{{ $perbaikan->label_status }}</span>
            </div>
        </div>
        <div class="status-item">
            <div class="label">Mulai Perbaikan</div>
            <div class="value">{{ $perbaikan->mulai_perbaikan ? $perbaikan->mulai_perbaikan->format('d M Y H:i') : '-' }}</div>
        </div>
        <div class="status-item">
            <div class="label">Selesai Perbaikan</div>
            <div class="value">{{ $perbaikan->selesai_perbaikan ? $perbaikan->selesai_perbaikan->format('d M Y H:i') : '-' }}</div>
        </div>
        <div class="status-item">
            <div class="label">Biaya Aktual</div>
            <div class="value">{{ $perbaikan->biaya_aktual ? 'Rp ' . number_format($perbaikan->biaya_aktual,0,',','.') : '-' }}</div>
        </div>
    </div>

    {{-- Info Aset --}}
    <div class="section">
        <div class="section-title">Informasi Aset & Kerusakan</div>
        <div class="grid-3">
            <div>
                <div class="field-label">Kode Laporan</div>
                <div class="field-value">{{ $perbaikan->kerusakan->kode_laporan ?? '-' }}</div>
            </div>
            <div>
                <div class="field-label">Nama Aset</div>
                <div class="field-value">{{ $perbaikan->kerusakan->asset->nama_asset ?? '-' }}</div>
            </div>
            <div>
                <div class="field-label">Kode Aset</div>
                <div class="field-value">{{ $perbaikan->kerusakan->asset->kode_asset ?? '-' }}</div>
            </div>
            <div>
                <div class="field-label">Kategori</div>
                <div class="field-value">{{ $perbaikan->kerusakan->asset->kategori->nama_kategori ?? '-' }}</div>
            </div>
            <div>
                <div class="field-label">Lokasi</div>
                <div class="field-value">{{ $perbaikan->kerusakan->lokasi->nama_lokasi ?? '-' }}</div>
            </div>
            <div>
                <div class="field-label">Jenis Kerusakan</div>
                <div class="field-value">{{ ucfirst($perbaikan->kerusakan->jenis_kerusakan ?? '-') }}</div>
            </div>
            <div>
                <div class="field-label">Dilaporkan Oleh</div>
                <div class="field-value">{{ $perbaikan->kerusakan->pelapor->name ?? '-' }}</div>
            </div>
            <div>
                <div class="field-label">Tanggal Laporan</div>
                <div class="field-value">{{ $perbaikan->kerusakan->tanggal_laporan?->format('d M Y') ?? '-' }}</div>
            </div>
            <div>
                <div class="field-label">Estimasi Biaya</div>
                <div class="field-value">
                    {{ $perbaikan->kerusakan->estimasi_biaya ? 'Rp ' . number_format($perbaikan->kerusakan->estimasi_biaya,0,',','.') : '-' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Tindakan Perbaikan --}}
    <div class="section">
        <div class="section-title">Tindakan Perbaikan</div>
        <div class="box" style="margin-bottom:8px">{{ $perbaikan->tindakan_perbaikan }}</div>

        @if($perbaikan->catatan_perbaikan)
        <div class="field-label" style="margin-bottom:4px">Catatan Tambahan</div>
        <div class="box">{{ $perbaikan->catatan_perbaikan }}</div>
        @endif

        @if($perbaikan->alasan_tidak_bisa)
        <div style="margin-top:8px">
            <div class="alert-box">⚠ <strong>Tidak bisa diperbaiki:</strong> {{ $perbaikan->alasan_tidak_bisa }}</div>
        </div>
        @endif
    </div>

    {{-- Komponen & Biaya --}}
    @if($perbaikan->komponen_diganti || $perbaikan->biaya_aktual)
    <div class="section">
        <div class="section-title">Komponen & Biaya</div>
        <div class="grid-2">
            @if($perbaikan->komponen_diganti)
            <div>
                <div class="field-label">Komponen Diganti</div>
                <div class="field-value">{{ $perbaikan->komponen_diganti }}</div>
            </div>
            @endif
            @if($perbaikan->biaya_aktual)
            <div>
                <div class="field-label">Biaya Aktual</div>
                <div class="field-value" style="color:#166534">Rp {{ number_format($perbaikan->biaya_aktual,0,',','.') }}</div>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Foto --}}
    @if($perbaikan->kerusakan->foto_kerusakan || $perbaikan->foto_sesudah)
    <div class="section">
        <div class="section-title">Dokumentasi Foto</div>
        <div class="foto-grid">
            @if($perbaikan->kerusakan->foto_kerusakan)
            <div>
                <div class="foto-label">Sebelum Perbaikan</div>
                <img src="{{ public_path('storage/' . $perbaikan->kerusakan->foto_kerusakan) }}" alt="Sebelum">
            </div>
            @endif
            @if($perbaikan->foto_sesudah)
            <div>
                <div class="foto-label">Sesudah Perbaikan</div>
                <img src="{{ public_path('storage/' . $perbaikan->foto_sesudah) }}" alt="Sesudah">
            </div>
            @endif
        </div>
    </div>
    @endif

</div>

{{-- FOOTER --}}
<div class="footer">
    <div class="meta">
        Dicetak: {{ now()->format('d F Y H:i') }} &nbsp;|&nbsp;
        Sistem Manajemen Aset
    </div>
    <div>
        <div class="ttd-box">
            {{ $perbaikan->teknisi->name ?? 'Teknisi' }}<br>
            <span style="font-size:9px;color:#64748b">Teknisi Pelaksana</span>
        </div>
    </div>
</div>

</body>
</html>