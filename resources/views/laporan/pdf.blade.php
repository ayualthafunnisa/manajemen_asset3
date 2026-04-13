{{-- resources/views/laporan/pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan {{ ucfirst($jenisLaporan) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }
        .header h1 {
            margin: 0;
            font-size: 18pt;
            color: #333;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 9pt;
        }
        .info {
            margin-bottom: 15px;
            padding: 10px;
            background: #f5f5f5;
            font-size: 9pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background: #f0f0f0;
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 9pt;
        }
        td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            font-size: 8pt;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8pt;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7pt;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN {{ strtoupper($jenisLaporan) }}</h1>
        <p>SMK Informatika Utama</p>
        <p>Dicetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
    </div>
    
    @if($startDate && $endDate)
    <div class="info">
        <strong>Periode:</strong> {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}
    </div>
    @endif
    
    <table>
        <thead>
            <tr>
                @if($jenisLaporan == 'asset')
                <th>No</th>
                <th>Kode Asset</th>
                <th>Nama Asset</th>
                <th>Kategori</th>
                <th>Lokasi</th>
                <th>Merk</th>
                <th>Serial Number</th>
                <th>Kondisi</th>
                <th>Status</th>
                <th>Tgl Dibuat</th>
                @elseif($jenisLaporan == 'kerusakan')
                <th>No</th>
                <th>Asset</th>
                <th>Kode Asset</th>
                <th>Deskripsi</th>
                <th>Tgl Laporan</th>
                <th>Status</th>
                <th>Lokasi</th>
                <th>Pelapor</th>
                @elseif($jenisLaporan == 'penghapusan')
                <th>No</th>
                <th>Asset</th>
                <th>Kode Asset</th>
                <th>Tgl Penghapusan</th>
                <th>Alasan</th>
                <th>Status</th>
                <th>Disetujui</th>
                @elseif($jenisLaporan == 'penyusutan')
                <th>No</th>
                <th>Asset</th>
                <th>Kode Asset</th>
                <th>Nilai Awal</th>
                <th>Nilai Akhir</th>
                <th>Penyusutan/Tahun</th>
                <th>Metode</th>
                <th>Tgl Mulai</th>
                <th>Masa Manfaat</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($data['items'] as $index => $item)
            <tr>
                @if($jenisLaporan == 'asset')
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->kode_asset }}</td>
                <td>{{ $item->nama_asset }}</td>
                <td>{{ $item->kategori->NamaKategori ?? '-' }}</td>
                <td>{{ $item->lokasi->NamaLokasi ?? '-' }}</td>
                <td>{{ $item->merk ?? '-' }}</td>
                <td>{{ $item->serial_number ?? '-' }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $item->kondisi)) }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $item->status_asset)) }}</td>
                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                @elseif($jenisLaporan == 'kerusakan')
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->asset->nama_asset ?? '-' }}</td>
                <td>{{ $item->asset->kode_asset ?? '-' }}</td>
                <td>{{ Str::limit($item->deskripsi_kerusakan, 60) }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d/m/Y') }}</td>
                <td>{{ ucfirst($item->status_perbaikan) }}</td>
                <td>{{ $item->lokasi->NamaLokasi ?? '-' }}</td>
                <td>{{ $item->user->name ?? '-' }}</td>
                @elseif($jenisLaporan == 'penghapusan')
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->asset->nama_asset ?? '-' }}</td>
                <td>{{ $item->asset->kode_asset ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_penghapusan)->format('d/m/Y') }}</td>
                <td>{{ Str::limit($item->alasan_penghapusan, 50) }}</td>
                <td>{{ ucfirst($item->status_penghapusan) }}</td>
                <td>{{ $item->approved_by ?? '-' }}</td>
                @elseif($jenisLaporan == 'penyusutan')
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->asset->nama_asset ?? '-' }}</td>
                <td>{{ $item->asset->kode_asset ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($item->nilai_awal, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item->nilai_akhir, 0, ',', '.') }}</td>
                <td class="text-center">{{ $item->penyusutan_per_tahun }}%</td>
                <td>{{ $item->metode_penyusutan }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }}</td>
                <td class="text-center">{{ $item->masa_manfaat }} thn</td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        Total Data: {{ $data['filtered'] }} dari {{ $data['total'] }}
    </div>
</body>
</html>