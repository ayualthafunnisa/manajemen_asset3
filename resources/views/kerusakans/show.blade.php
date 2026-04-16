{{-- resources/views/kerusakans/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Laporan Kerusakan - ' . $kerusakan->kode_laporan)

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Detail Laporan Kerusakan</h1>
        <p class="mt-1 text-gray-600">Laporan #{{ $kerusakan->kode_laporan }}</p>
    </div>
    <div class="mt-4 sm:mt-0 flex space-x-2">
        <a href="{{ route('kerusakan.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition duration-150 ease-in-out">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
        @if($kerusakan->status_perbaikan === 'dilaporkan' && (auth()->user()->role === 'staf_asset' && $kerusakan->dilaporkan_oleh === auth()->id()))
        <a href="{{ route('kerusakan.edit', $kerusakan->kerusakanID) }}" 
           class="inline-flex items-center px-4 py-2 border border-purple-600 bg-purple-600 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 transition duration-150 ease-in-out">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit
        </a>
        @endif
    </div>
</div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content - Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status Timeline Card -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gradient-to-r from-red-50 to-orange-50 px-6 py-4 border-b border-red-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-red-900">Status Perbaikan</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($kerusakan->status_perbaikan == 'dilaporkan') bg-yellow-100 text-yellow-800
                            @elseif($kerusakan->status_perbaikan == 'diproses') bg-blue-100 text-blue-800
                            @elseif($kerusakan->status_perbaikan == 'selesai') bg-green-100 text-green-800
                            @elseif($kerusakan->status_perbaikan == 'ditolak') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            <span class="w-2 h-2 rounded-full mr-1.5 
                                @if($kerusakan->status_perbaikan == 'dilaporkan') bg-yellow-500
                                @elseif($kerusakan->status_perbaikan == 'diproses') bg-blue-500
                                @elseif($kerusakan->status_perbaikan == 'selesai') bg-green-500
                                @elseif($kerusakan->status_perbaikan == 'ditolak') bg-red-500
                                @else bg-gray-500 @endif">
                            </span>
                            {{ ucfirst($kerusakan->status_perbaikan) }}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <!-- Timeline Progress -->
                    <div class="relative">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="relative">
                                    <div class="flex items-center justify-between">
                                        <!-- Dilaporkan -->
                                        <div class="flex flex-col items-center">
                                            <div class="w-10 h-10 rounded-full 
                                                @if(in_array($kerusakan->status_perbaikan, ['dilaporkan', 'diproses', 'selesai'])) bg-yellow-500 
                                                @else bg-gray-300 @endif
                                                flex items-center justify-center text-white font-bold">
                                                1
                                            </div>
                                            <span class="text-xs mt-2 text-gray-600">Dilaporkan</span>
                                        </div>
                                        
                                        <!-- Diproses -->
                                        <div class="flex flex-col items-center">
                                            <div class="w-10 h-10 rounded-full 
                                                @if(in_array($kerusakan->status_perbaikan, ['diproses', 'selesai'])) bg-blue-500 
                                                @else bg-gray-300 @endif
                                                flex items-center justify-center text-white font-bold">
                                                2
                                            </div>
                                            <span class="text-xs mt-2 text-gray-600">Diproses</span>
                                        </div>
                                        
                                        <!-- Selesai -->
                                        <div class="flex flex-col items-center">
                                            <div class="w-10 h-10 rounded-full 
                                                @if($kerusakan->status_perbaikan == 'selesai') bg-green-500 
                                                @else bg-gray-300 @endif
                                                flex items-center justify-center text-white font-bold">
                                                3
                                            </div>
                                            <span class="text-xs mt-2 text-gray-600">Selesai</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Progress Line -->
                                    <div class="absolute top-5 left-0 w-full h-1 bg-gray-200 -z-10">
                                        <div class="h-full bg-gradient-to-r from-yellow-500 via-blue-500 to-green-500 transition-all duration-500"
                                             style="width: 
                                                @if($kerusakan->status_perbaikan == 'dilaporkan') 0%
                                                @elseif($kerusakan->status_perbaikan == 'diproses') 50%
                                                @elseif($kerusakan->status_perbaikan == 'selesai') 100%
                                                @else 0% @endif">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Estimasi Selesai dari Perbaikan -->
                    @php
                        $perbaikan = $kerusakan->perbaikans->first();
                    @endphp
                    
                    @if($perbaikan)
                        <!-- Estimasi Selesai -->
                        @if($perbaikan->estimasi_selesai && $kerusakan->status_perbaikan == 'diproses')
                            <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-semibold text-blue-900">Estimasi Selesai Perbaikan</h4>
                                        <p class="text-lg font-bold text-blue-800 mt-1">
                                            {{ \Carbon\Carbon::parse($perbaikan->estimasi_selesai)->translatedFormat('l, d F Y') }}
                                        </p>
                                        <div class="flex items-center gap-2 mt-2">
                                            <span class="text-xs text-blue-600">
                                                Estimasi waktu tersisa: 
                                                @php
                                                    $now = \Carbon\Carbon::now();
                                                    $estimasi = \Carbon\Carbon::parse($perbaikan->estimasi_selesai);
                                                    if ($now->lt($estimasi)) {
                                                        echo $now->diffForHumans($estimasi, ['parts' => 2, 'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW]);
                                                    } else {
                                                        echo 'Melebihi estimasi';
                                                    }
                                                @endphp
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Tanggal Mulai Perbaikan -->
                        @if($perbaikan->mulai_perbaikan && $kerusakan->status_perbaikan == 'diproses')
                            <div class="mt-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm text-gray-600">Mulai Perbaikan:</span>
                                    <span class="text-sm font-medium text-gray-800">{{ \Carbon\Carbon::parse($perbaikan->mulai_perbaikan)->translatedFormat('d F Y H:i') }}</span>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Tanggal Selesai Perbaikan -->
                        @if($perbaikan->selesai_perbaikan && $kerusakan->status_perbaikan == 'selesai')
                            <div class="mt-6 p-4 bg-green-50 rounded-lg border border-green-200">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-semibold text-green-900">Perbaikan Selesai</h4>
                                        <p class="text-md font-medium text-green-800 mt-1">
                                            {{ \Carbon\Carbon::parse($perbaikan->selesai_perbaikan)->translatedFormat('l, d F Y H:i') }}
                                        </p>
                                        @if($perbaikan->estimasi_selesai)
                                            @php
                                                $estimasi = \Carbon\Carbon::parse($perbaikan->estimasi_selesai);
                                                $selesai = \Carbon\Carbon::parse($perbaikan->selesai_perbaikan);
                                                $selisih = $estimasi->diffInDays($selesai, false);
                                            @endphp
                                            @if($selisih > 0)
                                                <p class="text-xs text-orange-600 mt-1">
                                                    ⚠️ Selesai {{ $selisih }} hari {{ $selisih > 0 ? 'melebihi' : 'sebelum' }} estimasi
                                                </p>
                                            @elseif($selisih < 0)
                                                <p class="text-xs text-green-600 mt-1">
                                                    ✅ Selesai {{ abs($selisih) }} hari lebih cepat dari estimasi
                                                </p>
                                            @else
                                                <p class="text-xs text-blue-600 mt-1">
                                                    ✅ Selesai tepat waktu sesuai estimasi
                                                </p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Alert jika tidak ada estimasi tapi status diproses -->
                        @if(!$perbaikan->estimasi_selesai && $kerusakan->status_perbaikan == 'diproses')
                            <div class="mt-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-semibold text-yellow-900">Estimasi Perbaikan Belum Ditentukan</h4>
                                        <p class="text-sm text-yellow-700 mt-1">Estimasi selesai perbaikan akan segera ditentukan oleh teknisi.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            
            <!-- Informasi Kerusakan Card -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Kerusakan</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Kode Laporan</p>
                                <p class="text-lg font-bold text-gray-900 mt-1 font-mono">{{ $kerusakan->kode_laporan }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Jenis Kerusakan</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1
                                    @if($kerusakan->jenis_kerusakan == 'ringan') bg-green-100 text-green-800
                                    @elseif($kerusakan->jenis_kerusakan == 'sedang') bg-yellow-100 text-yellow-800
                                    @elseif($kerusakan->jenis_kerusakan == 'berat') bg-orange-100 text-orange-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($kerusakan->jenis_kerusakan) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Tingkat Kerusakan</p>
                                <div class="mt-2">
                                    <div class="flex items-center">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2.5">
                                            <div class="rounded-full h-2.5 
                                                @if($kerusakan->tingkat_kerusakan <= 25) bg-green-600
                                                @elseif($kerusakan->tingkat_kerusakan <= 50) bg-yellow-600
                                                @elseif($kerusakan->tingkat_kerusakan <= 75) bg-orange-600
                                                @else bg-red-600 @endif"
                                                style="width: {{ $kerusakan->tingkat_kerusakan }}%">
                                            </div>
                                        </div>
                                        <span class="ml-3 text-sm font-medium text-gray-700">{{ $kerusakan->tingkat_kerusakan }}%</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Prioritas</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1
                                    @if($kerusakan->prioritas == 'kritis') bg-red-100 text-red-800
                                    @elseif($kerusakan->prioritas == 'tinggi') bg-orange-100 text-orange-800
                                    @elseif($kerusakan->prioritas == 'sedang') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ ucfirst($kerusakan->prioritas) }}
                                </span>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Tanggal Laporan</p>
                                <p class="text-sm text-gray-900 mt-1">{{ \Carbon\Carbon::parse($kerusakan->tanggal_laporan)->translatedFormat('d F Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Tanggal Kerusakan</p>
                                <p class="text-sm text-gray-900 mt-1">{{ \Carbon\Carbon::parse($kerusakan->tanggal_kerusakan)->translatedFormat('d F Y') }}</p>
                            </div>
                            @if($kerusakan->estimasi_biaya)
                            <div>
                                <p class="text-sm font-medium text-gray-500">Estimasi Biaya</p>
                                <p class="text-lg font-bold text-gray-900 mt-1">
                                    Rp {{ number_format($kerusakan->estimasi_biaya, 0, ',', '.') }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Deskripsi Kerusakan -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <p class="text-sm font-medium text-gray-500 mb-2">Deskripsi Kerusakan</p>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $kerusakan->deskripsi_kerusakan }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Informasi Asset Card -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Asset</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Nama Asset</p>
                            <p class="text-md font-semibold text-gray-900 mt-1">{{ $kerusakan->asset->nama_asset ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Kode Asset</p>
                            <p class="text-md font-mono text-gray-900 mt-1">{{ $kerusakan->asset->kode_asset ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Kategori</p>
                            <p class="text-sm text-gray-900 mt-1">{{ $kerusakan->asset->kategori->NamaKategori ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Status Asset</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1
                                @if($kerusakan->asset->status_asset == 'aktif') bg-green-100 text-green-800
                                @elseif($kerusakan->asset->status_asset == 'diperbaiki') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $kerusakan->asset->status_asset ?? '-')) }}
                            </span>
                        </div>
                    </div>
                    
                    @if($kerusakan->asset && $kerusakan->asset->lokasi)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-sm font-medium text-gray-500 mb-2">Lokasi Asset</p>
                        <div class="bg-blue-50 rounded-lg p-3">
                            <p class="text-sm text-gray-700">
                                {{ $kerusakan->lokasi->NamaLokasi ?? '-' }}
                                @if($kerusakan->lokasi)
                                    (Gedung {{ $kerusakan->lokasi->Gedung ?: '-' }}, 
                                    Lantai {{ $kerusakan->lokasi->Lantai ?: '-' }}, 
                                    Ruangan {{ $kerusakan->lokasi->Ruangan ?: '-' }})
                                @endif
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Detail Perbaikan Card (jika ada) -->
            @if($kerusakan->perbaikans && $kerusakan->perbaikans->isNotEmpty())
                @php $perbaikan = $kerusakan->perbaikans->first(); @endphp
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Detail Perbaikan</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @if($perbaikan->tindakan_perbaikan)
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tindakan Perbaikan</p>
                            <p class="text-sm text-gray-900 mt-1">{{ $perbaikan->tindakan_perbaikan }}</p>
                        </div>
                        @endif
                        
                        @if($perbaikan->catatan_perbaikan)
                        <div>
                            <p class="text-sm font-medium text-gray-500">Catatan Perbaikan</p>
                            <div class="bg-gray-50 rounded-lg p-3 mt-1">
                                <p class="text-sm text-gray-700">{{ $perbaikan->catatan_perbaikan }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($perbaikan->komponen_diganti)
                        <div>
                            <p class="text-sm font-medium text-gray-500">Komponen yang Diganti</p>
                            <p class="text-sm text-gray-900 mt-1">{{ $perbaikan->komponen_diganti }}</p>
                        </div>
                        @endif
                        
                        @if($perbaikan->biaya_aktual)
                        <div>
                            <p class="text-sm font-medium text-gray-500">Biaya Aktual</p>
                            <p class="text-lg font-bold text-gray-900 mt-1">
                                Rp {{ number_format($perbaikan->biaya_aktual, 0, ',', '.') }}
                            </p>
                        </div>
                        @endif
                        
                        <!-- Informasi Teknisi -->
                        @if($perbaikan->teknisi)
                        <div>
                            <p class="text-sm font-medium text-gray-500">Teknisi</p>
                            <p class="text-sm text-gray-900 mt-1">{{ $perbaikan->teknisi->name }}</p>
                        </div>
                        @endif
                        
                        <!-- Informasi Estimasi Selesai yang lebih detail -->
                        @if($perbaikan->estimasi_selesai)
                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-sm font-medium text-gray-500 mb-3">Informasi Waktu Perbaikan</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @if($perbaikan->mulai_perbaikan)
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-xs text-gray-500">Mulai Perbaikan</p>
                                    <p class="text-sm font-medium text-gray-800">
                                        {{ \Carbon\Carbon::parse($perbaikan->mulai_perbaikan)->translatedFormat('d F Y H:i') }}
                                    </p>
                                </div>
                                @endif
                                <div class="bg-blue-50 rounded-lg p-3">
                                    <p class="text-xs text-gray-500">Estimasi Selesai</p>
                                    <p class="text-sm font-medium text-blue-800">
                                        {{ \Carbon\Carbon::parse($perbaikan->estimasi_selesai)->translatedFormat('d F Y H:i') }}
                                    </p>
                                </div>
                                @if($perbaikan->selesai_perbaikan)
                                <div class="bg-green-50 rounded-lg p-3">
                                    <p class="text-xs text-gray-500">Selesai Perbaikan</p>
                                    <p class="text-sm font-medium text-green-800">
                                        {{ \Carbon\Carbon::parse($perbaikan->selesai_perbaikan)->translatedFormat('d F Y H:i') }}
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                        
                        @if($perbaikan->status == 'tidak_bisa_diperbaiki' && $perbaikan->alasan_tidak_bisa)
                        <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-red-900">Alasan Tidak Bisa Diperbaiki</p>
                                    <p class="text-sm text-red-800 mt-1">{{ $perbaikan->alasan_tidak_bisa }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Foto Kerusakan Card -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Foto Kerusakan</h3>
                </div>
                <div class="p-6">
                    @if($kerusakan->foto_kerusakan)
                        <div class="bg-gray-100 rounded-lg overflow-hidden">
                            <img src="{{ Storage::url($kerusakan->foto_kerusakan) }}" 
                                 alt="Foto Kerusakan" 
                                 class="w-full h-64 object-cover">
                        </div>
                        <div class="mt-4 flex justify-center">
                            <a href="{{ Storage::url($kerusakan->foto_kerusakan) }}" target="_blank"
                               class="inline-flex items-center px-4 py-2 border border-red-600 text-red-600 rounded-lg hover:bg-red-50 transition duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Lihat Fullscreen
                            </a>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-8">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Tidak ada foto</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Right Column - Sidebar -->
        <div class="space-y-6">
            <!-- QR Code Card -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gradient-to-r from-red-50 to-orange-50 px-6 py-4 border-b border-red-100">
                    <h3 class="text-lg font-semibold text-red-900">QR Code Laporan</h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-col items-center">
                        <div class="bg-white p-4 rounded-xl border-2 border-red-100 shadow-inner">
                            {!! $kerusakan->qrCode !!}
                        </div>
                        
                        <div class="mt-4 text-center">
                            <p class="text-sm font-medium text-gray-500">Kode Laporan</p>
                            <p class="text-lg font-bold text-gray-900 font-mono">{{ $kerusakan->kode_laporan }}</p>
                        </div>
                        
                        <div class="mt-6 w-full">
                            <a href="{{ route('kerusakan.qrcode.download', $kerusakan->kerusakanID) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-600 text-red-600 rounded-lg hover:bg-red-50 transition duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download QR Code
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Informasi Pelapor Card -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Pelapor</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nama Pelapor</p>
                        <p class="text-md font-semibold text-gray-900 mt-1">{{ $kerusakan->pelapor->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Email</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $kerusakan->pelapor->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Role</p>
                        <p class="text-sm text-gray-600 mt-1 capitalize">{{ $kerusakan->pelapor->role ?? '-' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Informasi Sistem Card -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Sistem</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">ID Laporan</p>
                        <p class="text-sm font-mono text-gray-900 mt-1">#{{ $kerusakan->kerusakanID }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Dibuat Pada</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $kerusakan->created_at->translatedFormat('d F Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $kerusakan->created_at->format('H:i:s') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Terakhir Diupdate</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $kerusakan->updated_at->translatedFormat('d F Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $kerusakan->updated_at->format('H:i:s') }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Countdown Timer Card untuk Estimasi -->
            @if($kerusakan->perbaikans->first() && $kerusakan->perbaikans->first()->estimasi_selesai && $kerusakan->status_perbaikan == 'diproses')
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-blue-100">
                    <h3 class="text-lg font-semibold text-blue-900">Countdown Perbaikan</h3>
                </div>
                <div class="p-6">
                    <div class="text-center" id="countdown-timer">
                        <div class="grid grid-cols-4 gap-2">
                            <div class="bg-gray-100 rounded-lg p-2">
                                <span id="days" class="text-2xl font-bold text-blue-600">0</span>
                                <p class="text-xs text-gray-500">Hari</p>
                            </div>
                            <div class="bg-gray-100 rounded-lg p-2">
                                <span id="hours" class="text-2xl font-bold text-blue-600">0</span>
                                <p class="text-xs text-gray-500">Jam</p>
                            </div>
                            <div class="bg-gray-100 rounded-lg p-2">
                                <span id="minutes" class="text-2xl font-bold text-blue-600">0</span>
                                <p class="text-xs text-gray-500">Menit</p>
                            </div>
                            <div class="bg-gray-100 rounded-lg p-2">
                                <span id="seconds" class="text-2xl font-bold text-blue-600">0</span>
                                <p class="text-xs text-gray-500">Detik</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-3">
                            Estimasi selesai: {{ \Carbon\Carbon::parse($kerusakan->perbaikans->first()->estimasi_selesai)->translatedFormat('d F Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>
            
            <script>
                // Countdown Timer
                (function() {
                    const estimasiSelesai = '{{ $kerusakan->perbaikans->first()->estimasi_selesai }}';
                    const countdownDate = new Date(estimasiSelesai).getTime();
                    
                    function updateCountdown() {
                        const now = new Date().getTime();
                        const distance = countdownDate - now;
                        
                        if (distance < 0) {
                            document.getElementById('countdown-timer').innerHTML = `
                                <div class="text-center">
                                    <div class="text-orange-600 font-semibold">⚠️ Melebihi estimasi perbaikan</div>
                                    <p class="text-xs text-gray-500 mt-2">Perbaikan melebihi waktu yang diestimasikan</p>
                                </div>
                            `;
                            return;
                        }
                        
                        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                        
                        document.getElementById('days').innerHTML = days;
                        document.getElementById('hours').innerHTML = hours;
                        document.getElementById('minutes').innerHTML = minutes;
                        document.getElementById('seconds').innerHTML = seconds;
                    }
                    
                    updateCountdown();
                    setInterval(updateCountdown, 1000);
                })();
            </script>
            @endif
            
            <!-- Quick Actions Card -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
                </div>
                <div class="p-6 space-y-3">
                    @if(auth()->user()->role === 'admin_sekolah' && in_array($kerusakan->status_perbaikan, ['dilaporkan', 'diproses']))
                    <div class="space-y-2">
                        <form action="{{ route('kerusakan.update-status', $kerusakan->kerusakanID) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status_perbaikan" value="diproses">
                            <button type="submit" class="w-full flex items-center justify-between p-3 border border-blue-200 rounded-lg hover:bg-blue-50 transition duration-150">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-blue-700">Proses Perbaikan</span>
                                </div>
                                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </form>
                        
                        <form action="{{ route('kerusakan.update-status', $kerusakan->kerusakanID) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status_perbaikan" value="selesai">
                            <button type="submit" class="w-full flex items-center justify-between p-3 border border-green-200 rounded-lg hover:bg-green-50 transition duration-150">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-green-700">Tandai Selesai</span>
                                </div>
                                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </form>
                        
                        <form action="{{ route('kerusakan.update-status', $kerusakan->kerusakanID) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status_perbaikan" value="ditolak">
                            <button type="submit" class="w-full flex items-center justify-between p-3 border border-red-200 rounded-lg hover:bg-red-50 transition duration-150">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-red-700">Tolak Laporan</span>
                                </div>
                                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                    @endif
                    
                    @if($kerusakan->status_perbaikan === 'dilaporkan' && (auth()->user()->role === 'staf_asset' && $kerusakan->dilaporkan_oleh === auth()->id()))
                    <form action="{{ route('kerusakan.destroy', $kerusakan->kerusakanID) }}" method="POST" 
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?\n\nTindakan ini tidak dapat dibatalkan.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-between p-3 border border-red-200 rounded-lg hover:bg-red-50 transition duration-150">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-red-700">Hapus Laporan</span>
                            </div>
                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection