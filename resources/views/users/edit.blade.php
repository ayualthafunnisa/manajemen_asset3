{{-- resources/views/users/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit User - ' . $user->name)

@section('header')
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit User</h1>
        <p class="mt-1 text-sm text-gray-600">
            Edit data user <span class="font-semibold">{{ $user->name }}</span> ({{ $user->email }})
        </p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('user.show', $user->id) }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            Detail
        </a>
        <a href="{{ route('user.index') }}" 
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
        <form action="{{ route('user.update', $user->id) }}" method="POST" id="editForm">
            @csrf
            @method('PUT')
            
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            @endif
            
            {{-- Informasi Dasar Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Dasar</h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nama Lengkap --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" 
                                   value="{{ old('name', $user->name) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('name') border-red-500 @enderror"
                                   placeholder="Nama lengkap"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" 
                                   value="{{ old('email', $user->email) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('email') border-red-500 @enderror"
                                   placeholder="user@example.com"
                                   required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nomor Telepon --}}
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                Nomor Telepon
                            </label>
                            <input type="text" name="phone" id="phone" 
                                   value="{{ old('phone', $user->phone) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('phone') border-red-500 @enderror"
                                   placeholder="08123456789">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Instansi (Read Only) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Instansi
                            </label>
                            <input type="text" 
                                   value="{{ $user->instansi->NamaSekolah ?? ($user->instansi->name ?? ($user->instansi->KodeInstansi ?? 'Tidak ada instansi')) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-700 cursor-not-allowed"
                                   readonly
                                   disabled>
                            <input type="hidden" name="InstansiID" value="{{ $user->InstansiID }}">
                            @error('InstansiID')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Instansi tidak dapat diubah</p>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Role & Status Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Role & Status</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        {{-- Role Selection Cards --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Pilih Role Pengguna <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Petugas Card --}}
                                <div id="rolePetugasCard" 
                                    onclick="selectRole('petugas')" 
                                    class="role-card cursor-pointer p-6 border-2 rounded-xl text-center transition-all duration-300 border-gray-200 hover:border-purple-300 group {{ old('role', $user->role) == 'petugas' ? 'border-purple-600 bg-purple-50 ring-2 ring-purple-500' : 'border-gray-200' }}">
                                    <div class="w-16 h-16 mx-auto mb-4 rounded-full {{ old('role', $user->role) == 'petugas' ? 'bg-purple-100' : 'bg-gray-100' }} group-hover:bg-purple-100 flex items-center justify-center transition-colors shadow-sm">
                                        <svg class="w-8 h-8 {{ old('role', $user->role) == 'petugas' ? 'text-purple-600' : 'text-gray-600' }} group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-1">Petugas</h4>
                                    <p class="text-sm text-gray-500">Input data aset dan kelola laporan harian instansi</p>
                                </div>

                                {{-- Teknisi Card --}}
                                <div id="roleTeknisiCard" 
                                    onclick="selectRole('teknisi')" 
                                    class="role-card cursor-pointer p-6 border-2 rounded-xl text-center transition-all duration-300 border-gray-200 hover:border-green-300 group {{ old('role', $user->role) == 'teknisi' ? 'border-green-600 bg-green-50 ring-2 ring-green-500' : 'border-gray-200' }}">
                                    <div class="w-16 h-16 mx-auto mb-4 rounded-full {{ old('role', $user->role) == 'teknisi' ? 'bg-green-100' : 'bg-gray-100' }} group-hover:bg-green-100 flex items-center justify-center transition-colors shadow-sm">
                                        <svg class="w-8 h-8 {{ old('role', $user->role) == 'teknisi' ? 'text-green-600' : 'text-gray-600' }} group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-1">Teknisi</h4>
                                    <p class="text-sm text-gray-500">Menangani perbaikan dan pemeliharaan teknis aset</p>
                                </div>
                            </div>
                            
                            <input type="hidden" name="role" id="role_input" value="{{ old('role', $user->role) }}" required>
                            
                            @error('role')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Status --}}
                        <div class="mt-6">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                Status Akun <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('status') border-red-500 @enderror"
                                    required>
                                <option value="pending" {{ old('status', $user->status) == 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                                <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active (Aktif)</option>
                                <option value="suspended" {{ old('status', $user->status) == 'suspended' ? 'selected' : '' }}>Suspended (Ditangguhkan)</option>
                                <option value="rejected" {{ old('status', $user->status) == 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                @if($user->status == 'pending')
                                    <span class="text-yellow-600">⚠️ User belum mengaktifkan akun melalui email</span>
                                @elseif($user->status == 'active')
                                    <span class="text-green-600">✓ Akun aktif dan dapat digunakan</span>
                                @elseif($user->status == 'suspended')
                                    <span class="text-red-600">⛔ Akun ditangguhkan, tidak bisa login</span>
                                @elseif($user->status == 'rejected')
                                    <span class="text-red-600">✗ Pendaftaran ditolak</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Reset Password Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Reset Password</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Password Baru
                                <span class="text-xs text-gray-500 ml-1">(kosongkan jika tidak diubah)</span>
                            </label>
                            <input type="password" name="password" id="password" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('password') border-red-500 @enderror"
                                   placeholder="Minimal 8 karakter">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Konfirmasi Password Baru
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="Ulangi password baru">
                        </div>
                        
                        <div class="bg-yellow-50 rounded-lg p-3">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-xs text-yellow-700">
                                    Hanya isi password jika ingin mereset password user. Password baru minimal 8 karakter.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Informasi Tambahan --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Tambahan</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Email Terverifikasi
                            </label>
                            <div class="flex items-center mt-2">
                                @if($user->email_verified_at)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Terverifikasi {{ $user->email_verified_at->format('d/m/Y H:i') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Belum Terverifikasi
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Token Aktivasi
                            </label>
                            @if($user->activation_token)
                                <div class="flex items-center gap-2">
                                    <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ substr($user->activation_token, 0, 20) }}...</code>
                                    @if($user->activation_token_expires_at)
                                        <span class="text-xs text-gray-500">
                                            (Exp: {{ $user->activation_token_expires_at->format('d/m/Y H:i') }})
                                        </span>
                                    @endif
                                </div>
                            @else
                                <p class="text-sm text-gray-500 mt-2">Tidak ada token aktif</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4 pt-4 border-t border-gray-100">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Tanggal Dibuat
                            </label>
                            <p class="text-sm text-gray-900">{{ $user->created_at ? $user->created_at->format('d/m/Y H:i:s') : '-' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Terakhir Diupdate
                            </label>
                            <p class="text-sm text-gray-900">{{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i:s') : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Form Actions --}}
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('user.index') }}" 
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
        {{-- User Info Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-white border-b border-purple-100">
                <h3 class="text-lg font-semibold text-purple-900">Ringkasan User</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-sm text-gray-600">ID User</span>
                        <span class="text-sm font-mono font-semibold text-gray-900">#{{ $user->id }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Nama</span>
                        <span class="text-sm font-medium text-gray-900 text-right">{{ $user->name }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Email</span>
                        <span class="text-sm text-gray-900 text-right break-all">{{ $user->email }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Role</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($user->role == 'super_admin') bg-red-100 text-red-800
                            @elseif($user->role == 'admin_sekolah') bg-blue-100 text-blue-800
                            @elseif($user->role == 'petugas') bg-purple-100 text-purple-800
                            @elseif($user->role == 'teknisi') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Status</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($user->status == 'active') bg-green-100 text-green-800
                            @elseif($user->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($user->status == 'suspended') bg-orange-100 text-orange-800
                            @elseif($user->status == 'rejected') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>
                    @if($user->phone)
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Telepon</span>
                        <span class="text-sm text-gray-900">{{ $user->phone }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Dibuat</span>
                        <span class="text-sm text-gray-900">{{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}</span>
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
                    <a href="{{ route('user.create') }}" 
                       class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition group">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3 group-hover:bg-green-200 transition">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Tambah User Baru</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    
                    @if($user->status == 'pending')
                    <form action="{{ route('user.activate', $user->id) }}" method="POST" class="block">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition group text-left">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3 group-hover:bg-green-200 transition">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Aktivasi User</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </form>
                    @endif
                    
                    <a href="{{ route('user.index') }}" 
                       class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition group">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mr-3 group-hover:bg-purple-200 transition">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Lihat Semua User</span>
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
    // Initialize role selection based on current value
    const currentRole = document.getElementById('role_input').value;
    if (currentRole) {
        // Ensure the selected card is highlighted
        if (currentRole === 'petugas') {
            document.getElementById('rolePetugasCard').classList.add('border-purple-600', 'bg-purple-50', 'ring-2', 'ring-purple-500');
        } else if (currentRole === 'teknisi') {
            document.getElementById('roleTeknisiCard').classList.add('border-green-600', 'bg-green-50', 'ring-2', 'ring-green-500');
        }
    }
    
    // Unsaved changes warning
    let hasChanges = false;
    const form = document.getElementById('editForm');
    const inputs = form.querySelectorAll('input:not([type="password"]), select');
    const initialValues = new Map();
    inputs.forEach(input => {
        if (input.type !== 'password' && input.id !== 'role_input') {
            initialValues.set(input, input.value);
        }
    });
    
    const checkChanges = () => {
        hasChanges = [...inputs].some(input => {
            if (input.type === 'password' || input.id === 'role_input') return false;
            return input.value !== initialValues.get(input);
        });
    };
    
    inputs.forEach(input => {
        if (input.type !== 'password' && input.id !== 'role_input') {
            input.addEventListener('change', checkChanges);
            input.addEventListener('keyup', checkChanges);
        }
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
    
    // Password confirmation validation
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    
    function validatePassword() {
        if (password.value !== passwordConfirmation.value) {
            passwordConfirmation.setCustomValidity('Password tidak sama');
        } else {
            passwordConfirmation.setCustomValidity('');
        }
    }
    
    password.addEventListener('change', validatePassword);
    passwordConfirmation.addEventListener('keyup', validatePassword);
    
    // Phone number validation (only numbers)
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
});

function selectRole(role) {
    // Set value ke hidden input
    document.getElementById('role_input').value = role;

    // Reset semua style card ke default
    const cards = document.querySelectorAll('.role-card');
    cards.forEach(card => {
        card.classList.remove('border-purple-600', 'bg-purple-50', 'ring-2', 'ring-purple-500', 'border-green-600', 'bg-green-50', 'ring-green-500', 'border-red-500');
        card.classList.add('border-gray-200', 'bg-white');
        
        // Reset icon container background
        const iconContainer = card.querySelector('.w-16.h-16');
        if (iconContainer) {
            iconContainer.classList.remove('bg-purple-100', 'bg-green-100');
            iconContainer.classList.add('bg-gray-100');
        }
        
        // Reset icon color
        const icon = card.querySelector('svg');
        if (icon) {
            icon.classList.remove('text-purple-600', 'text-green-600');
            icon.classList.add('text-gray-600');
        }
    });

    // Tambahkan style "Active" berdasarkan role yang dipilih
    const selectedCard = (role === 'petugas') ? document.getElementById('rolePetugasCard') : document.getElementById('roleTeknisiCard');
    const iconContainer = selectedCard.querySelector('.w-16.h-16');
    const icon = selectedCard.querySelector('svg');
    
    if (role === 'petugas') {
        selectedCard.classList.remove('border-gray-200');
        selectedCard.classList.add('border-purple-600', 'bg-purple-50', 'ring-2', 'ring-purple-500');
        if (iconContainer) {
            iconContainer.classList.remove('bg-gray-100');
            iconContainer.classList.add('bg-purple-100');
        }
        if (icon) {
            icon.classList.remove('text-gray-600');
            icon.classList.add('text-purple-600');
        }
    } else {
        selectedCard.classList.remove('border-gray-200');
        selectedCard.classList.add('border-green-600', 'bg-green-50', 'ring-2', 'ring-green-500');
        if (iconContainer) {
            iconContainer.classList.remove('bg-gray-100');
            iconContainer.classList.add('bg-green-100');
        }
        if (icon) {
            icon.classList.remove('text-gray-600');
            icon.classList.add('text-green-600');
        }
    }
}
</script>
@endpush