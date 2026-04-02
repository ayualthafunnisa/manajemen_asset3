<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Register - Asset Management')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .animate-slide {
            animation: slideIn 0.4s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .form-input-error {
            border-color: #ef4444;
        }
        
        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .role-card {
            transition: all 0.3s ease;
        }
        
        .role-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.5);
        }
        
        .role-card.selected {
            border: 2px solid #3b82f6;
            background: linear-gradient(to bottom right, #eff6ff, #ffffff);
        }
        
        .step-indicator {
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="antialiased">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-blue-50 to-indigo-50">
        <div class="max-w-3xl w-full">
            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 shadow-lg mb-4 animate-fade-in">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">
                    Daftar<span class="text-blue-600">.</span>
                </h2>
                <p class="mt-2 text-sm text-gray-600" id="headerDescription">Pilih role untuk memulai pendaftaran</p>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up">
                <div class="p-8">
                    <!-- Progress Steps - Dynamic based on role -->
                    <div class="mb-8" id="progressSteps" style="display: none;">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center w-full">
                                <!-- Step 1 (always visible) -->
                                <div class="flex items-center relative">
                                    <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2" id="step1Circle">
                                        <div class="flex items-center justify-center font-bold" id="step1Text">1</div>
                                    </div>
                                    <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase" id="step1Label">Pilih Role</div>
                                </div>
                                
                                <!-- Line 1-2 -->
                                <div class="flex-auto border-t-2 transition duration-500 ease-in-out" id="step1Line"></div>
                                
                                <!-- Step 2 (changes based on role) -->
                                <div class="flex items-center relative">
                                    <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2" id="step2Circle">
                                        <div class="flex items-center justify-center font-bold" id="step2Text">2</div>
                                    </div>
                                    <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase" id="step2Label">Data Sekolah</div>
                                </div>
                                
                                <!-- Line 2-3 (only for admin) -->
                                <div class="flex-auto border-t-2 transition duration-500 ease-in-out" id="step2Line" style="display: none;"></div>
                                
                                <!-- Step 3 (only for admin) -->
                                <div class="flex items-center relative" id="step3Container" style="display: none;">
                                    <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2" id="step3Circle">
                                        <div class="flex items-center justify-center font-bold" id="step3Text">3</div>
                                    </div>
                                    <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase" id="step3Label">Data Admin</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Error Messages -->
                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Registrasi gagal:</h3>
                                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Register Form -->
                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="registerForm">
                        @csrf

                        <!-- Step 1: Role Selection -->
                        <div id="step1" class="step-content animate-slide">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Pilih Role Anda</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                                <!-- Admin Sekolah Card -->
                                <div id="roleAdminCard" 
                                     onclick="selectRole('admin_sekolah')" 
                                     class="role-card cursor-pointer p-6 border-2 rounded-xl text-center transition-all duration-300 border-gray-200 hover:border-blue-300">
                                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Admin Sekolah</h4>
                                    <p class="text-sm text-gray-600">Daftarkan sekolah Anda dan kelola aset sekolah</p>
                                </div>

                                <!-- Teknisi Card -->
                                <div id="roleTeknisiCard" 
                                     onclick="selectRole('teknisi')" 
                                     class="role-card cursor-pointer p-6 border-2 rounded-xl text-center transition-all duration-300 border-gray-200 hover:border-blue-300">
                                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-r from-green-600 to-teal-600 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Teknisi</h4>
                                    <p class="text-sm text-gray-600">Daftar sebagai teknisi untuk penanganan aset</p>
                                </div>
                            </div>
                            <input type="hidden" name="role" id="role" value="">
                            
                            <div class="flex justify-end">
                                <button type="button" 
                                        onclick="nextStep()" 
                                        id="nextToStep2"
                                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
                                        disabled>
                                    Selanjutnya
                                    <svg class="w-5 h-5 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: School Data (only for admin_sekolah) -->
                        <div id="step2" class="step-content animate-slide" style="display: none;">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Data Sekolah</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Nama Sekolah -->
                                <div class="md:col-span-2">
                                    <label for="nama_instansi" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Sekolah <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="nama_instansi" 
                                           id="nama_instansi" 
                                           value="{{ old('nama_instansi') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nama_instansi') border-red-500 @enderror"
                                           placeholder="Masukkan nama sekolah">
                                    @error('nama_instansi')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- NPSN -->
                                <div>
                                    <label for="npsn" class="block text-sm font-medium text-gray-700 mb-2">
                                        NPSN <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="npsn" 
                                           id="npsn" 
                                           value="{{ old('npsn') }}"
                                           maxlength="8"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('npsn') border-red-500 @enderror"
                                           placeholder="Masukkan NPSN (8 digit)">
                                    @error('npsn')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Jenjang Sekolah -->
                                <div>
                                    <label for="jenjang_sekolah" class="block text-sm font-medium text-gray-700 mb-2">
                                        Jenjang Sekolah <span class="text-red-500">*</span>
                                    </label>
                                    <select name="jenjang_sekolah" 
                                            id="jenjang_sekolah" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('jenjang_sekolah') border-red-500 @enderror">
                                        <option value="">Pilih Jenjang</option>
                                        <option value="SD" {{ old('jenjang_sekolah') == 'SD' ? 'selected' : '' }}>SD</option>
                                        <option value="SMP" {{ old('jenjang_sekolah') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                        <option value="SMA" {{ old('jenjang_sekolah') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                        <option value="SMK" {{ old('jenjang_sekolah') == 'SMK' ? 'selected' : '' }}>SMK</option>
                                    </select>
                                    @error('jenjang_sekolah')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email Sekolah -->
                                <div>
                                    <label for="email_instansi" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email Sekolah <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" 
                                           name="email_instansi" 
                                           id="email_instansi" 
                                           value="{{ old('email_instansi') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email_instansi') border-red-500 @enderror"
                                           placeholder="sekolah@email.com">
                                    @error('email_instansi')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Provinsi -->
                                <div>
                                    <label for="provinsi" class="block text-sm font-medium text-gray-700 mb-2">
                                        Provinsi <span class="text-red-500">*</span>
                                    </label>
                                    <select name="provinsi_code" 
                                            id="provinsi" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('provinsi_code') border-red-500 @enderror">
                                        <option value="">Pilih Provinsi</option>
                                        @foreach($provinsis as $prov)
                                            <option value="{{ $prov['code'] }}" {{ old('provinsi_code') == $prov['code'] ? 'selected' : '' }}>
                                                {{ $prov['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('provinsi_code')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Kota/Kabupaten -->
                                <div>
                                    <label for="kota" class="block text-sm font-medium text-gray-700 mb-2">
                                        Kota/Kabupaten <span class="text-red-500">*</span>
                                    </label>
                                    <select name="kota_code" 
                                            id="kota" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('kota_code') border-red-500 @enderror">
                                        <option value="">Pilih Kota</option>
                                    </select>
                                    @error('kota_code')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Kecamatan -->
                                <div>
                                    <label for="kecamatan" class="block text-sm font-medium text-gray-700 mb-2">
                                        Kecamatan <span class="text-red-500">*</span>
                                    </label>
                                    <select name="kecamatan_code" 
                                            id="kecamatan" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('kecamatan_code') border-red-500 @enderror">
                                        <option value="">Pilih Kecamatan</option>
                                    </select>
                                    @error('kecamatan_code')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Kelurahan -->
                                <div>
                                    <label for="kelurahan" class="block text-sm font-medium text-gray-700 mb-2">
                                        Kelurahan <span class="text-red-500">*</span>
                                    </label>
                                    <select name="kelurahan_code" 
                                            id="kelurahan" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('kelurahan_code') border-red-500 @enderror">
                                        <option value="">Pilih Kelurahan</option>
                                    </select>
                                    @error('kelurahan_code')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Kode Pos -->
                                <div>
                                    <label for="kode_pos" class="block text-sm font-medium text-gray-700 mb-2">
                                        Kode Pos <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="kode_pos" 
                                           id="kode_pos" 
                                           value="{{ old('kode_pos') }}"
                                           maxlength="5"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('kode_pos') border-red-500 @enderror"
                                           placeholder="Masukkan kode pos">
                                    @error('kode_pos')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Alamat Lengkap -->
                                <div class="md:col-span-2">
                                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                                        Alamat Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="alamat" 
                                              id="alamat" 
                                              rows="3"
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('alamat') border-red-500 @enderror"
                                              placeholder="Masukkan alamat lengkap jalan, nomor, RT/RW">{{ old('alamat') }}</textarea>
                                    @error('alamat')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nama Kepala Sekolah -->
                                <div>
                                    <label for="nama_kepsek" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Kepala Sekolah <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="nama_kepsek" 
                                           id="nama_kepsek" 
                                           value="{{ old('nama_kepsek') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nama_kepsek') border-red-500 @enderror"
                                           placeholder="Masukkan nama kepala sekolah">
                                    @error('nama_kepsek')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- NIP Kepala Sekolah -->
                                <div>
                                    <label for="nip_kepsek" class="block text-sm font-medium text-gray-700 mb-2">
                                        NIP Kepala Sekolah <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="nip_kepsek" 
                                           id="nip_kepsek" 
                                           value="{{ old('nip_kepsek') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nip_kepsek') border-red-500 @enderror"
                                           placeholder="Masukkan NIP kepala sekolah">
                                    @error('nip_kepsek')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tanggal Berdiri -->
                                <div>
                                    <label for="tanggal_berdiri" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal Berdiri <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" 
                                           name="tanggal_berdiri" 
                                           id="tanggal_berdiri" 
                                           value="{{ old('tanggal_berdiri') }}"
                                           max="{{ date('Y-m-d') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('tanggal_berdiri') border-red-500 @enderror">
                                    @error('tanggal_berdiri')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Logo Sekolah -->
                                <div>
                                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                                        Logo Sekolah <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex items-center space-x-4">
                                        <div class="relative">
                                            <input type="file" 
                                                   name="logo" 
                                                   id="logo" 
                                                   accept="image/jpeg,image/png,image/jpg"
                                                   class="hidden"
                                                   onchange="previewLogo(event)">
                                            <label for="logo" 
                                                   class="cursor-pointer flex flex-col items-center justify-center w-32 h-32 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition duration-150">
                                                <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="text-sm text-gray-500">Upload Logo</span>
                                            </label>
                                        </div>
                                        <div id="logoPreview" class="hidden">
                                            <img id="previewImage" class="w-32 h-32 rounded-lg object-cover border border-gray-200">
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG (maks. 2MB)</p>
                                    @error('logo')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex justify-between mt-6">
                                <button type="button" 
                                        onclick="prevStep()" 
                                        class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    Kembali
                                </button>
                                <button type="button" 
                                        onclick="nextStep()" 
                                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                                    Selanjutnya
                                    <svg class="w-5 h-5 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Teknisi Data / Admin Data (based on role) -->
                        <div id="step3" class="step-content animate-slide" style="display: none;">
                            <!-- Title will be dynamically updated -->
                            <h3 id="userDataTitle" class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Data Teknisi</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Nama Lengkap -->
                                <div class="md:col-span-2">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="name" 
                                           id="name" 
                                           value="{{ old('name') }}"
                                           required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('name') border-red-500 @enderror"
                                           placeholder="Masukkan nama lengkap">
                                    @error('name')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" 
                                           name="email" 
                                           id="email" 
                                           value="{{ old('email') }}"
                                           required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 @enderror"
                                           placeholder="email@example.com">
                                    @error('email')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- No. Telepon -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        No. Telepon
                                    </label>
                                    <input type="text" 
                                           name="phone" 
                                           id="phone" 
                                           value="{{ old('phone') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('phone') border-red-500 @enderror"
                                           placeholder="08123456789">
                                    @error('phone')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                        Password <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="password" 
                                               name="password" 
                                               id="password" 
                                               required 
                                               minlength="8"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-500 @enderror"
                                               placeholder="Minimal 8 karakter">
                                        <button type="button" 
                                                onclick="togglePassword('password')" 
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <svg class="h-5 w-5 text-gray-400 hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <!-- Password strength indicator -->
                                    <div id="password-strength" class="mt-2 h-1 rounded-full bg-gray-200 overflow-hidden hidden">
                                        <div class="h-full transition-all duration-300"></div>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Gunakan minimal 8 karakter dengan kombinasi huruf besar, huruf kecil, dan angka</p>
                                    @error('password')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                        Konfirmasi Password <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="password" 
                                               name="password_confirmation" 
                                               id="password_confirmation" 
                                               required 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                               placeholder="Ulangi password">
                                        <button type="button" 
                                                onclick="togglePassword('password_confirmation')" 
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <svg class="h-5 w-5 text-gray-400 hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="flex items-start mt-6 pt-4 border-t border-gray-200">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" 
                                           name="terms" 
                                           id="terms" 
                                           required
                                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="terms" class="text-gray-700">
                                        Saya menyetujui 
                                        <a href="#" class="text-blue-600 hover:text-blue-500">Syarat dan Ketentuan</a> 
                                        serta 
                                        <a href="#" class="text-blue-600 hover:text-blue-500">Kebijakan Privasi</a>
                                    </label>
                                </div>
                            </div>

                            <div class="flex justify-between mt-6">
                                <button type="button" 
                                        onclick="prevStep()" 
                                        class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    Kembali
                                </button>
                                <button type="submit" 
                                        id="submitBtn"
                                        class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span id="submitText">Daftar</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Login Link -->
            <div class="text-center mt-6">
                <p class="text-sm text-gray-600">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                        Masuk sekarang
                    </a>
                </p>
            </div>

            <!-- Footer -->
            <p class="text-center text-xs text-gray-500 mt-8">
                &copy; {{ date('Y') }} Asset Management. All rights reserved.
            </p>
        </div>
    </div>

    <script>
    let currentStep = 1;
    let selectedRole = '';
    let totalSteps = 2; // Default untuk teknisi

    // Preview logo function
    function previewLogo(event) {
        const input = event.target;
        const preview = document.getElementById('logoPreview');
        const previewImage = document.getElementById('previewImage');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Validasi ukuran file
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB');
                input.value = '';
                return;
            }
            
            // Validasi tipe file
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!allowedTypes.includes(file.type)) {
                alert('Format file harus JPG atau PNG');
                input.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.classList.remove('hidden');
                previewImage.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }

    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
        field.setAttribute('type', type);
    }

    // Password strength indicator
    document.getElementById('password')?.addEventListener('input', function(e) {
        const password = e.target.value;
        const strength = checkPasswordStrength(password);
        updatePasswordStrength(strength);
    });

    function checkPasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (password.match(/[a-z]+/)) strength++;
        if (password.match(/[A-Z]+/)) strength++;
        if (password.match(/[0-9]+/)) strength++;
        if (password.match(/[$@#&!]+/)) strength++;
        return strength;
    }

    function updatePasswordStrength(strength) {
        const strengthContainer = document.getElementById('password-strength');
        const strengthBar = strengthContainer.querySelector('div');
        
        if (strength > 0) {
            strengthContainer.classList.remove('hidden');
            const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-blue-500', 'bg-green-500'];
            const widths = ['20%', '40%', '60%', '80%', '100%'];
            
            strengthBar.style.width = widths[strength - 1];
            strengthBar.className = `h-full ${colors[strength - 1]} transition-all duration-300`;
        } else {
            strengthContainer.classList.add('hidden');
            strengthBar.style.width = '0%';
        }
    }

    // Select role function
    function selectRole(role) {
        selectedRole = role;
        document.getElementById('role').value = role;
        
        // Update card styles
        const adminCard = document.getElementById('roleAdminCard');
        const teknisiCard = document.getElementById('roleTeknisiCard');
        
        if (role === 'admin_sekolah') {
            adminCard.classList.add('selected', 'border-blue-500', 'bg-blue-50');
            adminCard.classList.remove('border-gray-200');
            teknisiCard.classList.remove('selected', 'border-blue-500', 'bg-blue-50');
            teknisiCard.classList.add('border-gray-200');
            totalSteps = 3; // Admin sekolah punya 3 steps
        } else {
            teknisiCard.classList.add('selected', 'border-blue-500', 'bg-blue-50');
            teknisiCard.classList.remove('border-gray-200');
            adminCard.classList.remove('selected', 'border-blue-500', 'bg-blue-50');
            adminCard.classList.add('border-gray-200');
            totalSteps = 2; // Teknisi punya 2 steps
        }
        
        // Enable next button
        document.getElementById('nextToStep2').disabled = false;
    }

    // Navigation functions
    function nextStep() {
        if (currentStep === 1) {
            if (!selectedRole) {
                alert('Silakan pilih role terlebih dahulu');
                return;
            }
            
            // Show progress steps
            document.getElementById('progressSteps').style.display = 'block';
            
            if (selectedRole === 'admin_sekolah') {
                // Admin sekolah: step 1 -> step 2 (school data)
                showStep(2);
                updateProgressSteps(2);
                document.getElementById('headerDescription').textContent = 'Lengkapi data sekolah Anda';
                document.getElementById('step2Label').textContent = 'Data Sekolah';
                document.getElementById('step3Label').textContent = 'Data Admin';
                
                // Tampilkan step 3 container untuk admin
                document.getElementById('step2Line').style.display = 'block';
                document.getElementById('step3Container').style.display = 'flex';
                
                // Set title for step 3 (will be used later)
                document.getElementById('userDataTitle').textContent = 'Data Admin Sekolah';
                document.getElementById('submitText').textContent = 'Daftarkan Sekolah';
            } else {
                // Teknisi: step 1 -> step 3 (user data) - langsung ke step akhir
                showStep(3);
                updateProgressSteps(2); // Progress step ke 2 dari 2
                document.getElementById('headerDescription').textContent = 'Lengkapi data diri teknisi';
                document.getElementById('userDataTitle').textContent = 'Data Teknisi';
                document.getElementById('submitText').textContent = 'Daftar sebagai Teknisi';
                
                // Sembunyikan step 3 container untuk teknisi
                document.getElementById('step2Line').style.display = 'none';
                document.getElementById('step3Container').style.display = 'none';
            }
        } 
        else if (currentStep === 2) {
            // Validate school data before proceeding
            if (validateSchoolData()) {
                showStep(3);
                updateProgressSteps(3);
                document.getElementById('headerDescription').textContent = 'Lengkapi data admin sekolah';
            }
        }
    }

    function prevStep() {
        if (currentStep === 2) {
            showStep(1);
            document.getElementById('progressSteps').style.display = 'none';
            document.getElementById('headerDescription').textContent = 'Pilih role untuk memulai pendaftaran';
        } else if (currentStep === 3) {
            if (selectedRole === 'admin_sekolah') {
                showStep(2);
                updateProgressSteps(2);
                document.getElementById('headerDescription').textContent = 'Lengkapi data sekolah Anda';
            } else {
                showStep(1);
                document.getElementById('progressSteps').style.display = 'none';
                document.getElementById('headerDescription').textContent = 'Pilih role untuk memulai pendaftaran';
            }
        }
    }

    function showStep(step) {
        // Hide all steps
        document.querySelectorAll('.step-content').forEach(el => {
            el.style.display = 'none';
        });
        
        // Show current step
        document.getElementById(`step${step}`).style.display = 'block';
        currentStep = step;
    }

    function updateProgressSteps(step) {
        // Reset all steps
        const steps = selectedRole === 'admin_sekolah' ? [1, 2, 3] : [1, 2];
        
        // Reset step 1 & 2
        document.getElementById('step1Circle').className = 'rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-gray-300 bg-white';
        document.getElementById('step1Text').className = 'flex items-center justify-center text-gray-500 font-bold';
        document.getElementById('step1Label').className = 'absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase text-gray-500';
        document.getElementById('step1Line').className = 'flex-auto border-t-2 transition duration-500 ease-in-out border-gray-300';
        
        document.getElementById('step2Circle').className = 'rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-gray-300 bg-white';
        document.getElementById('step2Text').className = 'flex items-center justify-center text-gray-500 font-bold';
        document.getElementById('step2Label').className = 'absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase text-gray-500';
        
        if (selectedRole === 'admin_sekolah') {
            document.getElementById('step2Line').className = 'flex-auto border-t-2 transition duration-500 ease-in-out border-gray-300';
            document.getElementById('step3Circle').className = 'rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-gray-300 bg-white';
            document.getElementById('step3Text').className = 'flex items-center justify-center text-gray-500 font-bold';
            document.getElementById('step3Label').className = 'absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase text-gray-500';
        }

        // Update based on current step
        for (let i = 1; i <= step; i++) {
            if (i < step) {
                // Completed steps
                document.getElementById(`step${i}Circle`).className = 'rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 bg-blue-600 border-blue-600';
                document.getElementById(`step${i}Text`).className = 'flex items-center justify-center text-white font-bold';
                document.getElementById(`step${i}Label`).className = 'absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase text-blue-600';
                if (i < 3 && (selectedRole === 'admin_sekolah' || i < 2)) {
                    document.getElementById(`step${i}Line`).className = 'flex-auto border-t-2 transition duration-500 ease-in-out border-blue-600';
                }
            } else if (i === step) {
                // Current step
                document.getElementById(`step${i}Circle`).className = 'rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-blue-600 bg-white';
                document.getElementById(`step${i}Text`).className = 'flex items-center justify-center text-blue-600 font-bold';
                document.getElementById(`step${i}Label`).className = 'absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase text-blue-600';
            }
        }
    }

    function validateSchoolData() {
        // Add your validation logic here
        const requiredFields = ['nama_instansi', 'npsn', 'jenjang_sekolah', 'email_instansi', 
                               'provinsi', 'kota', 'kecamatan', 'kelurahan', 'kode_pos', 
                               'alamat', 'nama_kepsek', 'nip_kepsek', 'tanggal_berdiri', 'logo'];
        
        for (let field of requiredFields) {
            const element = document.getElementById(field);
            if (element && !element.value) {
                alert('Harap lengkapi semua data sekolah');
                element.focus();
                return false;
            }
        }
        
        return true;
    }

    // Form submission validation
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Password tidak cocok');
            return;
        }
        
        // Disable submit button
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('submitBtn').innerHTML = `
            <svg class="animate-spin h-5 w-5 mr-3" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg> Mendaftarkan...
        `;
    });

    // Auto-hide notifications after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.bg-red-50, .bg-green-50');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        });

        // Format NPSN dan Kode Pos hanya angka
        const npsnInput = document.getElementById('npsn');
        const kodePosInput = document.getElementById('kode_pos');
        
        npsnInput?.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8);
        });
        
        kodePosInput?.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5);
        });

        // Set max date untuk tanggal berdiri
        const tanggalInput = document.getElementById('tanggal_berdiri');
        if (tanggalInput) {
            tanggalInput.max = new Date().toISOString().split('T')[0];
        }
    });

    // Wilayah dropdown dengan jQuery
    $(document).ready(function() {
        const provinsi = $('#provinsi');
        const kota = $('#kota');
        const kecamatan = $('#kecamatan');
        const kelurahan = $('#kelurahan');

        // Jika ada old value provinsi, load kota
        @if(old('provinsi_code'))
            loadKota('{{ old('provinsi_code') }}', '{{ old('kota_code') }}');
        @endif

        // Provinsi change handler
        provinsi.on('change', function() {
            const provCode = this.value;
            if (provCode) {
                loadKota(provCode);
            } else {
                kota.html('<option value="">Pilih Kota</option>');
                kecamatan.html('<option value="">Pilih Kecamatan</option>');
                kelurahan.html('<option value="">Pilih Kelurahan</option>');
            }
        });

        // Kota change handler
        kota.on('change', function() {
            const kotaCode = this.value;
            if (kotaCode) {
                loadKecamatan(kotaCode);
            } else {
                kecamatan.html('<option value="">Pilih Kecamatan</option>');
                kelurahan.html('<option value="">Pilih Kelurahan</option>');
            }
        });

        // Kecamatan change handler
        kecamatan.on('change', function() {
            const kecCode = this.value;
            if (kecCode) {
                loadKelurahan(kecCode);
            } else {
                kelurahan.html('<option value="">Pilih Kelurahan</option>');
            }
        });

        function loadKota(provCode, selectedKota = '') {
            $.ajax({
                url: `/api/indonesia/cities/${provCode}`,
                type: 'GET',
                success: function(data) {
                    let options = '<option value="">Pilih Kota</option>';
                    data.forEach(item => {
                        const selected = (item.code == selectedKota) ? 'selected' : '';
                        options += `<option value="${item.code}" ${selected}>${item.name}</option>`;
                    });
                    kota.html(options);
                    
                    // Jika ada selectedKota, load kecamatan
                    if (selectedKota) {
                        loadKecamatan(selectedKota, '{{ old('kecamatan_code') }}');
                    }
                }
            });
        }

        function loadKecamatan(kotaCode, selectedKec = '') {
            $.ajax({
                url: `/api/indonesia/districts/${kotaCode}`,
                type: 'GET',
                success: function(data) {
                    let options = '<option value="">Pilih Kecamatan</option>';
                    data.forEach(item => {
                        const selected = (item.code == selectedKec) ? 'selected' : '';
                        options += `<option value="${item.code}" ${selected}>${item.name}</option>`;
                    });
                    kecamatan.html(options);
                    
                    // Jika ada selectedKec, load kelurahan
                    if (selectedKec) {
                        loadKelurahan(selectedKec, '{{ old('kelurahan_code') }}');
                    }
                }
            });
        }

        function loadKelurahan(kecCode, selectedKel = '') {
            $.ajax({
                url: `/api/indonesia/villages/${kecCode}`,
                type: 'GET',
                success: function(data) {
                    let options = '<option value="">Pilih Kelurahan</option>';
                    data.forEach(item => {
                        const selected = (item.code == selectedKel) ? 'selected' : '';
                        options += `<option value="${item.code}" ${selected}>${item.name}</option>`;
                    });
                    kelurahan.html(options);
                }
            });
        }
    });
    </script>
</body>
</html>