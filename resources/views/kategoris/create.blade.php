{{-- resources/views/kategoris/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Kategori - Jobie')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Tambah Kategori Baru</h1>
        <p class="mt-1 text-gray-600">Isi form berikut untuk menambahkan kategori baru</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('kategori.index') }}" 
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
            <form action="{{ route('kategori.store') }}" method="POST" id="categoryForm" class="space-y-6">
                @csrf

                {{-- InstansiID di-inject otomatis dari Auth::user()->InstansiID di controller --}}

                <!-- Info Instansi (read-only) -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span class="text-sm font-medium text-blue-800">
                            Kategori akan ditambahkan ke instansi: <strong>{{ Auth::user()->instansi->NamaSekolah ?? '-' }}</strong>
                        </span>
                    </div>
                </div>

                <!-- Kode Kategori -->
                <div class="space-y-2">
                    <label for="KodeKategori" class="block text-sm font-medium text-gray-700">
                        Kode Kategori <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                        </div>
                        <input type="text" 
                               name="KodeKategori" 
                               id="KodeKategori" 
                               value="{{ old('KodeKategori') }}"
                               class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-150 @error('KodeKategori') border-red-500 @enderror"
                               placeholder="Contoh: KT001, ASSET-001"
                               required
                               autofocus>
                    </div>
                    @error('KodeKategori')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Kategori -->
                <div class="space-y-2">
                    <label for="NamaKategori" class="block text-sm font-medium text-gray-700">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <input type="text" 
                               name="NamaKategori" 
                               id="NamaKategori" 
                               value="{{ old('NamaKategori') }}"
                               class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-150 @error('NamaKategori') border-red-500 @enderror"
                               placeholder="Contoh: Elektronik, Furniture, Kendaraan"
                               required>
                    </div>
                    @error('NamaKategori')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="space-y-2">
                    <label for="Deskripsi" class="block text-sm font-medium text-gray-700">
                        Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="Deskripsi" 
                              id="Deskripsi" 
                              rows="5"
                              maxlength="500"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-150 @error('Deskripsi') border-red-500 @enderror"
                              placeholder="Jelaskan detail tentang kategori ini..."
                              required>{{ old('Deskripsi') }}</textarea>
                    @error('Deskripsi')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-sm text-gray-500">Deskripsi maksimal 500 karakter</p>
                        <span id="charCount" class="text-sm text-gray-500">0/500</span>
                    </div>
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
                        <a href="{{ route('kategori.index') }}" 
                           class="px-6 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition duration-150">
                            Batal
                        </a>
                        <button type="button" 
                                onclick="confirmSubmit()"
                                class="px-6 py-3 bg-purple-600 border border-transparent rounded-lg font-medium text-white hover:bg-purple-700 transition duration-150">
                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

    function updateCounter() {
        const len = deskripsi.value.length;
        charCount.textContent = `${len}/500`;
        
        // Update warna counter
        if (len > 500) {
            charCount.classList.add('text-red-500');
            charCount.classList.remove('text-gray-500');
        } else if (len > 450) {
            charCount.classList.add('text-yellow-500');
            charCount.classList.remove('text-gray-500', 'text-red-500');
        } else {
            charCount.classList.remove('text-red-500', 'text-yellow-500');
            charCount.classList.add('text-gray-500');
        }
        
        // Batasi input jika melebihi 500 karakter
        if (len > 500) {
            deskripsi.value = deskripsi.value.substring(0, 500);
            charCount.textContent = `500/500`;
        }
    }

    updateCounter();
    deskripsi.addEventListener('input', updateCounter);
});

// Fungsi konfirmasi sebelum menyimpan
function confirmSubmit() {
    // Validasi form terlebih dahulu
    const form = document.getElementById('categoryForm');
    
    // Cek required fields
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    let firstInvalid = null;
    let errorMessages = [];
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('border-red-500');
            
            // Dapatkan label untuk field yang tidak valid
            const label = document.querySelector(`label[for="${field.id}"]`);
            const fieldName = label ? label.innerText.replace('*', '').trim() : field.name;
            errorMessages.push(`${fieldName} wajib diisi`);
            
            if (!firstInvalid) {
                firstInvalid = field;
            }
        } else {
            field.classList.remove('border-red-500');
        }
    });
    
    // Validasi khusus untuk deskripsi (maksimal 500 karakter)
    const deskripsi = document.getElementById('Deskripsi');
    if (deskripsi && deskripsi.value.length > 500) {
        isValid = false;
        deskripsi.classList.add('border-red-500');
        errorMessages.push('Deskripsi maksimal 500 karakter');
        
        if (!firstInvalid) {
            firstInvalid = deskripsi;
        }
    }
    
    if (!isValid) {
        // Tampilkan pesan error gabungan
        let errorMessage = 'Mohon lengkapi data berikut:\n\n';
        errorMessages.forEach(msg => {
            errorMessage += `• ${msg}\n`;
        });
        alert(errorMessage);
        
        // Scroll ke field yang tidak valid pertama
        if (firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalid.focus();
        }
        return;
    }
    
    // Tampilkan konfirmasi sederhana
    if (confirm('Apakah Anda yakin ingin menyimpan data kategori ini?')) {
        form.submit();
    }
}

// Optional: Tambahkan validasi real-time untuk menghapus border merah saat user mulai mengetik
document.addEventListener('DOMContentLoaded', function() {
    const requiredInputs = document.querySelectorAll('[required]');
    requiredInputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('border-red-500');
            }
        });
    });
});
</script>
@endpush