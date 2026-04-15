<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Penyusutan Aset</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            padding: 20px;
            font-size: 12px;
        }
        
        .container {
            max-width: 100%;
            margin: 0 auto;
        }
        
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #4f46e5;
        }
        
        .header h1 {
            font-size: 20px;
            margin-bottom: 8px;
            color: #1e293b;
        }
        
        .header h3 {
            font-size: 14px;
            margin-bottom: 5px;
            color: #475569;
        }
        
        .header p {
            font-size: 11px;
            color: #64748b;
        }
        
        /* Tabel */
        .table-container {
            margin: 20px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        
        th {
            background: #4f46e5;
            color: white;
            padding: 12px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #cbd5e1;
        }
        
        td {
            padding: 10px;
            text-align: center;
            border: 1px solid #cbd5e1;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-left {
            text-align: left;
        }
        
        .bg-primary {
            background: #eef2ff;
        }
        
        .bg-success {
            background: #f0fdf4;
        }
        
        .font-bold {
            font-weight: bold;
        }
        
        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
        }
        
        .note {
            margin-top: 10px;
            padding: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            font-size: 11px;
            font-style: italic;
            text-align: center;
            color: #475569;
        }
        
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .container {
                padding: 10px;
            }
            th {
                background: #4f46e5 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .bg-primary {
                background: #eef2ff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .bg-success {
                background: #f0fdf4 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Header --}}
        <div class="header">
            <h1>LAPORAN PENYUSUTAN ASET</h1>
            <h3>{{ $penyusutan->asset->nama_asset ?? 'Aset' }}</h3>
            <p>Kode Aset: {{ $penyusutan->asset->kode_asset ?? '-' }} | Periode: {{ $penyusutan->tahun }} @if($penyusutan->bulan) - {{ \Carbon\Carbon::create()->month($penyusutan->bulan)->format('F') }} @else (Tahunan) @endif</p>
            <p>Metode: {{ $penyusutan->metode == 'garis_lurus' ? 'Garis Lurus' : 'Saldo Menurun' }}</p>
        </div>

        {{-- Tabel Perhitungan Penyusutan --}}
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 15%;">Tahun</th>
                        <th style="width: 25%;">Biaya Perolehan</th>
                        <th style="width: 20%;">Penyusutan</th>
                        <th style="width: 20%;">Akum. Penyusutan</th>
                        <th style="width: 20%;">Nilai Buku</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $nilaiPerolehan = $penyusutan->asset->nilai_perolehan ?? $penyusutan->nilai_awal ?? 0;
                        $nilaiResidu = $penyusutan->asset->nilai_residu ?? 0;
                        $umurEkonomis = $penyusutan->asset->umur_ekonomis ?? 1;
                        $metode = $penyusutan->metode ?? 'garis_lurus';
                        $persen = $penyusutan->persentase_penyusutan ?? 0;
                        $startYear = $penyusutan->tahun ?? date('Y');
                        
                        $akumPenyusutan = 0;
                        $nilaiBuku = $nilaiPerolehan;
                    @endphp
                    
                    @for($tahun = 1; $tahun <= $umurEkonomis; $tahun++)
                        @php
                            $penyusutanTahun = 0;
                            
                            if ($metode === 'garis_lurus') {
                                $penyusutanTahun = ($nilaiPerolehan - $nilaiResidu) / $umurEkonomis;
                            } elseif ($metode === 'saldo_menurun') {
                                if ($tahun === 1) {
                                    $penyusutanTahun = $nilaiPerolehan * ($persen / 100);
                                } else {
                                    $penyusutanTahun = $nilaiBuku * ($persen / 100);
                                }
                                
                                if ($tahun === $umurEkonomis) {
                                    $sisa = $nilaiBuku - $nilaiResidu;
                                    if ($penyusutanTahun > $sisa) {
                                        $penyusutanTahun = $sisa;
                                    }
                                }
                            }
                            
                            $penyusutanTahun = floor($penyusutanTahun);
                            if ($penyusutanTahun < 0) $penyusutanTahun = 0;
                            
                            if ($tahun === 1) {
                                $akumPenyusutan = $penyusutanTahun;
                                $nilaiBuku = $nilaiPerolehan - $penyusutanTahun;
                            } else {
                                $akumPenyusutan += $penyusutanTahun;
                                $nilaiBuku = $nilaiPerolehan - $akumPenyusutan;
                            }
                            
                            if ($tahun === $umurEkonomis && $nilaiBuku < $nilaiResidu) {
                                $nilaiBuku = $nilaiResidu;
                                $akumPenyusutan = $nilaiPerolehan - $nilaiResidu;
                            }
                            
                            $rowClass = ($tahun === $umurEkonomis) ? 'bg-success' : '';
                            $yearDisplay = ($tahun === 1) ? $startYear : $startYear + ($tahun - 1);
                        @endphp
                        <tr class="{{ $rowClass }}">
                            <td>{{ $yearDisplay }}</td>
                            <td class="text-right">Rp {{ number_format($nilaiPerolehan, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($penyusutanTahun, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($akumPenyusutan, 0, ',', '.') }}</td>
                            <td class="text-right"><strong>Rp {{ number_format($nilaiBuku, 0, ',', '.') }}</strong></td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        {{-- Note Nilai Residu --}}
        <div class="note">
            Nilai buku akhir = Nilai Residu (Rp {{ number_format($nilaiResidu, 0, ',', '.') }})
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
            <p>Sistem Informasi Manajemen Aset - Jobie</p>
        </div>
    </div>
</body>
</html>