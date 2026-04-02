@extends('layouts.app')

@section('title', 'Edit Instansi - Jobie')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-white shadow-sm border border-gray-200 px-4 py-3 rounded-lg">
        <li class="breadcrumb-item">
            <a href="" class="text-gray-600 hover:text-purple-600 transition duration-150">
                Dashboard
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('instansi.index') }}" class="text-gray-600 hover:text-purple-600 transition duration-150">
                Instansi
            </a>
        </li>
        <li class="breadcrumb-item active text-gray-800" aria-current="page">
            Edit Instansi #{{ $instansi->InstansiID }}
        </li>
    </ol>
</nav>
@endsection

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Instansi</h1>
        <p class="mt-1 text-gray-600">Perbarui informasi instansi #{{ $instansi->InstansiID }}</p>
    </div>
    <div class="mt-4 sm:mt-0 flex space-x-2">
        <a href="{{ route('instansi.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 active:bg-gray-100 focus:outline-none focus:border-purple-500 focus:ring focus:ring-purple-300 transition duration-150 ease-in-out">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
        <form action="{{ route('instansi.destroy', $instansi->InstansiID) }}" method="POST" id="deleteForm">
            @csrf
            @method('DELETE')
            <button type="button" 
                    onclick="confirmDelete()"
                    class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md font-semibold text-xs text-red-700 uppercase tracking-widest hover:bg-red-50 active:bg-red-100 focus:outline-none focus:border-red-500 focus:ring focus:ring-red-300 transition duration-150 ease-in-out">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Hapus
            </button>
        </form>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Section -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="p-6 md:p-8">
                    <form action="{{ route('instansi.update', $instansi->InstansiID) }}" method="POST" enctype="multipart/form-data" id="editForm" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Kode Instansi -->
                            <div class="space-y-2">
                                <label for="KodeInstansi" class="block text-sm font-medium text-gray-700">
                                    Kode Instansi <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="KodeInstansi" 
                                       id="KodeInstansi" 
                                       value="{{ old('KodeInstansi', $instansi->KodeInstansi) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-150 ease-in-out @error('KodeInstansi') border-red-500 @enderror"
                                       placeholder="Contoh: SKL001"
                                       required>
                                @error('KodeInstansi')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- NPSN -->
                            <div class="space-y-2">
                                <label for="NPSN" class="block text-sm font-medium text-gray-700">
                                    NPSN <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="NPSN" 
                                       id="NPSN" 
                                       value="{{ old('NPSN', $instansi->NPSN) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-150 ease-in-out @error('NPSN') border-red-500 @enderror"
                                       placeholder="Nomor Pokok Sekolah Nasional"
                                       required>
                                @error('NPSN')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Nama Sekolah -->
                        <div class="space-y-2">
                            <label for="NamaSekolah" class="block text-sm font-medium text-gray-700">
                                Nama Sekolah <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="NamaSekolah" 
                                   id="NamaSekolah" 
                                   value="{{ old('NamaSekolah', $instansi->NamaSekolah) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-150 ease-in-out @error('NamaSekolah') border-red-500 @enderror"
                                   placeholder="Nama lengkap sekolah"
                                   required>
                            @error('NamaSekolah')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Jenjang Sekolah -->
                            <div class="space-y-2">
                                <label for="JenjangSekolah" class="block text-sm font-medium text-gray-700">
                                    Jenjang Sekolah <span class="text-red-500">*</span>
                                </label>
                                <select name="JenjangSekolah" 
                                        id="JenjangSekolah" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-150 ease-in-out @error('JenjangSekolah') border-red-500 @enderror"
                                        required>
                                    <option value="">Pilih Jenjang</option>
                                    @foreach($jenjangOptions as $jenjang)
                                        <option value="{{ $jenjang }}" {{ old('JenjangSekolah', $instansi->JenjangSekolah) == $jenjang ? 'selected' : '' }}>
                                            {{ $jenjang }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('JenjangSekolah')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Kode Pos -->
                            <div class="space-y-2">
                                <label for="KodePos" class="block text-sm font-medium text-gray-700">
                                    Kode Pos <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="KodePos" 
                                       id="KodePos" 
                                       value="{{ old('KodePos', $instansi->KodePos) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-150 ease-in-out @error('KodePos') border-red-500 @enderror"
                                       placeholder="Contoh: 12345"
                                       required>
                                @error('KodePos')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Email -->
                        <div class="space-y-2">
                            <label for="Email" class="block text-sm font-medium text-gray-700">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   name="Email" 
                                   id="Email" 
                                   value="{{ old('Email', $instansi->Email) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-150 ease-in-out @error('Email') border-red-500 @enderror"
                                   placeholder="email@sekolah.example"
                                   required>
                            @error('Email')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Logo -->
                        <div class="space-y-2">
                            <label for="Logo" class="block text-sm font-medium text-gray-700">
                                Logo Sekolah
                            </label>
                            <div class="flex items-center space-x-4">
                                <div class="relative">
                                    <input type="file" 
                                           name="Logo" 
                                           id="Logo" 
                                           accept="image/*"
                                           class="hidden"
                                           onchange="previewLogo(event)">
                                    <label for="Logo" 
                                           class="cursor-pointer flex flex-col items-center justify-center w-32 h-32 border-2 border-dashed border-gray-300 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition duration-150">
                                        <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-500">Ubah Logo</span>
                                    </label>
                                </div>
                                <div id="logoPreview">
                                    <img id="previewImage" 
                                         src="{{ $instansi->Logo ? asset('storage/' . $instansi->Logo) : asset('images/default-logo.png') }}" 
                                         class="w-32 h-32 rounded-lg object-cover border border-gray-200">
                                    <p class="text-xs text-gray-500 mt-1">Logo saat ini</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, GIF (maks. 2MB)</p>
                            @error('Logo')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Kepala Sekolah -->
                            <div class="space-y-2">
                                <label for="NamaKepalaSekolah" class="block text-sm font-medium text-gray-700">
                                    Nama Kepala Sekolah <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="NamaKepalaSekolah" 
                                       id="NamaKepalaSekolah" 
                                       value="{{ old('NamaKepalaSekolah', $instansi->NamaKepalaSekolah) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-150 ease-in-out @error('NamaKepalaSekolah') border-red-500 @enderror"
                                       placeholder="Nama lengkap kepala sekolah"
                                       required>
                                @error('NamaKepalaSekolah')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- NIP Kepala Sekolah -->
                            <div class="space-y-2">
                                <label for="NIPKepalaSekolah" class="block text-sm font-medium text-gray-700">
                                    NIP Kepala Sekolah <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="NIPKepalaSekolah" 
                                       id="NIPKepalaSekolah" 
                                       value="{{ old('NIPKepalaSekolah', $instansi->NIPKepalaSekolah) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-150 ease-in-out @error('NIPKepalaSekolah') border-red-500 @enderror"
                                       placeholder="Nomor Induk Pegawai"
                                       required>
                                @error('NIPKepalaSekolah')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Tanggal Berdiri -->
                            <div class="space-y-2">
                                <label for="TanggalBerdiri" class="block text-sm font-medium text-gray-700">
                                    Tanggal Berdiri <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       name="TanggalBerdiri" 
                                       id="TanggalBerdiri" 
                                       value="{{ old('TanggalBerdiri', $instansi->TanggalBerdiri->format('Y-m-d')) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-150 ease-in-out @error('TanggalBerdiri') border-red-500 @enderror"
                                       required>
                                @error('TanggalBerdiri')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Status -->
                            <div class="space-y-2">
                                <label for="Status" class="block text-sm font-medium text-gray-700">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select name="Status" 
                                        id="Status" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-150 ease-in-out @error('Status') border-red-500 @enderror"
                                        required>
                                    <option value="">Pilih Status</option>
                                    @foreach($statusOptions as $status)
                                        <option value="{{ $status }}" {{ old('Status', $instansi->Status) == $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('Status')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Form Status Messages -->
                        @if(session('success'))
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if(session('error'))
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Form Actions -->
                        <div class="pt-6 border-t border-gray-200">
                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('instansi.index') }}" 
                                   class="px-6 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    Batal
                                </a>
                                <button type="submit" 
                                        class="px-6 py-3 bg-purple-600 border border-transparent rounded-lg font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-150 ease-in-out">
                                    <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Perbarui Instansi
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Info Sidebar -->
        <div class="space-y-6">
            <!-- Instansi Info Card -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-purple-50 px-6 py-4 border-b border-purple-100">
                    <h3 class="text-lg font-semibold text-purple-900">Informasi Instansi</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-center">
                        <img src="{{ $instansi->Logo ? asset('storage/' . $instansi->Logo) : asset('images/default-logo.png') }}" 
                             alt="{{ $instansi->NamaSekolah }}"
                             class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md">
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">ID Instansi</p>
                        <p class="text-lg font-bold text-gray-900 mt-1">#{{ $instansi->InstansiID }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">Status</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $instansi->Status == 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <svg class="mr-1.5 h-2 w-2 {{ $instansi->Status == 'Aktif' ? 'text-green-800' : 'text-red-800' }}" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            {{ $instansi->Status }}
                        </span>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">Tanggal Berdiri</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $instansi->TanggalBerdiri->format('d F Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $instansi->TanggalBerdiri->diffForHumans() }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">Usia Sekolah</p>
                        <p class="text-sm text-gray-900 mt-1">
                            {{ now()->diffInYears($instansi->TanggalBerdiri) }} tahun
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">Tanggal Dibuat</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $instansi->created_at->format('d F Y, H:i') }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">Terakhir Diupdate</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $instansi->updated_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('instansi.create') }}" 
                       class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Tambah Instansi Baru</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    
                    <a href="{{ route('instansi.index') }}" 
                       class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Lihat Semua Instansi</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    
                    <button type="button" 
                            onclick="window.location.reload()"
                            class="w-full flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Refresh Data</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </button>
                </div>
            </div>
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
                previewImage.src = e.target.result;
                preview.querySelector('p').textContent = 'Logo baru';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    window.confirmDelete = function() {
        if (confirm('Apakah Anda yakin ingin menghapus instansi ini?\n\nTindakan ini tidak dapat dibatalkan.')) {
            document.getElementById('deleteForm').submit();
        }
    };
    
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('editForm');
        
        // Auto-format NPSN
        const npsnInput = document.getElementById('NPSN');
        npsnInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
        
        // Auto-format KodePos
        const kodePosInput = document.getElementById('KodePos');
        kodePosInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
        
        // Set max date for Tanggal Berdiri to today
        const tanggalInput = document.getElementById('TanggalBerdiri');
        const today = new Date().toISOString().split('T')[0];
        tanggalInput.max = today;
        
        // Track changes for confirmation
        let originalValues = {
            KodeInstansi: document.getElementById('KodeInstansi').value,
            NPSN: document.getElementById('NPSN').value,
            NamaSekolah: document.getElementById('NamaSekolah').value,
            JenjangSekolah: document.getElementById('JenjangSekolah').value,
            KodePos: document.getElementById('KodePos').value,
            Email: document.getElementById('Email').value,
            NamaKepalaSekolah: document.getElementById('NamaKepalaSekolah').value,
            NIPKepalaSekolah: document.getElementById('NIPKepalaSekolah').value,
            TanggalBerdiri: document.getElementById('TanggalBerdiri').value,
            Status: document.getElementById('Status').value
        };
        
        form.addEventListener('submit', function(e) {
            // Check if any changes were made
            let hasChanges = false;
            for (const [key, originalValue] of Object.entries(originalValues)) {
                const currentValue = document.getElementById(key).value;
                if (currentValue !== originalValue) {
                    hasChanges = true;
                    break;
                }
            }
            
            // Check logo change
            const logoInput = document.getElementById('Logo');
            if (logoInput.files.length > 0) {
                hasChanges = true;
            }
            
            if (!hasChanges) {
                if (!confirm('Anda belum melakukan perubahan apapun. Lanjutkan?')) {
                    e.preventDefault();
                    return;
                }
            }
            
            // Validate file size
            if (logoInput.files.length > 0) {
                const fileSize = logoInput.files[0].size / 1024 / 1024; // in MB
                if (fileSize > 2) {
                    e.preventDefault();
                    alert('Ukuran file maksimal 2MB');
                    return;
                }
            }
        });
        
        // Show unsaved changes warning
        window.addEventListener('beforeunload', function(e) {
            let hasChanges = false;
            for (const [key, originalValue] of Object.entries(originalValues)) {
                const currentValue = document.getElementById(key).value;
                if (currentValue !== originalValue) {
                    hasChanges = true;
                    break;
                }
            }
            
            const logoInput = document.getElementById('Logo');
            if (logoInput.files.length > 0) {
                hasChanges = true;
            }
            
            if (hasChanges) {
                e.preventDefault();
                e.returnValue = 'Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman?';
                return e.returnValue;
            }
        });
    });
</script>
@endpush