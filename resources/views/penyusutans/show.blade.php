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

@section('content')
<div class="max-w-6xl mx-auto">
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
                            {{-- FIX: tampilkan tarif efektif garis lurus meski persentase_penyusutan = null --}}
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
                                    ->sum('nilai_penyusutan');
                                $sisaNilai = $penyusutan->asset->nilai_perolehan - $totalAkumulasi;
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
        </div>
    </div>
</div>
@endsection