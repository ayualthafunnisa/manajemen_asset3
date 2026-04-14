@extends('layouts.app')

@section('title', 'Tambah Lokasi Asset - Jobie')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-neutral-900">Tambah Lokasi Asset Baru</h1>
        <p class="mt-1 text-sm text-neutral-500">Isi form berikut untuk menambahkan lokasi penyimpanan asset baru</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('lokasi.index') }}" 
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900">Form Lokasi Asset</h2>
                    <p class="text-sm text-neutral-500">Lengkapi data lokasi dengan benar</p>
                </div>
            </div>
        </div>

        {{-- Form Body --}}
        <div class="px-8 py-8">
            <form action="{{ route('lokasi.store') }}" method="POST" id="lokasiForm" class="space-y-8">
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
                                Lokasi akan ditambahkan ke instansi:
                                <strong class="font-semibold">{{ Auth::user()->instansi->NamaSekolah ?? '-' }}</strong>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Form Grid --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Left Column --}}
                    <div class="space-y-6">
                        {{-- Kode Lokasi --}}
                        <div class="space-y-2">
                            <label for="KodeLokasi" class="block text-sm font-semibold text-neutral-700">
                                Kode Lokasi <span class="text-danger-500">*</span>
                            </label>
                            <div class="flex gap-2">
                                <div class="relative group flex-1">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-neutral-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                        </svg>
                                    </div>
                                    <input type="text" name="KodeLokasi" id="KodeLokasi"
                                        value="{{ old('KodeLokasi') }}"
                                        class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('KodeLokasi') border-danger-500 @enderror"
                                        placeholder="Contoh: LOK-001"
                                        maxlength="20"
                                        required>
                                </div>
                                <button type="button" onclick="generateKodeLokasi()"
                                        class="px-4 py-3 bg-neutral-100 border-2 border-neutral-200 rounded-lg hover:bg-neutral-200 hover:border-primary-300 transition-all duration-200 group"
                                        title="Generate kode otomatis">
                                    <svg class="w-5 h-5 text-neutral-600 group-hover:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-xs text-neutral-500">Kode lokasi harus unik. Klik tombol refresh untuk generate otomatis</p>
                            @error('KodeLokasi')
                                <p class="text-sm text-danger-600 mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Nama Lokasi --}}
                        <div class="space-y-2">
                            <label for="NamaLokasi" class="block text-sm font-semibold text-neutral-700">
                                Nama Lokasi <span class="text-danger-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <input type="text" name="NamaLokasi" id="NamaLokasi"
                                       value="{{ old('NamaLokasi') }}"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('NamaLokasi') border-danger-500 @enderror"
                                       placeholder="Contoh: Ruang Kelas 10A"
                                       maxlength="100"
                                       required>
                            </div>
                            @error('NamaLokasi')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="space-y-6">
                        {{-- Jenis Lokasi --}}
                        <div class="space-y-2">
                            <label for="JenisLokasi" class="block text-sm font-semibold text-neutral-700">
                                Jenis Lokasi <span class="text-danger-500">*</span>
                            </label>
                            <select name="JenisLokasi" id="JenisLokasi"
                                    class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('JenisLokasi') border-danger-500 @enderror"
                                    required>
                                <option value="">Pilih Jenis Lokasi</option>
                                <option value="ruangan" {{ old('JenisLokasi') == 'ruangan' ? 'selected' : '' }}>Ruangan</option>
                                <option value="gudang" {{ old('JenisLokasi') == 'gudang' ? 'selected' : '' }}>Gudang</option>
                                <option value="laboratorium" {{ old('JenisLokasi') == 'laboratorium' ? 'selected' : '' }}>Laboratorium</option>
                                <option value="lapangan" {{ old('JenisLokasi') == 'lapangan' ? 'selected' : '' }}>Lapangan</option>
                                <option value="lainnya" {{ old('JenisLokasi') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('JenisLokasi')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="space-y-2">
                            <label for="Status" class="block text-sm font-semibold text-neutral-700">
                                Status <span class="text-danger-500">*</span>
                            </label>
                            <select name="Status" id="Status"
                                    class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('Status') border-danger-500 @enderror"
                                    required>
                                <option value="">Pilih Status</option>
                                <option value="active" {{ old('Status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="maintenance" {{ old('Status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="tidak_aktif" {{ old('Status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                <option value="renovasi" {{ old('Status') == 'renovasi' ? 'selected' : '' }}>Renovasi</option>
                            </select>
                            @error('Status')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label for="PenanggungJawab" class="block text-sm font-semibold text-neutral-700">
                            Penanggung Jawab
                        </label>
                        <input type="text" name="PenanggungJawab" id="PenanggungJawab"
                            value="{{ old('PenanggungJawab') }}"
                            class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500"
                            placeholder="Nama penanggung jawab"
                            maxlength="100">
                    </div>

                    {{-- TAMBAH FIELD INI --}}
                    <div class="space-y-2">
                        <label for="NIP" class="block text-sm font-semibold text-neutral-700">
                            NIP
                        </label>
                        <input type="text" name="NIP" id="NIP"
                            value="{{ old('NIP') }}"
                            class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500"
                            placeholder="Contoh: 19801234200101001"
                            maxlength="30">
                    </div>

                    <div class="space-y-2">
                        <label for="TeleponPenanggungJawab" class="block text-sm font-semibold text-neutral-700">
                            Telepon Penanggung Jawab
                        </label>
                        <input type="text" name="TeleponPenanggungJawab" id="TeleponPenanggungJawab"
                            value="{{ old('TeleponPenanggungJawab') }}"
                            class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500"
                            placeholder="Contoh: 081234567890"
                            maxlength="20">
                    </div>

                </div>

                {{-- Keterangan --}}
                <div class="space-y-2">
                    <label for="Keterangan" class="block text-sm font-semibold text-neutral-700">Keterangan</label>
                    <textarea name="Keterangan" id="Keterangan" rows="3"
                              class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 resize-y @error('Keterangan') border-danger-500 @enderror"
                              placeholder="Tambahkan keterangan jika diperlukan">{{ old('Keterangan') }}</textarea>
                    @error('Keterangan')
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
                        <a href="{{ route('lokasi.index') }}" 
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
                            Simpan Lokasi
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
document.addEventListener('DOMContentLoaded', function() {
    // Validasi telepon hanya angka dan +
    const teleponInput = document.getElementById('TeleponPenanggungJawab');
    if (teleponInput) {
        teleponInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9+]/g, '');
        });
    }
    
    // Real-time validation
    const requiredInputs = document.querySelectorAll('[required]');
    requiredInputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('border-danger-500');
                this.classList.add('border-neutral-200');
            }
        });
        input.addEventListener('change', function() {
            if (this.value.trim()) {
                this.classList.remove('border-danger-500');
                this.classList.add('border-neutral-200');
            }
        });
    });
});

function confirmSubmit() {
    const form = document.getElementById('lokasiForm');
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
    
    if (!isValid) {
        let errorMessage = 'Mohon lengkapi data berikut:\n\n';
        errorMessages.forEach(msg => {
            errorMessage += `• ${msg}\n`;
        });
        alert(errorMessage);
        
        if (firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalid.focus();
        }
        return;
    }
    
    if (confirm('Apakah Anda yakin ingin menyimpan data lokasi ini?')) {
        form.submit();
    }
}

window.generateKodeLokasi = function () {
    const date = new Date();
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    document.getElementById('KodeLokasi').value = `LOK-${year}${month}${day}-${random}`;
};
</script>
@endpush