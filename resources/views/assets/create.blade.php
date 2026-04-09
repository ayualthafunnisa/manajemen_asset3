@extends('layouts.app')

@section('title', 'Tambah Asset Baru - Jobie')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-neutral-900">Tambah Asset Baru</h1>
        <p class="mt-1 text-sm text-neutral-500">Isi form berikut untuk menambahkan asset baru</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('asset.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-neutral-300 rounded-lg text-sm font-medium text-neutral-700 bg-white hover:bg-neutral-50 transition-all duration-200 shadow-sm hover:shadow">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="w-full">
    <div class="bg-white rounded-xl shadow-card border border-neutral-200 overflow-hidden hover:shadow-card-hover transition-shadow duration-300">
        {{-- Header Form --}}
        <div class="px-8 py-6 border-b border-neutral-200 bg-gradient-to-r from-neutral-50 to-white">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-primary-100 rounded-lg">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900">Form Asset</h2>
                    <p class="text-sm text-neutral-500">Lengkapi data asset dengan benar</p>
                </div>
            </div>
        </div>

        {{-- Form Body --}}
        <div class="px-8 py-8">
            <form action="{{ route('asset.store') }}" method="POST" id="assetForm" enctype="multipart/form-data" class="space-y-8">
                @csrf

                {{-- Info Instansi --}}
                <div class="bg-gradient-to-r from-primary-50 to-secondary-50 border border-primary-200 rounded-xl p-5">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <div class="p-2 bg-white rounded-lg shadow-sm">
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-primary-800">Informasi Instansi</p>
                            <p class="text-sm text-primary-700 mt-1">
                                Asset akan ditambahkan ke instansi:
                                <strong class="font-semibold">{{ Auth::user()->instansi->NamaSekolah ?? '-' }}</strong>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Informasi Dasar --}}
                <div class="border-b border-neutral-200 pb-6">
                    <h3 class="text-md font-semibold text-neutral-900 mb-4 flex items-center gap-2">
                        <span class="w-1 h-6 bg-primary-500 rounded-full"></span>
                        Informasi Dasar
                    </h3>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        {{-- Kategori --}}
                        <div class="space-y-2">
                            <label for="KategoriID" class="block text-sm font-semibold text-neutral-700">
                                Kategori Asset <span class="text-danger-500">*</span>
                            </label>
                            <select name="KategoriID" id="KategoriID" 
                                    class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('KategoriID') border-danger-500 @enderror"
                                    required>
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->KategoriID }}" {{ old('KategoriID') == $kategori->KategoriID ? 'selected' : '' }}>
                                        {{ $kategori->KodeKategori }} - {{ $kategori->NamaKategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('KategoriID')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Lokasi --}}
                        <div class="space-y-2">
                            <label for="LokasiID" class="block text-sm font-semibold text-neutral-700">
                                Lokasi Asset <span class="text-danger-500">*</span>
                            </label>
                            <select name="LokasiID" id="LokasiID" 
                                    class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('LokasiID') border-danger-500 @enderror"
                                    required>
                                <option value="">Pilih Lokasi</option>
                                @foreach($lokasis as $lokasi)
                                    <option value="{{ $lokasi->LokasiID }}" {{ old('LokasiID') == $lokasi->LokasiID ? 'selected' : '' }}>
                                        {{ $lokasi->KodeLokasi }} - {{ $lokasi->NamaLokasi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('LokasiID')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kode Asset --}}
                        <div class="space-y-2">
                            <label for="kode_asset" class="block text-sm font-semibold text-neutral-700">
                                Kode Asset <span class="text-danger-500">*</span>
                            </label>
                            <div class="flex gap-2">
                                <input type="text" name="kode_asset" id="kode_asset" 
                                       value="{{ old('kode_asset') }}"
                                       class="flex-1 px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('kode_asset') border-danger-500 @enderror"
                                       placeholder="Contoh: AST-20240101-001"
                                       required>
                                <button type="button" onclick="generateKodeAsset()"
                                        class="px-4 py-3 bg-neutral-100 border-2 border-neutral-200 rounded-lg hover:bg-neutral-200 hover:border-primary-300 transition-all duration-200 group"
                                        title="Generate kode otomatis">
                                    <svg class="w-5 h-5 text-neutral-600 group-hover:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-xs text-neutral-500">Kode asset harus unik. Klik tombol refresh untuk generate otomatis</p>
                            @error('kode_asset')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nama Asset --}}
                        <div class="space-y-2">
                            <label for="nama_asset" class="block text-sm font-semibold text-neutral-700">
                                Nama Asset <span class="text-danger-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <input type="text" name="nama_asset" id="nama_asset" 
                                       value="{{ old('nama_asset') }}"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('nama_asset') border-danger-500 @enderror"
                                       placeholder="Contoh: Laptop Dell XPS 13"
                                       required>
                            </div>
                            @error('nama_asset')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Identitas Asset --}}
                <div class="border-b border-neutral-200 pb-6">
                    <h3 class="text-md font-semibold text-neutral-900 mb-4 flex items-center gap-2">
                        <span class="w-1 h-6 bg-primary-500 rounded-full"></span>
                        Identitas Asset
                    </h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label for="merk" class="block text-sm font-semibold text-neutral-700">Merk</label>
                            <input type="text" name="merk" id="merk" 
                                   value="{{ old('merk') }}"
                                   class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200"
                                   placeholder="Contoh: Dell">
                        </div>

                        <div class="space-y-2">
                            <label for="serial_number" class="block text-sm font-semibold text-neutral-700">Serial Number</label>
                            <input type="text" name="serial_number" id="serial_number" 
                                   value="{{ old('serial_number') }}"
                                   class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200"
                                   placeholder="Contoh: SN-2024-001">
                        </div>
                    </div>
                </div>

                {{-- Informasi Perolehan --}}
                <div class="border-b border-neutral-200 pb-6">
                    <h3 class="text-md font-semibold text-neutral-900 mb-4 flex items-center gap-2">
                        <span class="w-1 h-6 bg-primary-500 rounded-full"></span>
                        Informasi Perolehan
                    </h3>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="space-y-2">
                            <label for="sumber_perolehan" class="block text-sm font-semibold text-neutral-700">
                                Sumber Perolehan <span class="text-danger-500">*</span>
                            </label>
                            <select name="sumber_perolehan" id="sumber_perolehan" 
                                    class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('sumber_perolehan') border-danger-500 @enderror"
                                    required>
                                <option value="">Pilih Sumber</option>
                                <option value="pembelian" {{ old('sumber_perolehan') == 'pembelian' ? 'selected' : '' }}>Pembelian</option>
                                <option value="hibah" {{ old('sumber_perolehan') == 'hibah' ? 'selected' : '' }}>Hibah</option>
                                <option value="bantuan_pemerintah" {{ old('sumber_perolehan') == 'bantuan_pemerintah' ? 'selected' : '' }}>Bantuan Pemerintah</option>
                                <option value="produksi_sendiri" {{ old('sumber_perolehan') == 'produksi_sendiri' ? 'selected' : '' }}>Produksi Sendiri</option>
                                <option value="sewa" {{ old('sumber_perolehan') == 'sewa' ? 'selected' : '' }}>Sewa</option>
                            </select>
                            @error('sumber_perolehan')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="tanggal_perolehan" class="block text-sm font-semibold text-neutral-700">
                                Tanggal Perolehan <span class="text-danger-500">*</span>
                            </label>
                            <input type="date" name="tanggal_perolehan" id="tanggal_perolehan" 
                                   value="{{ old('tanggal_perolehan') }}"
                                   class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('tanggal_perolehan') border-danger-500 @enderror"
                                   required>
                            @error('tanggal_perolehan')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="vendor" class="block text-sm font-semibold text-neutral-700">Vendor/Supplier</label>
                            <input type="text" name="vendor" id="vendor" 
                                   value="{{ old('vendor') }}"
                                   class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200"
                                   placeholder="Nama vendor">
                        </div>

                        <div class="space-y-2">
                            <label for="nilai_perolehan" class="block text-sm font-semibold text-neutral-700">
                                Nilai Perolehan (Rp) <span class="text-danger-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-neutral-500 text-sm pointer-events-none">Rp</span>
                                <input type="number" name="nilai_perolehan" id="nilai_perolehan" 
                                       value="{{ old('nilai_perolehan') }}"
                                       step="1000" min="0"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('nilai_perolehan') border-danger-500 @enderror"
                                       placeholder="0"
                                       required>
                            </div>
                            @error('nilai_perolehan')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="nilai_residu" class="block text-sm font-semibold text-neutral-700">Nilai Residu (Rp)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-neutral-500 text-sm pointer-events-none">Rp</span>
                                <input type="number" name="nilai_residu" id="nilai_residu" 
                                       value="{{ old('nilai_residu', 0) }}"
                                       step="1000" min="0"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200"
                                       placeholder="0">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="umur_ekonomis" class="block text-sm font-semibold text-neutral-700">
                                Umur Ekonomis (Tahun) <span class="text-danger-500">*</span>
                            </label>
                            <input type="number" name="umur_ekonomis" id="umur_ekonomis" 
                                   value="{{ old('umur_ekonomis') }}"
                                   min="1"
                                   class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('umur_ekonomis') border-danger-500 @enderror"
                                   placeholder="Contoh: 5"
                                   required>
                            @error('umur_ekonomis')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Status & Kondisi --}}
                <div class="border-b border-neutral-200 pb-6">
                    <h3 class="text-md font-semibold text-neutral-900 mb-4 flex items-center gap-2">
                        <span class="w-1 h-6 bg-primary-500 rounded-full"></span>
                        Status & Kondisi
                    </h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label for="kondisi" class="block text-sm font-semibold text-neutral-700">
                                Kondisi <span class="text-danger-500">*</span>
                            </label>
                            <select name="kondisi" id="kondisi" 
                                    class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('kondisi') border-danger-500 @enderror"
                                    required>
                                <option value="">Pilih Kondisi</option>
                                <option value="baik" {{ old('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="rusak_ringan" {{ old('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                <option value="rusak_berat" {{ old('kondisi') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                <option value="tidak_berfungsi" {{ old('kondisi') == 'tidak_berfungsi' ? 'selected' : '' }}>Tidak Berfungsi</option>
                                <option value="hilang" {{ old('kondisi') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                            </select>
                            @error('kondisi')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="status_asset" class="block text-sm font-semibold text-neutral-700">
                                Status Asset <span class="text-danger-500">*</span>
                            </label>
                            <select name="status_asset" id="status_asset" 
                                    class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('status_asset') border-danger-500 @enderror"
                                    required>
                                <option value="">Pilih Status</option>
                                <option value="aktif" {{ old('status_asset') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="non_aktif" {{ old('status_asset') == 'non_aktif' ? 'selected' : '' }}>Non Aktif</option>
                                <option value="dipinjam" {{ old('status_asset') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                <option value="diperbaiki" {{ old('status_asset') == 'diperbaiki' ? 'selected' : '' }}>Diperbaiki</option>
                                <option value="tersimpan" {{ old('status_asset') == 'tersimpan' ? 'selected' : '' }}>Tersimpan</option>
                            </select>
                            @error('status_asset')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Jumlah & Satuan --}}
                <div class="border-b border-neutral-200 pb-6">
                    <h3 class="text-md font-semibold text-neutral-900 mb-4 flex items-center gap-2">
                        <span class="w-1 h-6 bg-primary-500 rounded-full"></span>
                        Jumlah & Satuan
                    </h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label for="jumlah" class="block text-sm font-semibold text-neutral-700">
                                Jumlah <span class="text-danger-500">*</span>
                            </label>
                            <input type="number" name="jumlah" id="jumlah" 
                                   value="{{ old('jumlah', 1) }}"
                                   min="1"
                                   class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('jumlah') border-danger-500 @enderror"
                                   required>
                            @error('jumlah')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="satuan" class="block text-sm font-semibold text-neutral-700">Satuan</label>
                            <input type="text" name="satuan" id="satuan" 
                                   value="{{ old('satuan', 'unit') }}"
                                   class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200"
                                   placeholder="Contoh: unit, buah, set">
                        </div>
                    </div>
                </div>

                {{-- File Pendukung --}}
                <div class="border-b border-neutral-200 pb-6">
                    <h3 class="text-md font-semibold text-neutral-900 mb-4 flex items-center gap-2">
                        <span class="w-1 h-6 bg-primary-500 rounded-full"></span>
                        File Pendukung
                    </h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-neutral-700">Gambar Asset</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-neutral-200 border-dashed rounded-lg hover:border-primary-400 hover:bg-primary-50 transition-all duration-200">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <div class="flex text-sm text-neutral-600 justify-center">
                                        <label for="gambar_asset" class="cursor-pointer font-medium text-primary-600 hover:text-primary-500">
                                            <span>Upload file</span>
                                            <input id="gambar_asset" name="gambar_asset" type="file" class="sr-only" accept="image/jpg,image/jpeg,image/png">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-neutral-500">PNG, JPG, JPEG maks. 2MB</p>
                                    <p id="gambar_name" class="text-xs text-primary-600 mt-2 hidden"></p>
                                </div>
                            </div>
                            @error('gambar_asset')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-neutral-700">Dokumen Pendukung</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-neutral-200 border-dashed rounded-lg hover:border-primary-400 hover:bg-primary-50 transition-all duration-200">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <div class="flex text-sm text-neutral-600 justify-center">
                                        <label for="dokumen_pendukung" class="cursor-pointer font-medium text-primary-600 hover:text-primary-500">
                                            <span>Upload file</span>
                                            <input id="dokumen_pendukung" name="dokumen_pendukung" type="file" class="sr-only" accept=".pdf,.doc,.docx">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-neutral-500">PDF, DOC, DOCX maks. 4MB</p>
                                    <p id="dokumen_name" class="text-xs text-primary-600 mt-2 hidden"></p>
                                </div>
                            </div>
                            @error('dokumen_pendukung')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Keterangan --}}
                <div class="space-y-2">
                    <label for="keterangan" class="block text-sm font-semibold text-neutral-700">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                              class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 resize-y"
                              placeholder="Tambahkan keterangan jika diperlukan">{{ old('keterangan') }}</textarea>
                </div>

                {{-- Alert Messages --}}
                @if(session('success'))
                <div class="rounded-lg bg-success-50 border border-success-200 p-4 animate-fade-in">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-success-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm text-success-700">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="rounded-lg bg-danger-50 border border-danger-200 p-4 animate-fade-in">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-danger-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm text-danger-700">{{ session('error') }}</p>
                    </div>
                </div>
                @endif

                {{-- Form Actions --}}
                <div class="pt-6 border-t-2 border-neutral-100">
                    <div class="flex flex-col sm:flex-row justify-end gap-3">
                        <a href="{{ route('asset.index') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 border-2 border-neutral-300 rounded-lg font-medium text-neutral-700 bg-white hover:bg-neutral-50 hover:border-neutral-400 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Batal
                        </a>
                        <button type="button" 
                                onclick="confirmSubmit()"
                                class="inline-flex items-center justify-center px-6 py-3 bg-gradient-primary rounded-lg font-medium text-white hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            Simpan Asset
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.generateKodeAsset = function () {
        const date = new Date();
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
        document.getElementById('kode_asset').value = `AST-${year}${month}${day}-${random}`;
    };

    document.getElementById('gambar_asset').addEventListener('change', function (e) {
        const el = document.getElementById('gambar_name');
        const name = e.target.files[0]?.name;
        el.textContent = name ? `File: ${name}` : '';
        el.classList.toggle('hidden', !name);
    });

    document.getElementById('dokumen_pendukung').addEventListener('change', function (e) {
        const el = document.getElementById('dokumen_name');
        const name = e.target.files[0]?.name;
        el.textContent = name ? `File: ${name}` : '';
        el.classList.toggle('hidden', !name);
    });

    // Real-time validation
    const requiredInputs = document.querySelectorAll('[required]');
    requiredInputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('border-danger-500');
                this.classList.add('border-neutral-200');
            }
        });
    });
});

function confirmSubmit() {
    const form = document.getElementById('assetForm');
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    let firstInvalid = null;
    let errorMessages = [];
    
    requiredFields.forEach(field => {
        if (field.type === 'file') {
            if (!field.files || field.files.length === 0) {
                isValid = false;
                field.closest('.border-dashed')?.classList.add('border-danger-500');
                const label = document.querySelector(`label[for="${field.id}"]`);
                const fieldName = label ? label.innerText.replace('*', '').trim() : field.name;
                errorMessages.push(`${fieldName} wajib diunggah`);
                if (!firstInvalid) firstInvalid = field;
            }
        } else if (!field.value.trim()) {
            isValid = false;
            field.classList.add('border-danger-500');
            field.classList.remove('border-neutral-200');
            const label = document.querySelector(`label[for="${field.id}"]`);
            const fieldName = label ? label.innerText.replace('*', '').trim() : field.name;
            errorMessages.push(`${fieldName} wajib diisi`);
            if (!firstInvalid) firstInvalid = field;
        } else {
            field.classList.remove('border-danger-500');
            field.classList.add('border-neutral-200');
        }
    });
    
    // Validasi nilai perolehan > 0
    const nilaiPerolehan = document.getElementById('nilai_perolehan');
    if (nilaiPerolehan.value && parseFloat(nilaiPerolehan.value) <= 0) {
        isValid = false;
        nilaiPerolehan.classList.add('border-danger-500');
        errorMessages.push('Nilai perolehan harus lebih dari 0');
        if (!firstInvalid) firstInvalid = nilaiPerolehan;
    }
    
    if (!isValid) {
        let errorMessage = 'Mohon lengkapi data berikut:\n\n';
        errorMessages.forEach(msg => {
            errorMessage += `• ${msg}\n`;
        });
        alert(errorMessage);
        
        if (firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalid.focus();
        }
        return;
    }
    
    if (confirm('Apakah Anda yakin ingin menyimpan data asset ini?')) {
        form.submit();
    }
}
</script>
@endpush