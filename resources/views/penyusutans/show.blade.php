@extends('layouts.app')

@section('title', 'Detail Penyusutan Aset - Jobie')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Detail Penyusutan Aset</h1>
        <p class="mt-1 text-gray-600">Informasi lengkap perhitungan penyusutan</p>
    </div>
    <div class="mt-4 sm:mt-0 flex space-x-2">
        <a href="{{ route('penyusutan.index') }}"
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>
</div>
@endsection

@push('styles')
<style>
    .hover\:bg-neutral-50:hover {
        background-color: #f9fafb;
    }
    .transition-colors {
        transition-property: background-color;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 150ms;
    }
    @media print {
        .no-print {
            display: none !important;
        }
        .print-only {
            display: block !important;
        }
        body {
            padding: 20px;
            font-size: 12px;
        }
        .bg-white {
            background: white !important;
            box-shadow: none !important;
        }
        .shadow-lg {
            box-shadow: none !important;
        }
        .rounded-xl {
            border-radius: 0 !important;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f3f4f6 !important;
            color: black !important;
        }
    }
    
    /* Style untuk PDF */
    .pdf-content {
        font-family: 'Segoe UI', Arial, sans-serif;
    }
    .pdf-content table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    .pdf-content th, .pdf-content td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    .pdf-content th {
        background-color: #4f46e5;
        color: white;
    }
    .pdf-content td.text-right {
        text-align: right;
    }
</style>
@endpush

@section('content')
<div class="max-w-full mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Detail Penyusutan --}}
        <div class="lg:col-span-2">
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-purple-50 px-6 py-4 border-b border-purple-100">
                    <h3 class="text-lg font-semibold text-purple-900">Informasi Penyusutan</h3>
                </div>
                <div class="p-6">

                    {{-- Ringkasan Nilai --}}
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <p class="text-sm text-gray-500">Nilai Awal</p>
                            <p class="text-xl font-bold text-gray-900">
                                Rp {{ number_format($penyusutan->nilai_awal, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="bg-orange-50 p-4 rounded-lg text-center">
                            <p class="text-sm text-orange-600">Penyusutan</p>
                            <p class="text-xl font-bold text-orange-600">
                                Rp {{ number_format($penyusutan->nilai_penyusutan, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg text-center">
                            <p class="text-sm text-green-600">Nilai Akhir</p>
                            <p class="text-xl font-bold text-green-600">
                                Rp {{ number_format($penyusutan->nilai_akhir, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    {{-- Akumulasi --}}
                    <div class="bg-blue-50 p-4 rounded-lg mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-blue-700 font-medium">Akumulasi Penyusutan</span>
                            <span class="text-xl font-bold text-blue-700">
                                Rp {{ number_format($penyusutan->akumulasi_penyusutan, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    {{-- Detail Tabel --}}
                    <table class="min-w-full divide-y divide-gray-200">
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500 w-1/3">Tahun / Bulan</td>
                                <td class="py-3 text-sm text-gray-900">
                                    {{ $penyusutan->tahun }}
                                    @if($penyusutan->bulan)
                                        ({{ \Carbon\Carbon::create()->month($penyusutan->bulan)->format('F') }})
                                    @else
                                        <span class="text-gray-500">(Tahunan)</span>
                                    @endif
                                 </td>
                              </tr>
                              <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Metode Penyusutan</td>
                                <td class="py-3 text-sm">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        {{ $penyusutan->metode == 'garis_lurus'
                                            ? 'bg-blue-100 text-blue-800'
                                            : 'bg-purple-100 text-purple-800' }}">
                                        {{ $penyusutan->metode == 'garis_lurus' ? 'Garis Lurus' : 'Saldo Menurun' }}
                                    </span>
                                 </td>
                              </tr>

                            @if($penyusutan->metode == 'garis_lurus' && $penyusutan->asset)
                              <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Tarif Garis Lurus</td>
                                <td class="py-3 text-sm text-gray-900">
                                    @php
                                        $umur = $penyusutan->asset->umur_ekonomis;
                                        $tarif = $umur > 0 ? round(100 / $umur, 2) : 0;
                                    @endphp
                                    {{ $tarif }}% per tahun
                                    (umur {{ $umur }} tahun)
                                 </td>
                              </tr>
                            @endif

                            @if($penyusutan->metode == 'saldo_menurun' && $penyusutan->persentase_penyusutan !== null)
                              <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Persentase Penyusutan</td>
                                <td class="py-3 text-sm text-gray-900">
                                    {{ number_format($penyusutan->persentase_penyusutan, 2) }}%
                                 </td>
                              </tr>
                            @endif

                            @if($penyusutan->catatan)
                              <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Catatan</td>
                                <td class="py-3 text-sm text-gray-900">{{ $penyusutan->catatan }}</td>
                              </tr>
                            @endif

                              <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Dibuat Oleh</td>
                                <td class="py-3 text-sm text-gray-900">
                                    {{ $penyusutan->creator->name ?? 'System' }}
                                    <span class="text-gray-500 text-xs ml-2">
                                        ({{ $penyusutan->created_at->format('d/m/Y H:i') }})
                                    </span>
                                 </td>
                              </tr>
                              <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Terakhir Diupdate</td>
                                <td class="py-3 text-sm text-gray-900">
                                    {{ $penyusutan->updater->name ?? 'System' }}
                                    <span class="text-gray-500 text-xs ml-2">
                                        ({{ $penyusutan->updated_at->format('d/m/Y H:i') }})
                                    </span>
                                 </td>
                              </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tabel Preview Perhitungan per Tahun --}}
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200 mt-6">
                <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-6 py-4 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            Tabel Perhitungan Penyusutan per Tahun
                        </h3>
                        <p class="text-white/80 text-sm mt-1">Detail perhitungan penyusutan dari tahun ke tahun</p>
                    </div>
                    
                    <div class="flex gap-2 no-print">
                        <button onclick="downloadPDF()" 
                                class="inline-flex items-center px-4 py-2 bg-white text-primary-700 rounded-lg hover:bg-primary-50 transition-all duration-200 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download PDF
                        </button>
                        <button onclick="window.print()" 
                                class="inline-flex items-center px-4 py-2 bg-white text-primary-700 rounded-lg hover:bg-primary-50 transition-all duration-200 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print
                        </button>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="overflow-x-auto" id="pdfContent">
                        <div id="pdfHeader" style="text-align: center; margin-bottom: 20px; display: none;">
                            <h2 style="font-size: 24px; font-weight: bold; margin-bottom: 10px;">LAPORAN PENYUSUTAN ASET</h2>
                            <p style="font-size: 14px; margin-bottom: 5px;">{{ $penyusutan->asset->nama_asset ?? 'Aset' }}</p>
                            <p style="font-size: 12px; color: #666;">Periode: {{ $penyusutan->tahun }} @if($penyusutan->bulan) - {{ \Carbon\Carbon::create()->month($penyusutan->bulan)->format('F') }} @endif</p>
                            <hr style="margin: 15px 0;">
                        </div>
                        
                        <table class="min-w-full bg-white rounded-xl overflow-hidden shadow-sm border border-neutral-200" id="depreciationTable">
                            <thead>
                                <tr style="background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white;">
                                    <th style="padding: 12px; text-align: left;">Tahun</th>
                                    <th style="padding: 12px; text-align: right;">Biaya Perolehan</th>
                                    <th style="padding: 12px; text-align: right;">Penyusutan</th>
                                    <th style="padding: 12px; text-align: right;">Akum. Penyusutan</th>
                                    <th style="padding: 12px; text-align: right;">Nilai Buku</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $nilaiPerolehan = $penyusutan->asset->nilai_perolehan ?? $penyusutan->nilai_awal;
                                    $nilaiResidu = $penyusutan->asset->nilai_residu ?? 0;
                                    $umurEkonomis = $penyusutan->asset->umur_ekonomis ?? 1;
                                    $metode = $penyusutan->metode;
                                    $persen = $penyusutan->persentase_penyusutan ?? 0;
                                    $startYear = $penyusutan->tahun;
                                    
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
                                        
                                        $rowStyle = ($tahun === $umurEkonomis) ? 'background: #f0fdf4;' : '';
                                        $yearDisplay = ($tahun === 1) ? $startYear : $startYear + ($tahun - 1);
                                    @endphp
                                    <tr style="{{ $rowStyle }}">
                                        <td style="padding: 10px; {{ $tahun === $umurEkonomis ? 'font-weight: bold; color: #16a34a;' : '' }}">{{ $yearDisplay }}</td>
                                        <td style="padding: 10px; text-align: right;">Rp {{ number_format($nilaiPerolehan, 0, ',', '.') }}</td>
                                        <td style="padding: 10px; text-align: right;">Rp {{ number_format($penyusutanTahun, 0, ',', '.') }}</td>
                                        <td style="padding: 10px; text-align: right;">Rp {{ number_format($akumPenyusutan, 0, ',', '.') }}</td>
                                        <td style="padding: 10px; text-align: right; font-weight: bold; {{ $tahun === $umurEkonomis ? 'color: #16a34a;' : '' }}">
                                            Rp {{ number_format($nilaiBuku, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                            <tfoot>
                                <tr style="background: #f3f4f6;">
                                    <td colspan="5" style="padding: 10px; font-style: italic;">
                                        Nilai buku akhir = Nilai Residu (Rp {{ number_format($nilaiResidu, 0, ',', '.') }})
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        
                        <div id="pdfFooter" style="text-align: center; margin-top: 20px; display: none;">
                            <hr style="margin: 15px 0;">
                            <p style="font-size: 10px; color: #666;">Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
                            <p style="font-size: 10px; color: #666;">Dibuat oleh: {{ $penyusutan->creator->name ?? 'System' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Info Aset --}}
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-blue-50 px-6 py-4 border-b border-blue-100">
                    <h3 class="text-lg font-semibold text-blue-900">Informasi Aset</h3>
                </div>
                <div class="p-6">
                    @if($penyusutan->asset)
                    <table class="min-w-full">
                        <tbody class="divide-y divide-gray-200">
                             <tr>
                                <td class="py-2 text-sm font-medium text-gray-500">Kode Aset</td>
                                <td class="py-2 text-sm text-gray-900 font-mono">{{ $penyusutan->asset->kode_asset }}</td>
                             </tr>
                             <tr>
                                <td class="py-2 text-sm font-medium text-gray-500">Nama Aset</td>
                                <td class="py-2 text-sm text-gray-900">{{ $penyusutan->asset->nama_asset }}</td>
                             </tr>
                             <tr>
                                <td class="py-2 text-sm font-medium text-gray-500">Nilai Perolehan</td>
                                <td class="py-2 text-sm text-gray-900">
                                    Rp {{ number_format($penyusutan->asset->nilai_perolehan, 0, ',', '.') }}
                                 </td>
                             </tr>
                             <tr>
                                <td class="py-2 text-sm font-medium text-gray-500">Nilai Residu</td>
                                <td class="py-2 text-sm text-gray-900">
                                    Rp {{ number_format($penyusutan->asset->nilai_residu, 0, ',', '.') }}
                                 </td>
                             </tr>
                             <tr>
                                <td class="py-2 text-sm font-medium text-gray-500">Umur Ekonomis</td>
                                <td class="py-2 text-sm text-gray-900">{{ $penyusutan->asset->umur_ekonomis }} tahun</td>
                             </tr>
                            @php
                                $totalAkumulasi = \App\Models\Penyusutan::where('assetID', $penyusutan->assetID)
                                    ->where('penyusutanID', '<=', $penyusutan->penyusutanID)
                                    ->sum('nilai_penyusutan');
                                $persenTerdepresiasi = $penyusutan->asset->nilai_perolehan > 0
                                    ? ($totalAkumulasi / $penyusutan->asset->nilai_perolehan) * 100
                                    : 0;
                            @endphp
                             <tr>
                                <td class="py-2 text-sm font-medium text-gray-500" colspan="2">
                                    <div class="mt-2">
                                        <div class="flex justify-between text-xs mb-1">
                                            <span class="text-gray-600">Terdepresiasi</span>
                                            <span class="font-medium">{{ number_format($persenTerdepresiasi, 1) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-orange-400 h-2 rounded-full transition-all"
                                                 style="width: {{ min(100, $persenTerdepresiasi) }}%"></div>
                                        </div>
                                    </div>
                                 </td>
                             </tr>
                        </tbody>
                    </table>
                    @else
                        <p class="text-sm text-gray-500">Data aset tidak tersedia.</p>
                    @endif
                </div>
            </div>

            {{-- Info Instansi --}}
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-green-50 px-6 py-4 border-b border-green-100">
                    <h3 class="text-lg font-semibold text-green-900">Informasi Instansi</h3>
                </div>
                <div class="p-6">
                    @if($penyusutan->instansi)
                    <table class="min-w-full">
                        <tbody class="divide-y divide-gray-200">
                             <tr>
                                <td class="py-2 text-sm font-medium text-gray-500">Kode Instansi</td>
                                <td class="py-2 text-sm text-gray-900">{{ $penyusutan->instansi->KodeInstansi }}</td>
                             </tr>
                             <tr>
                                <td class="py-2 text-sm font-medium text-gray-500">Nama Sekolah</td>
                                <td class="py-2 text-sm text-gray-900">{{ $penyusutan->instansi->NamaSekolah }}</td>
                             </tr>
                             <tr>
                                <td class="py-2 text-sm font-medium text-gray-500">NPSN</td>
                                <td class="py-2 text-sm text-gray-900">{{ $penyusutan->instansi->NPSN }}</td>
                             </tr>
                             <tr>
                                <td class="py-2 text-sm font-medium text-gray-500">Jenjang</td>
                                <td class="py-2 text-sm text-gray-900">{{ $penyusutan->instansi->JenjangSekolah }}</td>
                             </tr>
                        </tbody>
                    </table>
                    @else
                        <p class="text-sm text-gray-500">Data instansi tidak tersedia.</p>
                    @endif
                </div>
            </div>

            {{-- Informasi Tambahan --}}
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-yellow-50 px-6 py-4 border-b border-yellow-100">
                    <h3 class="text-lg font-semibold text-yellow-900">Ringkasan Penyusutan</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Penyusutan:</span>
                            <span class="text-lg font-bold text-orange-600">
                                Rp {{ number_format($penyusutan->nilai_penyusutan, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Akumulasi Penyusutan:</span>
                            <span class="text-lg font-bold text-blue-600">
                                Rp {{ number_format($penyusutan->akumulasi_penyusutan, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Sisa Nilai Buku:</span>
                            <span class="text-lg font-bold text-green-600">
                                Rp {{ number_format($penyusutan->nilai_akhir, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="border-t border-gray-200 my-2"></div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Metode:</span>
                            <span class="text-sm font-medium">{{ $penyusutan->metode == 'garis_lurus' ? 'Garis Lurus' : 'Saldo Menurun' }}</span>
                        </div>
                        @if($penyusutan->metode == 'saldo_menurun' && $penyusutan->persentase_penyusutan)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Persentase:</span>
                            <span class="text-sm font-medium">{{ number_format($penyusutan->persentase_penyusutan, 2) }}%</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function downloadPDF() {
    // Tampilkan loading
    const btn = event.currentTarget;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Loading...';
    btn.disabled = true;
    
    // Fetch PDF
    fetch('{{ route("penyusutan.pdf", $penyusutan->penyusutanID) }}', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'Laporan_Penyusutan_{{ $penyusutan->asset->kode_asset ?? "Aset" }}_{{ date("Ymd") }}.pdf';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal mengunduh PDF. Silakan coba lagi.');
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}
</script>
@endpush