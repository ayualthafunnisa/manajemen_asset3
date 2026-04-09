@extends('layouts.app')

@section('title', 'Tambah User - Jobie')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-neutral-900">Tambah User Baru</h1>
        <p class="mt-1 text-sm text-neutral-500">Isi form berikut untuk menambahkan pengguna baru dalam instansi Anda</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('user.index') }}" 
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900">Form User</h2>
                    <p class="text-sm text-neutral-500">Lengkapi data pengguna dengan benar</p>
                </div>
            </div>
        </div>

        {{-- Form Body --}}
        <div class="px-8 py-8">
            <form action="{{ route('user.store') }}" method="POST" id="userForm" class="space-y-8">
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
                                User baru akan otomatis terdaftar di instansi:
                                <strong class="font-semibold">{{ auth()->user()->instansi->NamaSekolah ?? '-' }}</strong>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Form Grid --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Left Column --}}
                    <div class="space-y-6">
                        {{-- Nama Lengkap --}}
                        <div class="space-y-2">
                            <label for="name" class="block text-sm font-semibold text-neutral-700">
                                Nama Lengkap <span class="text-danger-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <input type="text" name="name" id="name"
                                       value="{{ old('name') }}"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('name') border-danger-500 @enderror"
                                       placeholder="Masukkan nama lengkap"
                                       required>
                            </div>
                            @error('name')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-semibold text-neutral-700">
                                Email <span class="text-danger-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <input type="email" name="email" id="email"
                                       value="{{ old('email') }}"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('email') border-danger-500 @enderror"
                                       placeholder="user@example.com"
                                       required>
                            </div>
                            @error('email')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nomor Telepon --}}
                        <div class="space-y-2">
                            <label for="phone" class="block text-sm font-semibold text-neutral-700">Nomor Telepon</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <input type="text" name="phone" id="phone"
                                       value="{{ old('phone') }}"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('phone') border-danger-500 @enderror"
                                       placeholder="Contoh: 081234567890">
                            </div>
                            @error('phone')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="space-y-6">
                        {{-- Password --}}
                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-semibold text-neutral-700">
                                Password <span class="text-danger-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <input type="password" name="password" id="password"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('password') border-danger-500 @enderror"
                                       placeholder="Minimal 6 karakter"
                                       required>
                            </div>
                            @error('password')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="space-y-2">
                            <label for="password_confirmation" class="block text-sm font-semibold text-neutral-700">
                                Konfirmasi Password <span class="text-danger-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200"
                                       placeholder="Ulangi password"
                                       required>
                            </div>
                        </div>

                        {{-- Status Akun --}}
                        <div class="space-y-2">
                            <label for="status" class="block text-sm font-semibold text-neutral-700">
                                Status Akun <span class="text-danger-500">*</span>
                            </label>
                            <select name="status" id="status"
                                    class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('status') border-danger-500 @enderror"
                                    required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                            @error('status')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Role Selection --}}
                <div class="space-y-4">
                    <label class="block text-sm font-semibold text-neutral-700">
                        Pilih Role Pengguna <span class="text-danger-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div id="rolePetugasCard" 
                            onclick="selectRole('petugas')" 
                            class="role-card cursor-pointer p-6 border-2 rounded-xl text-center transition-all duration-300 border-neutral-200 hover:border-primary-300 group">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-neutral-100 group-hover:bg-primary-100 flex items-center justify-center transition-colors shadow-sm">
                                <svg class="w-8 h-8 text-neutral-600 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-neutral-900 mb-1">Petugas</h4>
                            <p class="text-sm text-neutral-500">Input data aset dan kelola laporan harian instansi</p>
                        </div>

                        <div id="roleTeknisiCard" 
                            onclick="selectRole('teknisi')" 
                            class="role-card cursor-pointer p-6 border-2 rounded-xl text-center transition-all duration-300 border-neutral-200 hover:border-primary-300 group">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-neutral-100 group-hover:bg-primary-100 flex items-center justify-center transition-colors shadow-sm">
                                <svg class="w-8 h-8 text-neutral-600 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-neutral-900 mb-1">Teknisi</h4>
                            <p class="text-sm text-neutral-500">Menangani perbaikan dan pemeliharaan teknis aset</p>
                        </div>
                    </div>
                    
                    <input type="hidden" name="role" id="role_input" value="{{ old('role') }}" required>
                    
                    @error('role')
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
                        <a href="{{ route('user.index') }}" 
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
function selectRole(role) {
    document.getElementById('role_input').value = role;

    const cards = document.querySelectorAll('.role-card');
    cards.forEach(card => {
        card.classList.remove('border-primary-600', 'bg-primary-50', 'ring-2', 'ring-primary-500', 'border-danger-500');
        card.classList.add('border-neutral-200', 'bg-white');
    });

    const selectedCard = role === 'petugas' ? document.getElementById('rolePetugasCard') : document.getElementById('roleTeknisiCard');
    selectedCard.classList.remove('border-neutral-200');
    selectedCard.classList.add('border-primary-600', 'bg-primary-50', 'ring-2', 'ring-primary-500');
}

document.addEventListener('DOMContentLoaded', function () {
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
    
    const requiredInputs = document.querySelectorAll('[required]');
    requiredInputs.forEach(input => {
        if (input.type !== 'hidden') {
            input.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('border-danger-500');
                    this.classList.add('border-neutral-200');
                }
            });
        }
    });
    
    const oldRole = "{{ old('role') }}";
    if (oldRole) {
        selectRole(oldRole);
    }
});

function confirmSubmit() {
    const form = document.getElementById('userForm');
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    let firstInvalid = null;
    let errorMessages = [];
    
    requiredFields.forEach(field => {
        if (field.type === 'hidden') return;
        
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('border-danger-500');
            field.classList.remove('border-neutral-200');
            
            const label = document.querySelector(`label[for="${field.id}"]`);
            let fieldName = label ? label.innerText.replace('*', '').trim() : field.name;
            if (field.id === 'password_confirmation') fieldName = 'Konfirmasi Password';
            errorMessages.push(`${fieldName} wajib diisi`);
            if (!firstInvalid) firstInvalid = field;
        } else {
            field.classList.remove('border-danger-500');
            field.classList.add('border-neutral-200');
        }
    });
    
    const roleInput = document.getElementById('role_input');
    if (!roleInput.value.trim()) {
        isValid = false;
        errorMessages.push('Role Pengguna wajib dipilih');
        const cards = document.querySelectorAll('.role-card');
        cards.forEach(card => card.classList.add('border-danger-500'));
        if (!firstInvalid) firstInvalid = document.getElementById('rolePetugasCard');
    }
    
    const password = document.getElementById('password').value;
    const passwordConfirmation = document.getElementById('password_confirmation').value;
    if (password !== passwordConfirmation) {
        isValid = false;
        document.getElementById('password_confirmation').classList.add('border-danger-500');
        errorMessages.push('Password tidak cocok');
        if (!firstInvalid) firstInvalid = document.getElementById('password_confirmation');
    }
    
    if (password.length > 0 && password.length < 6) {
        isValid = false;
        document.getElementById('password').classList.add('border-danger-500');
        errorMessages.push('Password minimal 6 karakter');
        if (!firstInvalid) firstInvalid = document.getElementById('password');
    }
    
    const email = document.getElementById('email').value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email && !emailRegex.test(email)) {
        isValid = false;
        document.getElementById('email').classList.add('border-danger-500');
        errorMessages.push('Format email tidak valid');
        if (!firstInvalid) firstInvalid = document.getElementById('email');
    }
    
    if (!isValid) {
        let errorMessage = 'Mohon lengkapi data berikut:\n\n';
        errorMessages.forEach(msg => errorMessage += `• ${msg}\n`);
        alert(errorMessage);
        if (firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            if (firstInvalid.tagName !== 'DIV') firstInvalid.focus();
        }
        return;
    }
    
    if (confirm('Apakah Anda yakin ingin menyimpan data user ini?')) {
        form.submit();
    }
}
</script>
@endpush