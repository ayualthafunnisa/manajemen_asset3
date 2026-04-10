@extends('layouts.app')

@section('title', 'Tambah Kategori - Jobie')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-neutral-900">Tambah Kategori Baru</h1>
        <p class="mt-1 text-sm text-neutral-500">Isi form berikut untuk menambahkan kategori baru ke sistem</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('kategori.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-neutral-300 rounded-lg text-sm font-medium text-neutral-700 bg-white hover:bg-neutral-50 transition-all duration-200 shadow-sm hover:shadow">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900">Form Kategori</h2>
                    <p class="text-sm text-neutral-500">Lengkapi data kategori dengan benar</p>
                </div>
            </div>
        </div>

        {{-- Form Body --}}
        <div class="px-8 py-8">
            <form action="{{ route('kategori.store') }}" method="POST" id="categoryForm" class="space-y-8">
                @csrf

                {{-- Instansi Info Card --}}
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
                                Kategori akan ditambahkan ke instansi:
                                <strong class="font-semibold">{{ Auth::user()->instansi->NamaSekolah ?? Auth::user()->instansi->name ?? '-' }}</strong>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- 2 Columns Grid for better layout --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Left Column --}}
                    <div class="space-y-6">
                        {{-- Kode Kategori --}}
                        <div class="space-y-2">
                            <label for="KodeKategori" class="block text-sm font-semibold text-neutral-700">
                                Kode Kategori <span class="text-danger-500">*</span>
                            </label>
                            <div class="flex gap-2">
                                <div class="relative group flex-1">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-neutral-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                        </svg>
                                    </div>
                                    <input type="text" name="KodeKategori" id="KodeKategori"
                                        value="{{ old('KodeKategori') }}"
                                        class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('KodeKategori') border-danger-500 @enderror"
                                        placeholder="Contoh: KAT-001"
                                        maxlength="20"
                                        required>
                                </div>
                                <button type="button" onclick="generateKodeKategori()"
                                        class="px-4 py-3 bg-neutral-100 border-2 border-neutral-200 rounded-lg hover:bg-neutral-200 hover:border-primary-300 transition-all duration-200 group"
                                        title="Generate kode otomatis">
                                    <svg class="w-5 h-5 text-neutral-600 group-hover:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-xs text-neutral-500">Kode kategori harus unik. Klik tombol refresh untuk generate otomatis</p>
                            @error('KodeKategori')
                                <p class="text-sm text-danger-600 mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Nama Kategori --}}
                        <div class="space-y-2">
                            <label for="NamaKategori" class="block text-sm font-semibold text-neutral-700">
                                Nama Kategori <span class="text-danger-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="NamaKategori" 
                                       id="NamaKategori" 
                                       value="{{ old('NamaKategori') }}"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('NamaKategori') border-danger-500 @enderror"
                                       placeholder="Contoh: Elektronik, Furniture, Kendaraan"
                                       required>
                            </div>
                            <p class="text-xs text-neutral-500">Gunakan nama yang deskriptif dan mudah diingat</p>
                            @error('NamaKategori')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="space-y-6">
                        {{-- Deskripsi --}}
                        <div class="space-y-2">
                            <label for="Deskripsi" class="block text-sm font-semibold text-neutral-700">
                                Deskripsi <span class="text-danger-500">*</span>
                            </label>
                            <div class="relative">
                                <textarea name="Deskripsi" 
                                          id="Deskripsi" 
                                          rows="6"
                                          maxlength="500"
                                          class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 resize-y @error('Deskripsi') border-danger-500 @enderror"
                                          placeholder="Jelaskan secara detail tentang kategori ini...">{{ old('Deskripsi') }}</textarea>
                            </div>
                            <div class="flex justify-between items-center mt-2">
                                <p class="text-xs text-neutral-500">Deskripsi maksimal 500 karakter</p>
                                <div class="flex items-center gap-2">
                                    <div class="w-32 h-1.5 bg-neutral-200 rounded-full overflow-hidden">
                                        <div id="charProgress" class="h-full bg-primary-500 transition-all duration-300" style="width: 0%"></div>
                                    </div>
                                    <span id="charCount" class="text-sm font-medium text-neutral-600">0/500</span>
                                </div>
                            </div>
                            @error('Deskripsi')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
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
                        <a href="{{ route('kategori.index') }}" 
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
                            Simpan Kategori
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
    const deskripsi = document.getElementById('Deskripsi');
    const charCount = document.getElementById('charCount');
    const charProgress = document.getElementById('charProgress');

    function updateCounter() {
        const len = deskripsi.value.length;
        const percentage = (len / 500) * 100;
        
        // Update text counter
        charCount.textContent = `${len}/500`;
        
        // Update progress bar
        charProgress.style.width = `${percentage}%`;
        
        // Update colors based on length
        if (len > 500) {
            charCount.classList.add('text-danger-500');
            charCount.classList.remove('text-neutral-600');
            charProgress.classList.add('bg-danger-500');
            charProgress.classList.remove('bg-primary-500', 'bg-warning-500');
        } else if (len > 450) {
            charCount.classList.add('text-warning-500');
            charCount.classList.remove('text-neutral-600', 'text-danger-500');
            charProgress.classList.add('bg-warning-500');
            charProgress.classList.remove('bg-primary-500', 'bg-danger-500');
        } else {
            charCount.classList.remove('text-danger-500', 'text-warning-500');
            charCount.classList.add('text-neutral-600');
            charProgress.classList.remove('bg-danger-500', 'bg-warning-500');
            charProgress.classList.add('bg-primary-500');
        }
        
        // Limit input to 500 characters
        if (len > 500) {
            deskripsi.value = deskripsi.value.substring(0, 500);
            charCount.textContent = `500/500`;
            charProgress.style.width = '100%';
        }
    }

    updateCounter();
    deskripsi.addEventListener('input', updateCounter);
});

// Real-time validation remove error border
document.querySelectorAll('[required]').forEach(input => {
    input.addEventListener('input', function() {
        if (this.value.trim()) {
            this.classList.remove('border-danger-500');
            this.classList.add('border-neutral-200');
        }
    });
});

// Form validation and confirmation
function confirmSubmit() {
    const form = document.getElementById('categoryForm');
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    let firstInvalid = null;
    let errorMessages = [];
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
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
    
    // Validate description length
    const deskripsi = document.getElementById('Deskripsi');
    if (deskripsi && deskripsi.value.length > 500) {
        isValid = false;
        deskripsi.classList.add('border-danger-500');
        errorMessages.push('Deskripsi maksimal 500 karakter');
        if (!firstInvalid) firstInvalid = deskripsi;
    }
    
    if (!isValid) {
        alert(errorMessages.join('\n'));

        if (firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalid.focus();
        }
        return;
    }

    if (confirm('Apakah Anda yakin ingin menyimpan data kategori ini?')) {
        form.submit();
    }
}

window.generateKodeKategori = function () {
    const date = new Date();
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    document.getElementById('KodeKategori').value = `KAT-${year}${month}${day}-${random}`;
};
</script>
@endpush