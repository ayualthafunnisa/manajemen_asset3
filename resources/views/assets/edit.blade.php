{{-- resources/views/assets/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Asset - ' . $asset->nama_asset)

@section('header')
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Asset</h1>
        <p class="mt-1 text-sm text-gray-600">
            <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $asset->kode_asset }}</span> - {{ $asset->nama_asset }}
        </p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('asset.show', $asset->assetID) }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            Detail
        </a>
        <a href="{{ route('asset.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Main Form --}}
    <div class="lg:col-span-2 space-y-6">
        <form action="{{ route('asset.update', $asset->assetID) }}" method="POST" enctype="multipart/form-data" id="editForm">
            @csrf
            @method('PUT')
            
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg mb-6">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            @endif
            
            {{-- Informasi Dasar Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Dasar</h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Instansi (Read Only) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Instansi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   value="{{ $asset->instansi->KodeInstansi ?? '' }} - {{ $asset->instansi->NamaSekolah ?? '' }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-700 cursor-not-allowed"
                                   readonly
                                   disabled>
                            <input type="hidden" name="InstansiID" value="{{ $asset->InstansiID }}">
                            @error('InstansiID')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Kategori --}}
                        <div>
                            <label for="KategoriID" class="block text-sm font-medium text-gray-700 mb-1">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select name="KategoriID" id="KategoriID" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('KategoriID') border-red-500 @enderror"
                                    required>
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->KategoriID }}" {{ old('KategoriID', $asset->KategoriID) == $kategori->KategoriID ? 'selected' : '' }}>
                                        {{ $kategori->KodeKategori }} - {{ $kategori->NamaKategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('KategoriID')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Lokasi --}}
                        <div>
                            <label for="LokasiID" class="block text-sm font-medium text-gray-700 mb-1">
                                Lokasi <span class="text-red-500">*</span>
                            </label>
                            <select name="LokasiID" id="LokasiID" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('LokasiID') border-red-500 @enderror"
                                    required>
                                <option value="">Pilih Lokasi</option>
                                @foreach($lokasis as $lokasi)
                                    <option value="{{ $lokasi->LokasiID }}" {{ old('LokasiID', $asset->LokasiID) == $lokasi->LokasiID ? 'selected' : '' }}>
                                        {{ $lokasi->KodeLokasi }} - {{ $lokasi->NamaLokasi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('LokasiID')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Kode Asset --}}
                        <div>
                            <label for="kode_asset" class="block text-sm font-medium text-gray-700 mb-1">
                                Kode Asset <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="kode_asset" id="kode_asset" 
                                   value="{{ old('kode_asset', $asset->kode_asset) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('kode_asset') border-red-500 @enderror font-mono"
                                   placeholder="AST-YYYYMMDD-XXX"
                                   required>
                            @error('kode_asset')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Nama Asset --}}
                        <div>
                            <label for="nama_asset" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Asset <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_asset" id="nama_asset" 
                                   value="{{ old('nama_asset', $asset->nama_asset) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('nama_asset') border-red-500 @enderror"
                                   placeholder="Nama asset"
                                   required>
                            @error('nama_asset')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Merk --}}
                        <div>
                            <label for="merk" class="block text-sm font-medium text-gray-700 mb-1">Merk</label>
                            <input type="text" name="merk" id="merk" 
                                   value="{{ old('merk', $asset->merk) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="Contoh: Dell">
                        </div>
                        
                        {{-- Serial Number --}}
                        <div>
                            <label for="serial_number" class="block text-sm font-medium text-gray-700 mb-1">Serial Number</label>
                            <input type="text" name="serial_number" id="serial_number" 
                                   value="{{ old('serial_number', $asset->serial_number) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="Contoh: SN-2024-001">
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Status & Kondisi Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Status & Kondisi</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Kondisi --}}
                        <div>
                            <label for="kondisi" class="block text-sm font-medium text-gray-700 mb-1">
                                Kondisi <span class="text-red-500">*</span>
                            </label>
                            <select name="kondisi" id="kondisi" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('kondisi') border-red-500 @enderror"
                                    required>
                                <option value="">Pilih Kondisi</option>
                                <option value="baik" {{ old('kondisi', $asset->kondisi) == 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="rusak_ringan" {{ old('kondisi', $asset->kondisi) == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                <option value="rusak_berat" {{ old('kondisi', $asset->kondisi) == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                <option value="tidak_berfungsi" {{ old('kondisi', $asset->kondisi) == 'tidak_berfungsi' ? 'selected' : '' }}>Tidak Berfungsi</option>
                                <option value="hilang" {{ old('kondisi', $asset->kondisi) == 'hilang' ? 'selected' : '' }}>Hilang</option>
                            </select>
                            @error('kondisi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Status Asset --}}
                        <div>
                            <label for="status_asset" class="block text-sm font-medium text-gray-700 mb-1">
                                Status Asset <span class="text-red-500">*</span>
                            </label>
                            <select name="status_asset" id="status_asset" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('status_asset') border-red-500 @enderror"
                                    required>
                                <option value="">Pilih Status</option>
                                <option value="aktif" {{ old('status_asset', $asset->status_asset) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="non_aktif" {{ old('status_asset', $asset->status_asset) == 'non_aktif' ? 'selected' : '' }}>Non Aktif</option>
                                <option value="dipinjam" {{ old('status_asset', $asset->status_asset) == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                <option value="diperbaiki" {{ old('status_asset', $asset->status_asset) == 'diperbaiki' ? 'selected' : '' }}>Diperbaiki</option>
                                <option value="tersimpan" {{ old('status_asset', $asset->status_asset) == 'tersimpan' ? 'selected' : '' }}>Tersimpan</option>
                                <option value="dihapus" {{ old('status_asset', $asset->status_asset) == 'dihapus' ? 'selected' : '' }}>Dihapus</option>
                            </select>
                            @error('status_asset')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Jumlah --}}
                        <div>
                            <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-1">
                                Jumlah <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="jumlah" id="jumlah" 
                                       value="{{ old('jumlah', $asset->jumlah) }}"
                                       min="1"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('jumlah') border-red-500 @enderror"
                                       required>
                                <span class="absolute right-3 top-2.5 text-sm text-gray-500">{{ $asset->satuan ?? 'unit' }}</span>
                            </div>
                            @error('jumlah')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Informasi Perolehan Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Perolehan</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Sumber Perolehan --}}
                        <div>
                            <label for="sumber_perolehan" class="block text-sm font-medium text-gray-700 mb-1">
                                Sumber Perolehan <span class="text-red-500">*</span>
                            </label>
                            <select name="sumber_perolehan" id="sumber_perolehan" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('sumber_perolehan') border-red-500 @enderror"
                                    required>
                                <option value="">Pilih Sumber</option>
                                <option value="pembelian" {{ old('sumber_perolehan', $asset->sumber_perolehan) == 'pembelian' ? 'selected' : '' }}>Pembelian</option>
                                <option value="hibah" {{ old('sumber_perolehan', $asset->sumber_perolehan) == 'hibah' ? 'selected' : '' }}>Hibah</option>
                                <option value="bantuan_pemerintah" {{ old('sumber_perolehan', $asset->sumber_perolehan) == 'bantuan_pemerintah' ? 'selected' : '' }}>Bantuan Pemerintah</option>
                                <option value="produksi_sendiri" {{ old('sumber_perolehan', $asset->sumber_perolehan) == 'produksi_sendiri' ? 'selected' : '' }}>Produksi Sendiri</option>
                                <option value="sewa" {{ old('sumber_perolehan', $asset->sumber_perolehan) == 'sewa' ? 'selected' : '' }}>Sewa</option>
                            </select>
                            @error('sumber_perolehan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Tanggal Perolehan --}}
                        <div>
                            <label for="tanggal_perolehan" class="block text-sm font-medium text-gray-700 mb-1">
                                Tanggal Perolehan <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_perolehan" id="tanggal_perolehan" 
                                   value="{{ old('tanggal_perolehan', $asset->tanggal_perolehan) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('tanggal_perolehan') border-red-500 @enderror"
                                   required>
                            @error('tanggal_perolehan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Vendor --}}
                        <div>
                            <label for="vendor" class="block text-sm font-medium text-gray-700 mb-1">Vendor/Supplier</label>
                            <input type="text" name="vendor" id="vendor" 
                                   value="{{ old('vendor', $asset->vendor) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="Nama vendor">
                        </div>
                        
                        {{-- Nilai Perolehan --}}
                        <div>
                            <label for="nilai_perolehan" class="block text-sm font-medium text-gray-700 mb-1">
                                Nilai Perolehan (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="nilai_perolehan" id="nilai_perolehan" 
                                   value="{{ old('nilai_perolehan', $asset->nilai_perolehan) }}"
                                   step="1000" min="0"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('nilai_perolehan') border-red-500 @enderror"
                                   required>
                            @error('nilai_perolehan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Nilai Residu --}}
                        <div>
                            <label for="nilai_residu" class="block text-sm font-medium text-gray-700 mb-1">Nilai Residu (Rp)</label>
                            <input type="number" name="nilai_residu" id="nilai_residu" 
                                   value="{{ old('nilai_residu', $asset->nilai_residu) }}"
                                   step="1000" min="0"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        
                        {{-- Umur Ekonomis --}}
                        <div>
                            <label for="umur_ekonomis" class="block text-sm font-medium text-gray-700 mb-1">
                                Umur Ekonomis (Tahun) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="umur_ekonomis" id="umur_ekonomis" 
                                   value="{{ old('umur_ekonomis', $asset->umur_ekonomis) }}"
                                   min="1"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('umur_ekonomis') border-red-500 @enderror"
                                   required>
                            @error('umur_ekonomis')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- File Upload Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">File Pendukung</h2>
                </div>
                <div class="p-6 space-y-6">
                    {{-- Current Files --}}
                    @if($asset->gambar_asset || $asset->dokumen_pendukung)
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-sm font-medium text-blue-800 mb-3">File Saat Ini</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if($asset->gambar_asset)
                                    <div class="flex items-center bg-white p-3 rounded-lg border border-blue-200">
                                        <img src="{{ Storage::url($asset->gambar_asset) }}" alt="Asset" class="w-12 h-12 object-cover rounded-lg">
                                        <div class="ml-3 flex-1 min-w-0">
                                            <p class="text-xs font-medium text-gray-900 truncate">{{ basename($asset->gambar_asset) }}</p>
                                            <p class="text-xs text-gray-500">Gambar Asset</p>
                                        </div>
                                    </div>
                                @endif
                                @if($asset->dokumen_pendukung)
                                    <div class="flex items-center bg-white p-3 rounded-lg border border-blue-200">
                                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3 flex-1 min-w-0">
                                            <p class="text-xs font-medium text-gray-900 truncate">{{ basename($asset->dokumen_pendukung) }}</p>
                                            <p class="text-xs text-gray-500">Dokumen Pendukung</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Ganti Gambar Asset
                                <span class="text-xs text-gray-500 ml-1">(opsional)</span>
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-500 transition group">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-purple-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="gambar_asset" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500">
                                            <span>Upload file</span>
                                            <input id="gambar_asset" name="gambar_asset" type="file" class="sr-only" accept="image/jpg,image/jpeg,image/png">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, JPEG (max. 2MB)</p>
                                    <p id="gambar_name" class="text-xs text-gray-600 mt-2 hidden"></p>
                                </div>
                            </div>
                            @error('gambar_asset')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Ganti Dokumen Pendukung
                                <span class="text-xs text-gray-500 ml-1">(opsional)</span>
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-500 transition group">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-purple-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="dokumen_pendukung" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500">
                                            <span>Upload file</span>
                                            <input id="dokumen_pendukung" name="dokumen_pendukung" type="file" class="sr-only" accept=".pdf,.doc,.docx">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX (max. 4MB)</p>
                                    <p id="dokumen_name" class="text-xs text-gray-600 mt-2 hidden"></p>
                                </div>
                            </div>
                            @error('dokumen_pendukung')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Keterangan --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Keterangan</h2>
                </div>
                <div class="p-6">
                    <textarea name="keterangan" id="keterangan" rows="3"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 resize-none"
                              placeholder="Tambahkan catatan atau keterangan tambahan...">{{ old('keterangan', $asset->keterangan) }}</textarea>
                </div>
            </div>
            
            {{-- Form Actions --}}
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('asset.index') }}" 
                   class="px-6 py-2.5 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700 transition inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
    
    {{-- Sidebar --}}
    <div class="space-y-6">
        {{-- Asset Info Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-white border-b border-purple-100">
                <h3 class="text-lg font-semibold text-purple-900">Ringkasan Asset</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-sm text-gray-600">ID Asset</span>
                        <span class="text-sm font-mono font-semibold text-gray-900">#{{ $asset->assetID }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Kode Asset</span>
                        <span class="text-sm font-mono font-semibold text-purple-700">{{ $asset->kode_asset }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Status</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($asset->status_asset == 'aktif') bg-green-100 text-green-800
                            @elseif($asset->status_asset == 'non_aktif') bg-gray-100 text-gray-800
                            @elseif($asset->status_asset == 'dipinjam') bg-blue-100 text-blue-800
                            @elseif($asset->status_asset == 'diperbaiki') bg-yellow-100 text-yellow-800
                            @elseif($asset->status_asset == 'tersimpan') bg-indigo-100 text-indigo-800
                            @elseif($asset->status_asset == 'dihapus') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $asset->status_asset)) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Kondisi</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($asset->kondisi == 'baik') bg-green-100 text-green-800
                            @elseif($asset->kondisi == 'rusak_ringan') bg-yellow-100 text-yellow-800
                            @elseif($asset->kondisi == 'rusak_berat') bg-red-100 text-red-800
                            @elseif($asset->kondisi == 'tidak_berfungsi') bg-orange-100 text-orange-800
                            @elseif($asset->kondisi == 'hilang') bg-gray-100 text-gray-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $asset->kondisi)) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Kategori</span>
                        <span class="text-sm font-medium text-gray-900">{{ $asset->kategori->NamaKategori ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Lokasi</span>
                        <span class="text-sm font-medium text-gray-900 text-right">{{ $asset->lokasi->NamaLokasi ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Jumlah</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $asset->jumlah }} {{ $asset->satuan ?? 'unit' }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Nilai Perolehan</span>
                        <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($asset->nilai_perolehan, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Dibuat</span>
                        <span class="text-sm text-gray-900">{{ $asset->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Diupdate</span>
                        <span class="text-sm text-gray-900">{{ $asset->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Quick Actions Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
            </div>
            <div class="p-4">
                <div class="space-y-2">
                    <a href="{{ route('asset.create') }}" 
                       class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition group">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3 group-hover:bg-green-200 transition">
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
                    <a href="{{ route('asset.show', $asset->assetID) }}" 
                       class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition group">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3 group-hover:bg-blue-200 transition">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Lihat Detail</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="{{ route('asset.index') }}" 
                       class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition group">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mr-3 group-hover:bg-purple-200 transition">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Lihat Semua Asset</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInputs = [
            { input: 'gambar_asset', preview: 'gambar_name' },
            { input: 'dokumen_pendukung', preview: 'dokumen_name' }
        ];
        
        fileInputs.forEach(({ input, preview }) => {
            const element = document.getElementById(input);
            const previewElement = document.getElementById(preview);
            if (element) {
                element.addEventListener('change', function(e) {
                    const fileName = e.target.files[0]?.name;
                    if (fileName) {
                        previewElement.textContent = `📎 ${fileName}`;
                        previewElement.classList.remove('hidden');
                    } else {
                        previewElement.classList.add('hidden');
                    }
                });
            }
        });
        
        // Unsaved changes warning
        let hasChanges = false;
        const form = document.getElementById('editForm');
        const inputs = form.querySelectorAll('input:not([type="file"]):not([name="InstansiID"]), select, textarea');
        const initialValues = new Map();
        inputs.forEach(input => initialValues.set(input, input.value));
        
        const checkChanges = () => {
            hasChanges = [...inputs].some(input => input.value !== initialValues.get(input));
        };
        
        inputs.forEach(input => {
            input.addEventListener('change', checkChanges);
            input.addEventListener('keyup', checkChanges);
        });
        
        window.addEventListener('beforeunload', (e) => {
            if (hasChanges) {
                e.preventDefault();
                e.returnValue = 'Perubahan belum disimpan. Yakin ingin meninggalkan halaman?';
            }
        });
        
        form.addEventListener('submit', () => {
            hasChanges = false;
        });
    });
</script>
@endpush