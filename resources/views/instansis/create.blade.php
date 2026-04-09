@extends('layouts.app')

@section('title', 'Tambah Instansi - Jobie')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-neutral-900">Tambah Instansi Baru</h1>
        <p class="mt-1 text-sm text-neutral-500">Isi form berikut untuk menambahkan instansi/sekolah baru</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('instansi.index') }}" 
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900">Form Instansi</h2>
                    <p class="text-sm text-neutral-500">Lengkapi data instansi/sekolah dengan benar</p>
                </div>
            </div>
        </div>

        {{-- Form Body --}}
        <div class="px-8 py-8">
            <form action="{{ route('instansi.store') }}" method="POST" enctype="multipart/form-data" id="instansiForm" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Left Column --}}
                    <div class="space-y-6">
                        {{-- Kode Instansi --}}
                        <div class="space-y-2">
                            <label for="KodeInstansi" class="block text-sm font-semibold text-neutral-700">
                                Kode Instansi <span class="text-danger-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                    </svg>
                                </div>
                                <input type="text" name="KodeInstansi" id="KodeInstansi"
                                       value="{{ old('KodeInstansi') }}"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('KodeInstansi') border-danger-500 @enderror"
                                       placeholder="Contoh: SKL001"
                                       required>
                            </div>
                            @error('KodeInstansi')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- NPSN --}}
                        <div class="space-y-2">
                            <label for="NPSN" class="block text-sm font-semibold text-neutral-700">
                                NPSN <span class="text-danger-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <input type="text" name="NPSN" id="NPSN"
                                       value="{{ old('NPSN') }}"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('NPSN') border-danger-500 @enderror"
                                       placeholder="Nomor Pokok Sekolah Nasional"
                                       required>
                            </div>
                            @error('NPSN')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nama Sekolah --}}
                        <div class="space-y-2">
                            <label for="NamaSekolah" class="block text-sm font-semibold text-neutral-700">
                                Nama Sekolah <span class="text-danger-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <input type="text" name="NamaSekolah" id="NamaSekolah"
                                       value="{{ old('NamaSekolah') }}"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('NamaSekolah') border-danger-500 @enderror"
                                       placeholder="Nama lengkap sekolah"
                                       required>
                            </div>
                            @error('NamaSekolah')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jenjang Sekolah --}}
                        <div class="space-y-2">
                            <label for="JenjangSekolah" class="block text-sm font-semibold text-neutral-700">
                                Jenjang Sekolah <span class="text-danger-500">*</span>
                            </label>
                            <select name="JenjangSekolah" id="JenjangSekolah"
                                    class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('JenjangSekolah') border-danger-500 @enderror"
                                    required>
                                <option value="">Pilih Jenjang</option>
                                @foreach($jenjangOptions as $jenjang)
                                    <option value="{{ $jenjang }}" {{ old('JenjangSekolah') == $jenjang ? 'selected' : '' }}>
                                        {{ $jenjang }}
                                    </option>
                                @endforeach
                            </select>
                            @error('JenjangSekolah')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="space-y-6">
                        {{-- Email --}}
                        <div class="space-y-2">
                            <label for="Email" class="block text-sm font-semibold text-neutral-700">
                                Email <span class="text-danger-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <input type="email" name="Email" id="Email"
                                       value="{{ old('Email') }}"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('Email') border-danger-500 @enderror"
                                       placeholder="email@sekolah.example"
                                       required>
                            </div>
                            @error('Email')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kode Pos --}}
                        <div class="space-y-2">
                            <label for="KodePos" class="block text-sm font-semibold text-neutral-700">
                                Kode Pos <span class="text-danger-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <input type="text" name="KodePos" id="KodePos"
                                       value="{{ old('KodePos') }}"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('KodePos') border-danger-500 @enderror"
                                       placeholder="Contoh: 12345"
                                       required>
                            </div>
                            @error('KodePos')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nama Kepala Sekolah --}}
                        <div class="space-y-2">
                            <label for="NamaKepalaSekolah" class="block text-sm font-semibold text-neutral-700">
                                Nama Kepala Sekolah <span class="text-danger-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <input type="text" name="NamaKepalaSekolah" id="NamaKepalaSekolah"
                                       value="{{ old('NamaKepalaSekolah') }}"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('NamaKepalaSekolah') border-danger-500 @enderror"
                                       placeholder="Nama lengkap kepala sekolah"
                                       required>
                            </div>
                            @error('NamaKepalaSekolah')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- NIP Kepala Sekolah --}}
                        <div class="space-y-2">
                            <label for="NIPKepalaSekolah" class="block text-sm font-semibold text-neutral-700">
                                NIP Kepala Sekolah <span class="text-danger-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                    </svg>
                                </div>
                                <input type="text" name="NIPKepalaSekolah" id="NIPKepalaSekolah"
                                       value="{{ old('NIPKepalaSekolah') }}"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('NIPKepalaSekolah') border-danger-500 @enderror"
                                       placeholder="Nomor Induk Pegawai"
                                       required>
                            </div>
                            @error('NIPKepalaSekolah')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Alamat (Wilayah) --}}
                <div class="space-y-6">
                    <h3 class="text-md font-semibold text-neutral-900 flex items-center gap-2">
                        <span class="w-1 h-6 bg-primary-500 rounded-full"></span>
                        Alamat Wilayah
                    </h3>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        {{-- Provinsi --}}
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-neutral-700">Provinsi</label>
                            <select id="provinsi" name="provinsi_code"
                                    class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                                <option value="">Pilih Provinsi</option>
                                @foreach($provinsis as $prov)
                                    <option value="{{ $prov->code }}">{{ $prov->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Kota --}}
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-neutral-700">Kota / Kabupaten</label>
                            <select id="kota" name="kota_code"
                                    class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                                <option value="">Pilih Kota</option>
                            </select>
                        </div>

                        {{-- Kecamatan --}}
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-neutral-700">Kecamatan</label>
                            <select id="kecamatan" name="kecamatan_code"
                                    class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>

                        {{-- Kelurahan --}}
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-neutral-700">Kelurahan</label>
                            <select id="kelurahan" name="kelurahan_code"
                                    class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                                <option value="">Pilih Kelurahan</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Tanggal Berdiri & Status --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label for="TanggalBerdiri" class="block text-sm font-semibold text-neutral-700">
                            Tanggal Berdiri <span class="text-danger-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <input type="date" name="TanggalBerdiri" id="TanggalBerdiri"
                                   value="{{ old('TanggalBerdiri') }}"
                                   class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('TanggalBerdiri') border-danger-500 @enderror"
                                   required>
                        </div>
                        @error('TanggalBerdiri')
                            <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="Status" class="block text-sm font-semibold text-neutral-700">
                            Status <span class="text-danger-500">*</span>
                        </label>
                        <select name="Status" id="Status"
                                class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('Status') border-danger-500 @enderror"
                                required>
                            <option value="">Pilih Status</option>
                            @foreach($statusOptions as $status)
                                <option value="{{ $status }}" {{ old('Status') == $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                        @error('Status')
                            <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Logo --}}
                <div class="space-y-2">
                    <label for="Logo" class="block text-sm font-semibold text-neutral-700">Logo Sekolah</label>
                    <div class="flex items-center gap-6">
                        <div class="relative">
                            <input type="file" name="Logo" id="Logo" accept="image/*" class="hidden" onchange="previewLogo(event)">
                            <label for="Logo" 
                                   class="cursor-pointer flex flex-col items-center justify-center w-32 h-32 border-2 border-dashed border-neutral-300 rounded-xl hover:border-primary-500 hover:bg-primary-50 transition-all duration-200">
                                <svg class="w-8 h-8 text-neutral-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-xs text-neutral-500">Upload Logo</span>
                            </label>
                        </div>
                        <div id="logoPreview" class="hidden">
                            <img id="previewImage" class="w-32 h-32 rounded-xl object-cover border-2 border-primary-200 shadow-sm">
                        </div>
                    </div>
                    <p class="text-xs text-neutral-500 mt-1">Format: JPG, PNG, GIF (maks. 2MB)</p>
                    @error('Logo')
                        <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                    @enderror
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
                        <a href="{{ route('instansi.index') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 border-2 border-neutral-300 rounded-lg font-medium text-neutral-700 bg-white hover:bg-neutral-50 hover:border-neutral-400 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Batal
                        </a>
                        <button type="submit"
                                class="inline-flex items-center justify-center px-6 py-3 bg-gradient-primary rounded-lg font-medium text-white hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            Simpan Instansi
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
function previewLogo(event) {
    const input = event.target;
    const preview = document.getElementById('logoPreview');
    const previewImage = document.getElementById('previewImage');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.classList.remove('hidden');
            previewImage.src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('instansiForm');
    
    // Auto-format NPSN (hanya angka)
    const npsnInput = document.getElementById('NPSN');
    npsnInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    // Auto-format KodePos (hanya angka)
    const kodePosInput = document.getElementById('KodePos');
    kodePosInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    // Set max date for Tanggal Berdiri to today
    const tanggalInput = document.getElementById('TanggalBerdiri');
    const today = new Date().toISOString().split('T')[0];
    tanggalInput.max = today;
    
    // Real-time validation
    const requiredInputs = document.querySelectorAll('[required]');
    requiredInputs.forEach(input => {
        if (input.type !== 'file') {
            input.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('border-danger-500');
                    this.classList.add('border-neutral-200');
                }
            });
        }
    });

    // Wilayah dropdown chaining
    const provinsi = document.getElementById('provinsi');
    const kota = document.getElementById('kota');
    const kecamatan = document.getElementById('kecamatan');
    const kelurahan = document.getElementById('kelurahan');

    provinsi.addEventListener('change', function () {
        fetch(`/api/indonesia/cities/${this.value}`)
            .then(res => res.json())
            .then(data => {
                kota.innerHTML = '<option value="">Pilih Kota</option>';
                kecamatan.innerHTML = '<option value="">Pilih Kecamatan</option>';
                kelurahan.innerHTML = '<option value="">Pilih Kelurahan</option>';

                data.forEach(item => {
                    kota.innerHTML += `<option value="${item.code}">${item.name}</option>`;
                });
            });
    });

    kota.addEventListener('change', function () {
        fetch(`/api/indonesia/districts/${this.value}`)
            .then(res => res.json())
            .then(data => {
                kecamatan.innerHTML = '<option value="">Pilih Kecamatan</option>';
                kelurahan.innerHTML = '<option value="">Pilih Kelurahan</option>';

                data.forEach(item => {
                    kecamatan.innerHTML += `<option value="${item.code}">${item.name}</option>`;
                });
            });
    });

    kecamatan.addEventListener('change', function () {
        fetch(`/api/indonesia/villages/${this.value}`)
            .then(res => res.json())
            .then(data => {
                kelurahan.innerHTML = '<option value="">Pilih Kelurahan</option>';

                data.forEach(item => {
                    kelurahan.innerHTML += `<option value="${item.code}">${item.name}</option>`;
                });
            });
    });
});
</script>
@endpush