@extends('layouts.app')

@section('content')
@php
function formatWilayah($data) {
    if (empty($data) || $data === '-' || $data === null) return '-';
    
    // Coba decode JSON dulu
    $decoded = json_decode($data, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        // Coba semua kemungkinan key dari berbagai paket wilayah
        return $decoded['name'] 
            ?? $decoded['nama'] 
            ?? $decoded['wilayah'] 
            ?? $decoded['daerah']
            ?? reset($decoded) // ambil value pertama apapun keynya
            ?? '-';
    }
    
    // Bukan JSON, langsung return string
    return trim($data) ?: '-';
}
@endphp
<div class="container mx-auto px-4 py-8 max-w-5xl">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Profil Saya</h1>
        <p class="text-gray-500 mt-1">Kelola informasi akun dan data sekolah</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6">
            <div class="font-medium mb-1">Terjadi kesalahan:</div>
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="space-y-6">
        <!-- Data Admin Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/30">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Data Admin Sekolah</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Informasi akun administrator sekolah</p>
                </div>
                <button onclick="toggleEdit('adminForm')" 
                        class="px-4 py-2 text-sm bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-100 transition-colors flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    <span>Edit</span>
                </button>
            </div>
            
            <!-- View Mode -->
            <div id="adminView" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Nama Lengkap</label>
                        <p class="text-gray-800 font-medium">{{ $user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Email</label>
                        <p class="text-gray-800 font-medium">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Nomor Telepon</label>
                        <p class="text-gray-800 font-medium">{{ $user->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Role</label>
                        <p class="text-gray-800 font-medium">
                            @php
                                $roleNames = ['super_admin' => 'Super Admin', 'admin_sekolah' => 'Admin Sekolah', 'petugas' => 'Petugas', 'teknisi' => 'Teknisi'];
                            @endphp
                            {{ $roleNames[$user->role ?? ''] ?? 'Member' }}
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Edit Mode -->
            <form id="adminForm" action="{{ route('profile.update') }}" method="POST" class="hidden">
                @csrf
                @method('PUT')
                <div class="p-6 border-t border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="cancelEdit('adminForm')" 
                                class="px-4 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Data Sekolah Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/30">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Data Sekolah</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Informasi lengkap profil sekolah</p>
                </div>
                <button onclick="toggleEdit('schoolForm')" 
                        class="px-4 py-2 text-sm bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-100 transition-colors flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    <span>{{ $instansi ? 'Edit' : 'Isi Data' }}</span>
                </button>
            </div>
            
            <!-- View Mode -->
            <div id="schoolView" class="p-6">
                @if($instansi)
                    <div class="flex flex-col md:flex-row gap-6 mb-6">
                        <!-- Logo Sekolah -->
                        <div class="flex-shrink-0">
                            @if($instansi->Logo && Storage::disk('public')->exists($instansi->Logo))
                                <div class="w-32 h-32 rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                                    <img src="{{ Storage::url($instansi->Logo) }}" alt="Logo {{ $instansi->NamaSekolah }}" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="w-32 h-32 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Nama Sekolah</label>
                                    <p class="text-gray-800 font-medium text-lg">{{ $instansi->NamaSekolah }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">NPSN</label>
                            <p class="text-gray-800 font-medium">{{ $instansi->NPSN }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Jenjang Sekolah</label>
                            <p class="text-gray-800 font-medium">{{ $instansi->JenjangSekolah }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Email Sekolah</label>
                            <p class="text-gray-800 font-medium">{{ $instansi->EmailSekolah }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Telepon Sekolah</label>
                            <p class="text-gray-800 font-medium">{{ $instansi->TeleponSekolah ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Kepala Sekolah</label>
                            <p class="text-gray-800 font-medium">{{ $instansi->NamaKepalaSekolah ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">NIP Kepala Sekolah</label>
                            <p class="text-gray-800 font-medium">{{ $instansi->NIPKepalaSekolah ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Tanggal Berdiri</label>
                            <p class="text-gray-800 font-medium">{{ $instansi->TanggalBerdiri ? date('d M Y', strtotime($instansi->TanggalBerdiri)) : '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Provinsi</label>
                            <p class="text-gray-800 font-medium">{{ formatWilayah($instansi->Provinsi) }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Kota/Kabupaten</label>
                            <p class="text-gray-800 font-medium">{{ formatWilayah($instansi->KotaKabupaten) }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Kecamatan</label>
                            <p class="text-gray-800 font-medium">{{ formatWilayah($instansi->Kecamatan) }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Kelurahan/Desa</label>
                            <p class="text-gray-800 font-medium">{{ formatWilayah($instansi->Kelurahan) }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Kode Pos</label>
                            <p class="text-gray-800 font-medium">
                                @php
                                    $pos = $instansi->KodePos;
                                    if (is_string($pos) && $pos != '-') {
                                        $posData = json_decode($pos, true);
                                        echo $posData['pos'] ?? $pos;
                                    } else {
                                        echo $pos ?? '-';
                                    }
                                @endphp
                            </p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Alamat Lengkap</label>
                            <p class="text-gray-800">{{ $instansi->AlamatLengkap ?? '-' }}</p>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500">Data sekolah belum diisi</p>
                        <p class="text-sm text-gray-400 mt-1">Klik tombol "Isi Data" untuk mengisi profil sekolah</p>
                    </div>
                @endif
            </div>
            
            <!-- Edit Mode Form -->
            <form id="schoolForm" action="{{ route('profile.school') }}" method="POST" enctype="multipart/form-data" class="hidden">
                @csrf
                @method('PUT')
                <div class="p-6 border-t border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Upload Logo - WAJIB untuk create baru -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Logo Sekolah 
                                @if(!$instansi)
                                    <span class="text-red-500">*</span>
                                @endif
                            </label>
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div id="logoPreviewContainer" class="w-24 h-24 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center overflow-hidden">
                                        @if($instansi && $instansi->Logo && Storage::disk('public')->exists($instansi->Logo))
                                            <img id="logoPreview" src="{{ Storage::url($instansi->Logo) }}" alt="Logo Preview" class="w-full h-full object-cover">
                                        @else
                                            <svg id="defaultLogoIcon" class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="Logo" id="logoInput" accept="image/jpeg,image/png,image/jpg,image/gif" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                        {{ !$instansi ? 'required' : '' }}>
                                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF. Maksimal 2MB</p>
                                    @if(!$instansi)
                                        <p class="text-xs text-red-500 mt-1">* Logo wajib diupload untuk pertama kali</p>
                                    @else
                                        <p class="text-xs text-gray-500 mt-1">* Kosongkan jika tidak ingin mengganti logo</p>
                                    @endif
                                </div>
                            </div>
                            @error('Logo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Nama Sekolah -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Sekolah <span class="text-red-500">*</span></label>
                            <input type="text" name="NamaSekolah" value="{{ old('NamaSekolah', $instansi->NamaSekolah ?? '') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <!-- NPSN -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">NPSN <span class="text-red-500">*</span></label>
                            <input type="text" name="NPSN" value="{{ old('NPSN', $instansi->NPSN ?? '') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <!-- Jenjang Sekolah -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenjang Sekolah <span class="text-red-500">*</span></label>
                            <select name="JenjangSekolah" required class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Jenjang</option>
                                <option value="SD" {{ old('JenjangSekolah', $instansi->JenjangSekolah ?? '') == 'SD' ? 'selected' : '' }}>SD</option>
                                <option value="SMP" {{ old('JenjangSekolah', $instansi->JenjangSekolah ?? '') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                <option value="SMA" {{ old('JenjangSekolah', $instansi->JenjangSekolah ?? '') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                <option value="SMK" {{ old('JenjangSekolah', $instansi->JenjangSekolah ?? '') == 'SMK' ? 'selected' : '' }}>SMK</option>
                            </select>
                        </div>
                        
                        <!-- Email Sekolah -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Sekolah <span class="text-red-500">*</span></label>
                            <input type="email" name="EmailSekolah" value="{{ old('EmailSekolah', $instansi->EmailSekolah ?? '') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <!-- Telepon Sekolah -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Telepon Sekolah</label>
                            <input type="text" name="TeleponSekolah" value="{{ old('TeleponSekolah', $instansi->TeleponSekolah ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <!-- Nama Kepala Sekolah - WAJIB -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kepala Sekolah <span class="text-red-500">*</span></label>
                            <input type="text" name="NamaKepalaSekolah" value="{{ old('NamaKepalaSekolah', $instansi->NamaKepalaSekolah ?? '') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <!-- NIP Kepala Sekolah - WAJIB -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">NIP Kepala Sekolah <span class="text-red-500">*</span></label>
                            <input type="text" name="NIPKepalaSekolah" value="{{ old('NIPKepalaSekolah', $instansi->NIPKepalaSekolah ?? '') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <!-- Tanggal Berdiri - WAJIB -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Berdiri <span class="text-red-500">*</span></label>
                            <input type="date" name="TanggalBerdiri" value="{{ old('TanggalBerdiri', $instansi->TanggalBerdiri ?? '') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <!-- Kode Pos - WAJIB -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos <span class="text-red-500">*</span></label>
                            <input type="text" name="KodePos" value="{{ old('KodePos', $instansi->KodePos ?? '') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <!-- Wilayah Dropdowns -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Wilayah</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <select name="provinsi_code" id="provinsi" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                                        <option value="">Pilih Provinsi</option>
                                        @foreach($provinces as $prov)
                                            <option value="{{ $prov->code }}" 
                                                data-name="{{ $prov->name }}"
                                                {{ old('provinsi_code', $instansi->provinsi_code ?? '') == $prov->code ? 'selected' : '' }}>
                                                {{ $prov->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <select name="kota_code" id="kota" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                                        <option value="">Pilih Kota/Kabupaten</option>
                                    </select>
                                </div>
                                <div>
                                    <select name="kecamatan_code" id="kecamatan" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                                        <option value="">Pilih Kecamatan</option>
                                    </select>
                                </div>
                                <div>
                                    <select name="kelurahan_code" id="kelurahan" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                                        <option value="">Pilih Kelurahan/Desa</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hidden inputs untuk menyimpan nama wilayah -->
                        <input type="hidden" name="Provinsi" id="Provinsi">
                        <input type="hidden" name="KotaKabupaten" id="KotaKabupaten">
                        <input type="hidden" name="Kecamatan" id="Kecamatan">
                        <input type="hidden" name="Kelurahan" id="Kelurahan">
                        
                        <!-- Alamat Lengkap -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                            <textarea name="AlamatLengkap" rows="2" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">{{ old('AlamatLengkap', $instansi->AlamatLengkap ?? '') }}</textarea>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="cancelEdit('schoolForm')" 
                                class="px-4 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                            Simpan Data Sekolah
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Preview logo sebelum upload
document.getElementById('logoInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Validasi tipe file
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            alert('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
            this.value = '';
            return;
        }
        
        // Validasi ukuran file (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 2MB.');
            this.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewContainer = document.getElementById('logoPreviewContainer');
            if (previewContainer) {
                previewContainer.innerHTML = `<img id="logoPreview" src="${e.target.result}" alt="Logo Preview" class="w-full h-full object-cover">`;
            }
        };
        reader.readAsDataURL(file);
    }
});

// Fungsi toggle edit
function toggleEdit(formId) {
    const viewDiv = document.getElementById(formId === 'adminForm' ? 'adminView' : 'schoolView');
    const formDiv = document.getElementById(formId);
    
    if (formDiv.classList.contains('hidden')) {
        viewDiv.classList.add('hidden');
        formDiv.classList.remove('hidden');
    } else {
        viewDiv.classList.remove('hidden');
        formDiv.classList.add('hidden');
    }
}

function cancelEdit(formId) {
    const viewDiv = document.getElementById(formId === 'adminForm' ? 'adminView' : 'schoolView');
    const formDiv = document.getElementById(formId);
    
    viewDiv.classList.remove('hidden');
    formDiv.classList.add('hidden');
}

// Wilayah dropdown dengan Laravolt Indonesia
$(document).ready(function() {
    const provinsi = $('#provinsi');
    const kota = $('#kota');
    const kecamatan = $('#kecamatan');
    const kelurahan = $('#kelurahan');
    
    // Hidden inputs untuk nama wilayah
    const provinsiName = $('#Provinsi');
    const kotaName = $('#KotaKabupaten');
    const kecamatanName = $('#Kecamatan');
    const kelurahanName = $('#Kelurahan');
    
    // Set initial nama provinsi jika ada
    if (provinsi.val()) {
        const selectedProv = provinsi.find('option:selected');
        provinsiName.val(selectedProv.data('name') || selectedProv.text());
    }
    
    // Fungsi untuk load kota
    function loadKota(provCode, selectedKota = '') {
        if (!provCode) return;
        
        $.ajax({
            url: `/api/cities/${provCode}`,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                let options = '<option value="">Pilih Kota/Kabupaten</option>';
                if (data && data.length > 0) {
                    data.forEach(function(item) {
                        const selected = (item.code == selectedKota) ? 'selected' : '';
                        options += `<option value="${item.code}" data-name="${item.name}" ${selected}>${item.name}</option>`;
                    });
                    kota.html(options);
                    
                    if (selectedKota) {
                        const selectedCity = kota.find('option:selected');
                        kotaName.val(selectedCity.data('name') || selectedCity.text());
                        loadKecamatan(selectedKota, '{{ old('kecamatan_code', $instansi->kecamatan_code ?? '') }}');
                    }
                } else {
                    kota.html('<option value="">Tidak ada data kota</option>');
                }
            },
            error: function(xhr) {
                console.error('Error loading cities:', xhr);
                kota.html('<option value="">Error loading data</option>');
            }
        });
    }
    
    // Fungsi untuk load kecamatan
    function loadKecamatan(kotaCode, selectedKec = '') {
        if (!kotaCode) return;
        
        $.ajax({
            url: `/api/districts/${kotaCode}`,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                let options = '<option value="">Pilih Kecamatan</option>';
                if (data && data.length > 0) {
                    data.forEach(function(item) {
                        const selected = (item.code == selectedKec) ? 'selected' : '';
                        options += `<option value="${item.code}" data-name="${item.name}" ${selected}>${item.name}</option>`;
                    });
                    kecamatan.html(options);
                    
                    if (selectedKec) {
                        const selectedDistrict = kecamatan.find('option:selected');
                        kecamatanName.val(selectedDistrict.data('name') || selectedDistrict.text());
                        loadKelurahan(selectedKec, '{{ old('kelurahan_code', $instansi->kelurahan_code ?? '') }}');
                    }
                } else {
                    kecamatan.html('<option value="">Tidak ada data kecamatan</option>');
                }
            },
            error: function(xhr) {
                console.error('Error loading districts:', xhr);
                kecamatan.html('<option value="">Error loading data</option>');
            }
        });
    }
    
    // Fungsi untuk load kelurahan
    function loadKelurahan(kecCode, selectedKel = '') {
        if (!kecCode) return;
        
        $.ajax({
            url: `/api/villages/${kecCode}`,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                let options = '<option value="">Pilih Kelurahan/Desa</option>';
                if (data && data.length > 0) {
                    data.forEach(function(item) {
                        const selected = (item.code == selectedKel) ? 'selected' : '';
                        options += `<option value="${item.code}" data-name="${item.name}" ${selected}>${item.name}</option>`;
                    });
                    kelurahan.html(options);
                    
                    if (selectedKel) {
                        const selectedVillage = kelurahan.find('option:selected');
                        kelurahanName.val(selectedVillage.data('name') || selectedVillage.text());
                    }
                } else {
                    kelurahan.html('<option value="">Tidak ada data kelurahan</option>');
                }
            },
            error: function(xhr) {
                console.error('Error loading villages:', xhr);
                kelurahan.html('<option value="">Error loading data</option>');
            }
        });
    }
    
    // Provinsi change handler
    provinsi.on('change', function() {
        const provCode = $(this).val();
        const selectedOption = $(this).find('option:selected');
        provinsiName.val(selectedOption.data('name') || selectedOption.text());
        
        if (provCode) {
            loadKota(provCode);
        } else {
            kota.html('<option value="">Pilih Kota/Kabupaten</option>');
            kecamatan.html('<option value="">Pilih Kecamatan</option>');
            kelurahan.html('<option value="">Pilih Kelurahan/Desa</option>');
            kotaName.val('');
            kecamatanName.val('');
            kelurahanName.val('');
        }
    });
    
    // Kota change handler
    kota.on('change', function() {
        const kotaCode = $(this).val();
        const selectedOption = $(this).find('option:selected');
        kotaName.val(selectedOption.data('name') || selectedOption.text());
        
        if (kotaCode) {
            loadKecamatan(kotaCode);
        } else {
            kecamatan.html('<option value="">Pilih Kecamatan</option>');
            kelurahan.html('<option value="">Pilih Kelurahan/Desa</option>');
            kecamatanName.val('');
            kelurahanName.val('');
        }
    });
    
    // Kecamatan change handler
    kecamatan.on('change', function() {
        const kecCode = $(this).val();
        const selectedOption = $(this).find('option:selected');
        kecamatanName.val(selectedOption.data('name') || selectedOption.text());
        
        if (kecCode) {
            loadKelurahan(kecCode);
        } else {
            kelurahan.html('<option value="">Pilih Kelurahan/Desa</option>');
            kelurahanName.val('');
        }
    });
    
    // Kelurahan change handler
    kelurahan.on('change', function() {
        const selectedOption = $(this).find('option:selected');
        kelurahanName.val(selectedOption.data('name') || selectedOption.text());
    });
    
    // Load data jika ada old value atau existing data
    @if(old('provinsi_code', $instansi->provinsi_code ?? ''))
        const initialProvCode = '{{ old('provinsi_code', $instansi->provinsi_code ?? '') }}';
        const initialKotaCode = '{{ old('kota_code', $instansi->kota_code ?? '') }}';
        const initialKecCode = '{{ old('kecamatan_code', $instansi->kecamatan_code ?? '') }}';
        const initialKelCode = '{{ old('kelurahan_code', $instansi->kelurahan_code ?? '') }}';
        
        if (initialProvCode) {
            loadKota(initialProvCode, initialKotaCode);
        }
    @endif
});
</script>
@endsection