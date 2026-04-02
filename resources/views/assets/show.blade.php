{{-- resources/views/assets/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Asset - ' . $asset->nama_asset)

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-white shadow-sm border border-gray-200 px-4 py-3 rounded-lg">
        <li class="breadcrumb-item">
            <a href="" class="text-gray-600 hover:text-purple-600 transition duration-150">Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('asset.index') }}" class="text-gray-600 hover:text-purple-600 transition duration-150">Asset</a>
        </li>
        <li class="breadcrumb-item active text-gray-800" aria-current="page">
            Detail Asset #{{ $asset->assetID }}
        </li>
    </ol>
</nav>
@endsection

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Detail Asset</h1>
        <p class="mt-1 text-gray-600">Informasi lengkap asset #{{ $asset->assetID }}</p>
    </div>
    <div class="mt-4 sm:mt-0 flex space-x-2">
        <a href="{{ route('asset.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition duration-150 ease-in-out">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
        <a href="{{ route('asset.edit', $asset->assetID) }}" 
           class="inline-flex items-center px-4 py-2 border border-purple-600 bg-purple-600 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 transition duration-150 ease-in-out">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content - Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informasi Umum Card -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gradient-to-r from-purple-50 to-blue-50 px-6 py-4 border-b border-purple-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-purple-900">Informasi Umum</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($asset->status_asset == 'aktif') bg-green-100 text-green-800
                            @elseif($asset->status_asset == 'non_aktif') bg-gray-100 text-gray-800
                            @elseif($asset->status_asset == 'dipinjam') bg-blue-100 text-blue-800
                            @elseif($asset->status_asset == 'diperbaiki') bg-yellow-100 text-yellow-800
                            @elseif($asset->status_asset == 'tersimpan') bg-indigo-100 text-indigo-800
                            @elseif($asset->status_asset == 'dihapus') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            <span class="w-2 h-2 rounded-full mr-1.5 
                                @if($asset->status_asset == 'aktif') bg-green-500
                                @elseif($asset->status_asset == 'non_aktif') bg-gray-500
                                @elseif($asset->status_asset == 'dipinjam') bg-blue-500
                                @elseif($asset->status_asset == 'diperbaiki') bg-yellow-500
                                @elseif($asset->status_asset == 'tersimpan') bg-indigo-500
                                @elseif($asset->status_asset == 'dihapus') bg-red-500
                                @else bg-gray-500 @endif">
                            </span>
                            {{ ucfirst(str_replace('_', ' ', $asset->status_asset)) }}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Kode Asset</p>
                                <p class="text-lg font-bold text-gray-900 mt-1 font-mono">{{ $asset->kode_asset }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Nama Asset</p>
                                <p class="text-lg font-semibold text-gray-900 mt-1">{{ $asset->nama_asset }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Kategori</p>
                                <span class="inline-block px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs mt-1">
                                    {{ $asset->kategori->NamaKategori ?? '-' }}
                                </span>
                                @if($asset->kategori && $asset->kategori->KodeKategori)
                                <p class="text-xs text-gray-500 mt-1">Kode: {{ $asset->kategori->KodeKategori }}</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Instansi</p>
                                <p class="text-sm text-gray-900 mt-1">{{ $asset->instansi->NamaSekolah ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $asset->instansi->KodeInstansi ?? '' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Lokasi</p>
                                <p class="text-sm text-gray-900 mt-1">{{ $asset->lokasi->NamaLokasi ?? '-' }}</p>
                                @if($asset->lokasi)
                                <p class="text-xs text-gray-500">
                                    {{ $asset->lokasi->Gedung ? 'Gedung '.$asset->lokasi->Gedung : '' }}
                                    {{ $asset->lokasi->Lantai ? 'Lantai '.$asset->lokasi->Lantai : '' }}
                                    {{ $asset->lokasi->Ruangan ? 'Ruangan '.$asset->lokasi->Ruangan : '' }}
                                </p>
                                @endif
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Jumlah & Satuan</p>
                                <div class="flex items-baseline mt-1">
                                    <span class="text-2xl font-bold text-gray-900">{{ $asset->jumlah }}</span>
                                    <span class="ml-2 text-sm text-gray-600">{{ $asset->satuan ?? 'unit' }}</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Kondisi</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1
                                    @if($asset->kondisi == 'baik') bg-green-100 text-green-800
                                    @elseif($asset->kondisi == 'rusak_ringan') bg-yellow-100 text-yellow-800
                                    @elseif($asset->kondisi == 'rusak_berat') bg-red-100 text-red-800
                                    @elseif($asset->kondisi == 'tidak_berfungsi') bg-orange-100 text-orange-800
                                    @elseif($asset->kondisi == 'hilang') bg-gray-100 text-gray-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $asset->kondisi)) }}
                                </span>
                            </div>
                            @if($asset->merk)
                            <div>
                                <p class="text-sm font-medium text-gray-500">Merk</p>
                                <p class="text-sm text-gray-900 mt-1">{{ $asset->merk }}</p>
                            </div>
                            @endif
                            @if($asset->serial_number)
                            <div>
                                <p class="text-sm font-medium text-gray-500">Serial Number</p>
                                <p class="text-sm font-mono text-gray-900 mt-1">{{ $asset->serial_number }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Informasi Perolehan & Keuangan Card -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Perolehan & Keuangan</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Sumber Perolehan</p>
                                <p class="text-sm text-gray-900 mt-1 capitalize">
                                    {{ $asset->sumber_perolehan ? ucfirst(str_replace('_', ' ', $asset->sumber_perolehan)) : '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Tanggal Perolehan</p>
                                <p class="text-sm text-gray-900 mt-1">
                                    {{ $asset->tanggal_perolehan ? \Carbon\Carbon::parse($asset->tanggal_perolehan)->format('d F Y') : '-' }}
                                </p>
                            </div>
                            @if($asset->vendor)
                            <div>
                                <p class="text-sm font-medium text-gray-500">Vendor/Supplier</p>
                                <p class="text-sm text-gray-900 mt-1">{{ $asset->vendor }}</p>
                            </div>
                            @endif
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Nilai Perolehan</p>
                                <p class="text-lg font-bold text-gray-900 mt-1">
                                    Rp {{ number_format($asset->nilai_perolehan, 0, ',', '.') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Nilai Residu</p>
                                <p class="text-sm text-gray-900 mt-1">
                                    Rp {{ number_format($asset->nilai_residu ?? 0, 0, ',', '.') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Umur Ekonomis</p>
                                <p class="text-sm text-gray-900 mt-1">{{ $asset->umur_ekonomis }} Tahun</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Keterangan Card -->
            @if($asset->keterangan)
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Keterangan</h3>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $asset->keterangan }}</p>
                </div>
            </div>
            @endif
        </div>
        
        <!-- Right Column - Sidebar -->
        <div class="space-y-6">
            <!-- Gambar Asset Card -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gradient-to-r from-purple-50 to-blue-50 px-6 py-4 border-b border-purple-100">
                    <h3 class="text-lg font-semibold text-purple-900">Gambar Asset</h3>
                </div>
                <div class="p-6">
                    @if($asset->gambar_asset)
                        <div class="bg-gray-100 rounded-lg overflow-hidden">
                            <img src="{{ Storage::url($asset->gambar_asset) }}" 
                                 alt="{{ $asset->nama_asset }}" 
                                 class="w-full h-48 object-cover">
                        </div>
                        <div class="mt-4 flex justify-center">
                            <a href="{{ Storage::url($asset->gambar_asset) }}" target="_blank"
                               class="inline-flex items-center px-4 py-2 border border-purple-600 text-purple-600 rounded-lg hover:bg-purple-50 transition duration-150">
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
                            <p class="mt-2 text-sm text-gray-500">Tidak ada gambar</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- QR Code Card --}}
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gradient-to-r from-purple-50 to-blue-50 px-6 py-4 border-b border-purple-100">
                    <h3 class="text-lg font-semibold text-purple-900">QR Code Asset</h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-col items-center">
                        <!-- QR Code Display -->
                        <div class="bg-white p-4 rounded-xl border-2 border-purple-100 shadow-inner">
                            {!! $asset->qrCode !!}
                        </div>
                        
                        <!-- Kode Asset -->
                        <div class="mt-4 text-center">
                            <p class="text-sm font-medium text-gray-500">Kode Asset</p>
                            <p class="text-lg font-bold text-gray-900 font-mono">{{ $asset->kode_asset }}</p>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="mt-6 flex space-x-2 w-full">
                            <a href="{{ route('asset.qrcode.download', $asset->assetID) }}" 
                            class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-purple-600 text-purple-600 rounded-lg hover:bg-purple-50 transition duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download SVG
                            </a>
                            <button onclick="printQRCode()" 
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                                Print
                            </button>
                        </div>
                        
                        <!-- Info Tambahan -->
                        <div class="mt-4 text-xs text-gray-500 text-center">
                            <p>Scan untuk melihat detail asset</p>
                            <p>Format SVG - dapat dibuka di browser</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Dokumen Pendukung Card -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Dokumen Pendukung</h3>
                </div>
                <div class="p-6">
                    @if($asset->dokumen_pendukung)
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ basename($asset->dokumen_pendukung) }}</p>
                                <a href="{{ Storage::url($asset->dokumen_pendukung) }}" target="_blank"
                                   class="text-xs text-purple-600 hover:text-purple-800 flex items-center mt-1">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Download Dokumen
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-6">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Tidak ada dokumen pendukung</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Informasi Sistem Card -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Sistem</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">ID Asset</p>
                        <p class="text-sm font-mono text-gray-900 mt-1">#{{ $asset->assetID }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Dibuat Pada</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $asset->created_at->format('d F Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $asset->created_at->format('H:i:s') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Terakhir Diupdate</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $asset->updated_at->format('d F Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $asset->updated_at->format('H:i:s') }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions Card -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('asset.create') }}" 
                       class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Tambah Asset Baru</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    
                    <a href="{{ route('asset.edit', $asset->assetID) }}" 
                       class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Edit Asset</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    
                    <a href="{{ route('asset.index') }}" 
                       class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Lihat Semua Asset</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    
                    <form action="{{ route('asset.destroy', $asset->assetID) }}" method="POST" 
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus asset ini?\n\nTindakan ini tidak dapat dibatalkan.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-between p-3 border border-red-200 rounded-lg hover:bg-red-50 transition duration-150">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-red-700">Hapus Asset</span>
                            </div>
                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function printQRCode() {
    const qrContent = document.querySelector('.bg-white.p-4.rounded-xl').innerHTML;
    const assetName = "{{ $asset->nama_asset }}";
    const assetCode = "{{ $asset->kode_asset }}";
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Print QR Code - {{ $asset->kode_asset }}</title>
                <style>
                    body { font-family: Arial, sans-serif; text-align: center; padding: 20px; }
                    .qr-container { margin: 20px auto; max-width: 300px; }
                    .asset-info { margin-top: 20px; }
                    .asset-name { font-size: 16px; font-weight: bold; }
                    .asset-code { font-size: 14px; color: #666; }
                    @media print {
                        body { padding: 0; }
                        button { display: none; }
                    }
                </style>
            </head>
            <body>
                <div class="qr-container">
                    ${qrContent}
                </div>
                <div class="asset-info">
                    <div class="asset-name">${assetName}</div>
                    <div class="asset-code">${assetCode}</div>
                </div>
                <button onclick="window.print()" style="margin-top: 20px; padding: 10px 20px;">Print</button>
            </body>
        </html>
    `);
    printWindow.document.close();
}
</script>
@endpush
@endsection