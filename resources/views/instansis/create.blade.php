@extends('layouts.app')

@section('title', 'Tambah Instansi - Jobie')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Tambah Instansi Baru</h1>
        <p class="mt-1 text-gray-600">Isi form berikut untuk menambahkan instansi/sekolah baru</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('instansi.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 active:bg-gray-100 focus:outline-none focus:border-purple-500 focus:ring focus:ring-purple-300 transition duration-150 ease-in-out">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
        <div class="p-6 md:p-8">
            <form action="{{ route('instansi.store') }}" method="POST" enctype="multipart/form-data" id="instansiForm" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kode Instansi -->
                    <div class="space-y-2">
                        <label for="KodeInstansi" class="block text-sm font-medium text-gray-700">
                            Kode Instansi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="KodeInstansi" 
                               id="KodeInstansi" 
                               value="{{ old('KodeInstansi') }}"
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
                               value="{{ old('NPSN') }}"
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
                           value="{{ old('NamaSekolah') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-150 ease-in-out @error('NamaSekolah') border-red-500 @enderror"
                           placeholder="Nama lengkap sekolah"
                           required>
                    @error('NamaSekolah')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Jenjang Sekolah (full width) -->
                    <div class="space-y-2 md:col-span-2">
                        <label for="JenjangSekolah" class="block text-sm font-medium text-gray-700">
                            Jenjang Sekolah <span class="text-red-500">*</span>
                        </label>
                        <select name="JenjangSekolah" 
                                id="JenjangSekolah" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-150 ease-in-out @error('JenjangSekolah') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Jenjang</option>
                            @foreach($jenjangOptions as $jenjang)
                                <option value="{{ $jenjang }}" {{ old('JenjangSekolah') == $jenjang ? 'selected' : '' }}>
                                    {{ $jenjang }}
                                </option>
                            @endforeach
                        </select>
                        @error('JenjangSekolah')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Provinsi dan Kota (baris pertama, masing-masing col-span-6) -->
                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Provinsi -->
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Provinsi</label>
                            <select id="provinsi" name="provinsi_code"
                                class="w-full px-4 py-3 border rounded-lg">
                                <option value="">Pilih Provinsi</option>
                                @foreach($provinsis as $prov)
                                    <option value="{{ $prov->code }}">
                                        {{ $prov->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Kota -->
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Kota / Kabupaten</label>
                            <select id="kota" name="kota_code"
                                class="w-full px-4 py-3 border rounded-lg">
                                <option value="">Pilih Kota</option>
                            </select>
                        </div>
                    </div>

                    <!-- Kecamatan dan Kelurahan (baris kedua, masing-masing col-span-6) -->
                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kecamatan -->
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Kecamatan</label>
                            <select id="kecamatan" name="kecamatan_code"
                                class="w-full px-4 py-3 border rounded-lg">
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>

                        <!-- Kelurahan -->
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Kelurahan</label>
                            <select id="kelurahan" name="kelurahan_code"
                                class="w-full px-4 py-3 border rounded-lg">
                                <option value="">Pilih Kelurahan</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Kode Pos -->
                    <div class="space-y-2">
                        <label for="KodePos" class="block text-sm font-medium text-gray-700">
                            Kode Pos <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                            name="KodePos" 
                            id="KodePos" 
                            value="{{ old('KodePos') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-150 ease-in-out @error('KodePos') border-red-500 @enderror"
                            placeholder="Contoh: 12345"
                            required>
                        @error('KodePos')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                
                <!-- Email -->
                <div class="space-y-2">
                    <label for="Email" class="block text-sm font-medium text-gray-700">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           name="Email" 
                           id="Email" 
                           value="{{ old('Email') }}"
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
                                <span class="text-sm text-gray-500">Upload Logo</span>
                            </label>
                        </div>
                        <div id="logoPreview" class="hidden">
                            <img id="previewImage" class="w-32 h-32 rounded-lg object-cover border border-gray-200">
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
                               value="{{ old('NamaKepalaSekolah') }}"
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
                               value="{{ old('NIPKepalaSekolah') }}"
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
                               value="{{ old('TanggalBerdiri') }}"
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
                                <option value="{{ $status }}" {{ old('Status') == $status ? 'selected' : '' }}>
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
        
        // Form validation
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Reset previous error states
            document.querySelectorAll('.border-red-500').forEach(el => {
                el.classList.remove('border-red-500');
            });
            document.querySelectorAll('.error-message').forEach(el => {
                el.remove();
            });
            
            // Validate file size
            const logoInput = document.getElementById('Logo');
            if (logoInput.files.length > 0) {
                const fileSize = logoInput.files[0].size / 1024 / 1024; // in MB
                if (fileSize > 2) {
                    isValid = false;
                    logoInput.parentElement.classList.add('border-red-500');
                    showError(logoInput.parentElement, 'Ukuran file maksimal 2MB');
                }
            }
            
            if (!isValid) {
                e.preventDefault();
                // Scroll to first error
                const firstError = document.querySelector('.border-red-500');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
        
        function showError(inputElement, message) {
            const errorElement = document.createElement('p');
            errorElement.className = 'error-message text-sm text-red-600 mt-1';
            errorElement.textContent = message;
            
            const parent = inputElement.parentElement;
            if (parent) {
                parent.appendChild(errorElement);
            }
        }

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