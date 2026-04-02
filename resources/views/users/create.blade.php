@extends('layouts.app')

@section('title', 'Tambah User - Jobie')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-white shadow-sm border border-gray-200 px-4 py-3 rounded-lg">
        <li class="breadcrumb-item">
            <a href="" class="text-gray-600 hover:text-purple-600 transition duration-150">Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('user.index') }}" class="text-gray-600 hover:text-purple-600 transition duration-150">Users</a>
        </li>
        <li class="breadcrumb-item active text-gray-800" aria-current="page">Tambah Baru</li>
    </ol>
</nav>
@endsection

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Tambah User Baru</h1>
        <p class="mt-1 text-gray-600">Isi form berikut untuk menambahkan pengguna baru dalam instansi Anda</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('user.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition duration-150 ease-in-out">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

            {{-- InstansiID di-inject otomatis dari controller (dari user yang login) --}}

            <!-- Info instansi otomatis -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            User baru akan otomatis terdaftar di instansi <strong>{{ auth()->user()->instansi->NamaSekolah ?? '-' }}</strong>.
                        </p>
                    </div>
                </div>
            </div>

            <form action="{{ route('user.store') }}" method="POST" id="userForm" class="space-y-6">
                @csrf

                <!-- Nama Lengkap -->
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name"
                           value="{{ old('name') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('name') border-red-500 @enderror"
                           placeholder="Masukkan nama lengkap"
                           required>
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email"
                           value="{{ old('email') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('email') border-red-500 @enderror"
                           placeholder="user@example.com"
                           required>
                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor Telepon -->
                <div class="space-y-2">
                    <label for="phone" class="block text-sm font-medium text-gray-700">
                        Nomor Telepon
                    </label>
                    <input type="text" name="phone" id="phone"
                           value="{{ old('phone') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('phone') border-red-500 @enderror"
                           placeholder="Contoh: 081234567890">
                    @error('phone')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password & Konfirmasi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" id="password"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('password') border-red-500 @enderror"
                               placeholder="Minimal 6 karakter"
                               required>
                        @error('password')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="Ulangi password"
                               required>
                    </div>
                </div>

                <!-- Role & Status -->
                <div class="space-y-4">
                    <label class="block text-sm font-medium text-gray-700">
                        Pilih Role Pengguna <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div id="rolePetugasCard" 
                            onclick="selectRole('petugas')" 
                            class="role-card cursor-pointer p-6 border-2 rounded-xl text-center transition-all duration-300 border-gray-200 hover:border-purple-300 group">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 group-hover:bg-purple-100 flex items-center justify-center transition-colors shadow-sm icon-container">
                                <svg class="w-8 h-8 text-gray-600 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-1">Petugas</h4>
                            <p class="text-sm text-gray-500">Input data aset dan kelola laporan harian instansi</p>
                        </div>

                        <div id="roleTeknisiCard" 
                            onclick="selectRole('teknisi')" 
                            class="role-card cursor-pointer p-6 border-2 rounded-xl text-center transition-all duration-300 border-gray-200 hover:border-green-300 group">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 group-hover:bg-green-100 flex items-center justify-center transition-colors shadow-sm icon-container">
                                <svg class="w-8 h-8 text-gray-600 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-1">Teknisi</h4>
                            <p class="text-sm text-gray-500">Menangani perbaikan dan pemeliharaan teknis aset</p>
                        </div>
                    </div>
                    
                    <input type="hidden" name="role" id="role_input" value="{{ old('role') }}" required>
                    
                    @error('role')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2 mt-6">
                    <label for="status" class="block text-sm font-medium text-gray-700">
                        Status Akun <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('status') border-red-500 @enderror"
                            required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                </div>

                @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
                @endif

                <!-- Form Actions -->
                <div class="pt-6 border-t border-gray-200">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('user.index') }}" 
                           class="px-6 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition duration-150 ease-in-out">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-purple-600 border border-transparent rounded-lg font-medium text-white hover:bg-purple-700 transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            Simpan User
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
    // Hanya izinkan angka pada nomor telepon
    document.getElementById('phone').addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Validasi password cocok sebelum submit
    document.getElementById('userForm').addEventListener('submit', function (e) {
        const pw  = document.getElementById('password').value;
        const pwc = document.getElementById('password_confirmation').value;
        if (pw !== pwc) {
            e.preventDefault();
            document.getElementById('password_confirmation').classList.add('border-red-500');
            // Tampilkan pesan error sederhana
            let errEl = document.getElementById('pw-error');
            if (!errEl) {
                errEl = document.createElement('p');
                errEl.id = 'pw-error';
                errEl.className = 'text-sm text-red-600 mt-1';
                document.getElementById('password_confirmation').parentNode.appendChild(errEl);
            }
            errEl.textContent = 'Password tidak cocok.';
            document.getElementById('password_confirmation').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
});
function selectRole(role) {
    // 1. Set value ke hidden input
    document.getElementById('role_input').value = role;

    // 2. Reset semua style card ke default
    const cards = document.querySelectorAll('.role-card');
    cards.forEach(card => {
        card.classList.remove('border-purple-600', 'bg-purple-50', 'ring-2', 'ring-purple-500', 'border-green-600', 'bg-green-50', 'ring-green-500');
        card.classList.add('border-gray-200', 'bg-white');
    });

    // 3. Tambahkan style "Active" berdasarkan role yang dipilih
    const selectedCard = (role === 'petugas') ? document.getElementById('rolePetugasCard') : document.getElementById('roleTeknisiCard');
    
    if (role === 'petugas') {
        selectedCard.classList.remove('border-gray-200');
        selectedCard.classList.add('border-purple-600', 'bg-purple-50', 'ring-2', 'ring-purple-500');
    } else {
        selectedCard.classList.remove('border-gray-200');
        selectedCard.classList.add('border-green-600', 'bg-green-50', 'ring-2', 'ring-green-500');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Jalankan fungsi selectRole jika ada nilai 'old' (saat validasi error)
    const oldRole = "{{ old('role') }}";
    if (oldRole) {
        selectRole(oldRole);
    }

    // Script validasi nomor telepon yang sudah Anda miliki tetap di sini...
    document.getElementById('phone').addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});
</script>
@endpush