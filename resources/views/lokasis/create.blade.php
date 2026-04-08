@extends('layouts.app')

@section('title', 'Tambah Lokasi Asset - Jobie')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Tambah Lokasi Asset Baru</h1>
        <p class="mt-1 text-gray-600">Isi form berikut untuk menambahkan lokasi penyimpanan asset baru</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('lokasi.index') }}" 
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
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
        <div class="p-6 md:p-8">
            <form action="{{ route('lokasi.store') }}" method="POST" id="lokasiForm" class="space-y-6">
                @csrf

                {{-- InstansiID di-inject otomatis dari controller, tidak perlu dropdown --}}

                <!-- Kode Lokasi & Nama Lokasi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="KodeLokasi" class="block text-sm font-medium text-gray-700">
                            Kode Lokasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="KodeLokasi" id="KodeLokasi"
                               value="{{ old('KodeLokasi') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('KodeLokasi') border-red-500 @enderror"
                               placeholder="Contoh: LOK-001"
                               maxlength="20"
                               required>
                        @error('KodeLokasi')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="NamaLokasi" class="block text-sm font-medium text-gray-700">
                            Nama Lokasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="NamaLokasi" id="NamaLokasi"
                               value="{{ old('NamaLokasi') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('NamaLokasi') border-red-500 @enderror"
                               placeholder="Contoh: Ruang Kelas 10A"
                               maxlength="100"
                               required>
                        @error('NamaLokasi')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Jenis & Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="JenisLokasi" class="block text-sm font-medium text-gray-700">
                            Jenis Lokasi <span class="text-red-500">*</span>
                        </label>
                        <select name="JenisLokasi" id="JenisLokasi"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('JenisLokasi') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Jenis Lokasi</option>
                            <option value="ruangan"      {{ old('JenisLokasi') == 'ruangan'      ? 'selected' : '' }}>Ruangan</option>
                            <option value="gudang"       {{ old('JenisLokasi') == 'gudang'       ? 'selected' : '' }}>Gudang</option>
                            <option value="laboratorium" {{ old('JenisLokasi') == 'laboratorium' ? 'selected' : '' }}>Laboratorium</option>
                            <option value="lapangan"     {{ old('JenisLokasi') == 'lapangan'     ? 'selected' : '' }}>Lapangan</option>
                            <option value="lainnya"      {{ old('JenisLokasi') == 'lainnya'      ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('JenisLokasi')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="Status" class="block text-sm font-medium text-gray-700">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="Status" id="Status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('Status') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Status</option>
                            <option value="active"      {{ old('Status') == 'active'      ? 'selected' : '' }}>Active</option>
                            <option value="maintenance" {{ old('Status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="tidak_aktif" {{ old('Status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            <option value="renovasi"    {{ old('Status') == 'renovasi'    ? 'selected' : '' }}>Renovasi</option>
                        </select>
                        @error('Status')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Penanggung Jawab & Telepon -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="PenanggungJawab" class="block text-sm font-medium text-gray-700">
                            Penanggung Jawab
                        </label>
                        <input type="text" name="PenanggungJawab" id="PenanggungJawab"
                               value="{{ old('PenanggungJawab') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('PenanggungJawab') border-red-500 @enderror"
                               placeholder="Nama penanggung jawab"
                               maxlength="100">
                        @error('PenanggungJawab')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="TeleponPenanggungJawab" class="block text-sm font-medium text-gray-700">
                            Telepon Penanggung Jawab
                        </label>
                        <input type="text" name="TeleponPenanggungJawab" id="TeleponPenanggungJawab"
                               value="{{ old('TeleponPenanggungJawab') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('TeleponPenanggungJawab') border-red-500 @enderror"
                               placeholder="Contoh: 081234567890"
                               maxlength="20">
                        @error('TeleponPenanggungJawab')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Keterangan -->
                <div class="space-y-2">
                    <label for="Keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                    <textarea name="Keterangan" id="Keterangan" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('Keterangan') border-red-500 @enderror"
                              placeholder="Tambahkan keterangan jika diperlukan">{{ old('Keterangan') }}</textarea>
                    @error('Keterangan')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
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
                        <a href="{{ route('lokasi.index') }}" 
                           class="px-6 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition duration-150 ease-in-out">
                            Batal
                        </a>
                        <button type="button" 
                                onclick="confirmSubmit()"
                                class="px-6 py-3 bg-purple-600 border border-transparent rounded-lg font-medium text-white hover:bg-purple-700 transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        
        // Validasi real-time untuk menghapus border merah
        const requiredInputs = document.querySelectorAll('[required]');
        requiredInputs.forEach(input => {
            input.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('border-red-500');
                }
            });
            input.addEventListener('change', function() {
                if (this.value.trim()) {
                    this.classList.remove('border-red-500');
                }
            });
        });
    });
    
    // Fungsi konfirmasi sebelum menyimpan
    function confirmSubmit() {
        // Validasi form terlebih dahulu
        const form = document.getElementById('lokasiForm');
        
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
        
        // Tampilkan konfirmasi
        if (confirm('Apakah Anda yakin ingin menyimpan data lokasi ini?')) {
            form.submit();
        }
    }
</script>
@endpush