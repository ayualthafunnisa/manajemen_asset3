{{-- resources/views/profile/settings.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Pengaturan Profil</h1>
            <p class="text-gray-600 mt-2">Kelola informasi profil dan data sekolah Anda</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8" x-data="activityStats()" x-init="loadStats()">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Aset</p>
                        <p class="text-2xl font-bold text-gray-800" x-text="stats.total_assets">0</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Perawatan</p>
                        <p class="text-2xl font-bold text-gray-800" x-text="stats.total_maintenance">0</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Laporan</p>
                        <p class="text-2xl font-bold text-gray-800" x-text="stats.total_reports">0</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Terakhir Login</p>
                        <p class="text-sm font-semibold text-gray-800 mt-1" x-text="stats.last_login">-</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Messages -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif
        
        @if(session('success_instansi'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success_instansi') }}
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Information -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-800">Informasi Profil</h2>
                        <p class="text-sm text-gray-600">Update informasi pribadi Anda</p>
                    </div>
                    
                    <div class="p-6">
                        <!-- Avatar Upload -->
                        <div class="flex items-center space-x-6 mb-6 pb-6 border-b border-gray-100">
                            <div class="relative">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" 
                                         alt="Avatar" 
                                         class="w-20 h-20 rounded-full object-cover border-2 border-blue-500">
                                @else
                                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white text-2xl font-bold">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                            
                            <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" class="flex-1">
                                @csrf
                                <label class="block">
                                    <span class="text-sm font-medium text-gray-700">Foto Profil</span>
                                    <input type="file" name="avatar" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    @error('avatar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </label>
                                <button type="submit" class="mt-2 text-sm text-blue-600 hover:text-blue-700">Upload foto baru</button>
                            </form>
                        </div>
                        
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                                    <input type="text" value="{{ ucfirst(str_replace('_', ' ', $user->role)) }}" disabled
                                           class="w-full px-4 py-2 rounded-lg border border-gray-200 bg-gray-50 text-gray-600">
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Password Change -->
            <div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-800">Ubah Password</h2>
                        <p class="text-sm text-gray-600">Perbarui password Anda</p>
                    </div>
                    
                    <div class="p-6">
                        <form action="{{ route('profile.password') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                                    <input type="password" name="current_password" 
                                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                                    @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                    <input type="password" name="password" 
                                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation" 
                                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <button type="submit" class="w-full px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm">
                                    Ubah Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Account Settings Links -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6">
                        <h3 class="font-semibold text-gray-800 mb-3">Pengaturan Lainnya</h3>
                        <div class="space-y-2">
                            <a href="{{ route('account.settings') }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition group">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="text-gray-700 group-hover:text-blue-600">Pengaturan Akun</span>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- School Information Section (for admin_sekolah) -->
        @if(auth()->user()->role === 'admin_sekolah' && $instansi)
        <div class="mt-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">
                                <svg class="inline-block w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                Data Instansi/Sekolah
                            </h2>
                            @if($instansiIncomplete)
                            <p class="text-sm text-orange-600 mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                Data sekolah belum lengkap, silakan lengkapi data berikut!
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('instansi.update', $instansi->InstansiID) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Logo Sekolah</label>
                                <div class="flex items-center space-x-4">
                                    @if($instansi->Logo)
                                        <img src="{{ asset('storage/' . $instansi->Logo) }}" alt="Logo" class="w-16 h-16 object-cover rounded-lg border">
                                    @endif
                                    <input type="file" name="Logo" accept="image/*" class="flex-1">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Sekolah <span class="text-red-500">*</span></label>
                                <input type="text" name="NamaSekolah" value="{{ old('NamaSekolah', $instansi->NamaSekolah) }}" 
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                                @error('NamaSekolah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">NPSN <span class="text-red-500">*</span></label>
                                <input type="text" name="NPSN" value="{{ old('NPSN', $instansi->NPSN) }}" 
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                                @error('NPSN') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jenjang Sekolah <span class="text-red-500">*</span></label>
                                <select name="JenjangSekolah" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                                    <option value="">Pilih Jenjang</option>
                                    <option value="SD" {{ old('JenjangSekolah', $instansi->JenjangSekolah) == 'SD' ? 'selected' : '' }}>SD</option>
                                    <option value="SMP" {{ old('JenjangSekolah', $instansi->JenjangSekolah) == 'SMP' ? 'selected' : '' }}>SMP</option>
                                    <option value="SMA" {{ old('JenjangSekolah', $instansi->JenjangSekolah) == 'SMA' ? 'selected' : '' }}>SMA</option>
                                    <option value="SMK" {{ old('JenjangSekolah', $instansi->JenjangSekolah) == 'SMK' ? 'selected' : '' }}>SMK</option>
                                </select>
                                @error('JenjangSekolah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Sekolah <span class="text-red-500">*</span></label>
                                <input type="email" name="EmailSekolah" value="{{ old('EmailSekolah', $instansi->EmailSekolah) }}" 
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                                @error('EmailSekolah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
                                <input type="text" name="Telepon" value="{{ old('Telepon', $instansi->Telepon) }}" 
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                                <textarea name="Alamat" rows="3" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">{{ old('Alamat', $instansi->Alamat) }}</textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kode Pos</label>
                                <input type="text" name="KodePos" value="{{ old('KodePos', $instansi->KodePos) }}" 
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                                @error('KodePos') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kepala Sekolah</label>
                                <input type="text" name="NamaKepalaSekolah" value="{{ old('NamaKepalaSekolah', $instansi->NamaKepalaSekolah) }}" 
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">NIP Kepala Sekolah</label>
                                <input type="text" name="NIPKepalaSekolah" value="{{ old('NIPKepalaSekolah', $instansi->NIPKepalaSekolah) }}" 
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                                <select name="Status" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                                    <option value="Aktif" {{ old('Status', $instansi->Status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Inactive" {{ old('Status', $instansi->Status) == 'Inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @error('Status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm">
                                Simpan Data Sekolah
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function activityStats() {
    return {
        stats: {
            total_assets: 0,
            total_maintenance: 0,
            total_reports: 0,
            last_login: '-'
        },
        
        async loadStats() {
            try {
                const response = await fetch('{{ route("profile.stats") }}');
                const data = await response.json();
                this.stats = data;
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }
    }
}
</script>
@endsection