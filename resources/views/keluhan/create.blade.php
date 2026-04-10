@extends('layouts.app')

@section('title', 'Buat Laporan Perbaikan - Jobie')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-neutral-900">Buat Laporan Perbaikan</h1>
        <p class="mt-1 text-sm text-neutral-500">Isi form berikut untuk membuat laporan perbaikan aset</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('keluhan.show', $kerusakan->kerusakanID) }}"
           class="inline-flex items-center px-4 py-2 border border-neutral-300 rounded-lg text-sm font-medium text-neutral-700 bg-white hover:bg-neutral-50 transition-all duration-200 shadow-sm hover:shadow">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900">Form Laporan Perbaikan</h2>
                    <p class="text-sm text-neutral-500">Lengkapi data perbaikan dengan detail</p>
                </div>
            </div>
        </div>

        {{-- Form Body --}}
        <div class="px-8 py-8">
            {{-- Ringkasan Keluhan --}}
            <div class="bg-warning-50 border-l-4 border-warning-500 rounded-r-xl p-5 mb-8">
                <div class="flex flex-wrap items-center gap-6">
                    <div>
                        <p class="text-xs text-warning-600 font-medium">Kode Laporan</p>
                        <p class="text-sm font-semibold text-warning-900 font-mono">{{ $kerusakan->kode_laporan }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-warning-600 font-medium">Aset</p>
                        <p class="text-sm font-semibold text-warning-900">{{ $kerusakan->asset->nama_asset ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-warning-600 font-medium">Lokasi</p>
                        <p class="text-sm font-semibold text-warning-900">{{ $kerusakan->lokasi->NamaLokasi ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-warning-600 font-medium">Jenis Kerusakan</p>
                        <p class="text-sm font-semibold text-warning-900">{{ ucfirst($kerusakan->jenis_kerusakan) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-warning-600 font-medium">Prioritas</p>
                        <p class="text-sm font-semibold text-warning-900">{{ ucfirst($kerusakan->prioritas) }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('keluhan.perbaikan.store', $kerusakan->kerusakanID) }}" method="POST" enctype="multipart/form-data" id="perbaikanForm" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Left Column --}}
                    <div class="space-y-6">
                        {{-- Kode Perbaikan (readonly) --}}
                        <div class="space-y-2">
                            <label for="kode_perbaikan" class="block text-sm font-semibold text-neutral-700">
                                Kode Perbaikan <span class="text-danger-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                    </svg>
                                </div>
                                <input type="text" name="kode_perbaikan" id="kode_perbaikan"
                                       value="{{ old('kode_perbaikan', $kodePerbaikan) }}"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg bg-neutral-50 text-neutral-600 font-mono @error('kode_perbaikan') border-danger-500 @enderror"
                                       readonly>
                            </div>
                            @error('kode_perbaikan')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Mulai Perbaikan --}}
                        <div class="space-y-2">
                            <label for="mulai_perbaikan" class="block text-sm font-semibold text-neutral-700">
                                Mulai Perbaikan
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <input type="date" name="mulai_perbaikan" id="mulai_perbaikan"
                                       value="{{ old('mulai_perbaikan', date('Y-m-d')) }}"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('mulai_perbaikan') border-danger-500 @enderror">
                            </div>
                            @error('mulai_perbaikan')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Komponen Diganti --}}
                        <div class="space-y-2">
                            <label for="komponen_diganti" class="block text-sm font-semibold text-neutral-700">
                                Komponen Diganti <span class="text-neutral-400 text-xs">(Opsional)</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="komponen_diganti" id="komponen_diganti"
                                       value="{{ old('komponen_diganti') }}"
                                       placeholder="Contoh: Baterai, layar, kabel..."
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('komponen_diganti') border-danger-500 @enderror">
                            </div>
                            @error('komponen_diganti')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Biaya Aktual --}}
                        <div class="space-y-2">
                            <label for="biaya_aktual" class="block text-sm font-semibold text-neutral-700">
                                Biaya Aktual <span class="text-neutral-400 text-xs">(Opsional)</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-neutral-500 text-sm pointer-events-none">Rp</span>
                                <input type="number" name="biaya_aktual" id="biaya_aktual"
                                       value="{{ old('biaya_aktual') }}"
                                       min="0" step="1000"
                                       placeholder="0"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('biaya_aktual') border-danger-500 @enderror">
                            </div>
                            @error('biaya_aktual')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="space-y-6">
                        {{-- Status Perbaikan --}}
                        <div class="space-y-2">
                            <label for="status" class="block text-sm font-semibold text-neutral-700">
                                Status Perbaikan <span class="text-danger-500">*</span>
                            </label>
                            <select name="status" id="status"
                                    class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('status') border-danger-500 @enderror"
                                    required>
                                <option value="dalam_perbaikan" {{ old('status') == 'dalam_perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                                <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="tidak_bisa_diperbaiki" {{ old('status') == 'tidak_bisa_diperbaiki' ? 'selected' : '' }}>Tidak Bisa Diperbaiki</option>
                            </select>
                            @error('status')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Selesai Perbaikan --}}
                        <div class="space-y-2" id="selesaiField">
                            <label for="selesai_perbaikan" class="block text-sm font-semibold text-neutral-700">
                                Selesai Perbaikan
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <input type="date" name="selesai_perbaikan" id="selesai_perbaikan"
                                       value="{{ old('selesai_perbaikan') }}"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('selesai_perbaikan') border-danger-500 @enderror">
                            </div>
                            @error('selesai_perbaikan')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Foto Sesudah --}}
                        <div class="space-y-2">
                            <label for="foto_sesudah" class="block text-sm font-semibold text-neutral-700">
                                Foto Sesudah Perbaikan <span class="text-neutral-400 text-xs">(Opsional)</span>
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-neutral-200 border-dashed rounded-lg hover:border-primary-400 hover:bg-primary-50 transition-all duration-200">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <div class="flex text-sm text-neutral-600 justify-center">
                                        <label for="foto_sesudah" class="cursor-pointer font-medium text-primary-600 hover:text-primary-500">
                                            <span>Upload file</span>
                                            <input id="foto_sesudah" name="foto_sesudah" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-neutral-500">Format gambar (JPG, PNG, JPEG) maks. 2MB</p>
                                    <p id="foto_sesudah_name" class="text-xs text-primary-600 mt-2 hidden"></p>
                                </div>
                            </div>
                            @error('foto_sesudah')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Alasan Tidak Bisa (conditional) --}}
                <div class="space-y-2 hidden" id="alasanField">
                    <label for="alasan_tidak_bisa" class="block text-sm font-semibold text-neutral-700">
                        Alasan Tidak Bisa Diperbaiki <span class="text-danger-500">*</span>
                    </label>
                    <textarea name="alasan_tidak_bisa" id="alasan_tidak_bisa" rows="3"
                              placeholder="Jelaskan alasan mengapa aset tidak dapat diperbaiki..."
                              class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-danger-500 focus:border-danger-500 transition-all duration-200 resize-y @error('alasan_tidak_bisa') border-danger-500 @enderror">{{ old('alasan_tidak_bisa') }}</textarea>
                    @error('alasan_tidak_bisa')
                        <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tindakan Perbaikan --}}
                <div class="space-y-2">
                    <label for="tindakan_perbaikan" class="block text-sm font-semibold text-neutral-700">
                        Tindakan Perbaikan <span class="text-danger-500">*</span>
                    </label>
                    <textarea name="tindakan_perbaikan" id="tindakan_perbaikan" rows="4"
                              placeholder="Jelaskan tindakan perbaikan yang dilakukan secara detail..."
                              class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 resize-y @error('tindakan_perbaikan') border-danger-500 @enderror"
                              required>{{ old('tindakan_perbaikan') }}</textarea>
                    @error('tindakan_perbaikan')
                        <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Catatan --}}
                <div class="space-y-2">
                    <label for="catatan_perbaikan" class="block text-sm font-semibold text-neutral-700">
                        Catatan Tambahan <span class="text-neutral-400 text-xs">(Opsional)</span>
                    </label>
                    <textarea name="catatan_perbaikan" id="catatan_perbaikan" rows="3"
                              placeholder="Catatan tambahan jika diperlukan..."
                              class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 resize-y @error('catatan_perbaikan') border-danger-500 @enderror">{{ old('catatan_perbaikan') }}</textarea>
                    @error('catatan_perbaikan')
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
                        <a href="{{ route('keluhan.show', $kerusakan->kerusakanID) }}"
                           class="inline-flex items-center justify-center px-6 py-3 border-2 border-neutral-300 rounded-lg font-medium text-neutral-700 bg-white hover:bg-neutral-50 hover:border-neutral-400 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Batal
                        </a>
                        <button type="button" onclick="confirmSubmit()"
                                class="inline-flex items-center justify-center px-6 py-3 bg-gradient-primary rounded-lg font-medium text-white hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Simpan Laporan Perbaikan
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
    const statusSelect = document.getElementById('status');
    const alasanField = document.getElementById('alasanField');
    const selesaiField = document.getElementById('selesaiField');

    function toggleFields() {
        const val = statusSelect.value;
        alasanField.classList.toggle('hidden', val !== 'tidak_bisa_diperbaiki');
        if (val === 'tidak_bisa_diperbaiki') {
            document.getElementById('alasan_tidak_bisa').required = true;
        } else {
            document.getElementById('alasan_tidak_bisa').required = false;
        }
    }

    statusSelect.addEventListener('change', toggleFields);
    toggleFields();

    // Preview foto sesudah
    const fotoSesudah = document.getElementById('foto_sesudah');
    const fotoSesudahName = document.getElementById('foto_sesudah_name');

    fotoSesudah.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            fotoSesudahName.textContent = `File: ${file.name}`;
            fotoSesudahName.classList.remove('hidden');
        } else {
            fotoSesudahName.classList.add('hidden');
        }
    });

    // Real-time validation
    const requiredInputs = document.querySelectorAll('[required]');
    requiredInputs.forEach(input => {
        if (input.type !== 'file') {
            input.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('border-danger-500');
                    this.classList.add('border-neutral-200');
                }
            });
        }
    });
});

function confirmSubmit() {
    const form = document.getElementById('perbaikanForm');
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    let firstInvalid = null;
    let errorMessages = [];

    requiredFields.forEach(field => {
        if (field.type === 'file') {
            // File tidak wajib, skip
            return;
        }
        if (field.type !== 'hidden' && !field.value.trim()) {
            isValid = false;
            field.classList.add('border-danger-500');
            field.classList.remove('border-neutral-200');
            const label = document.querySelector(`label[for="${field.id}"]`);
            let fieldName = label ? label.innerText.replace('*', '').trim() : field.name;
            errorMessages.push(`${fieldName} wajib diisi`);
            if (!firstInvalid) firstInvalid = field;
        } else {
            field.classList.remove('border-danger-500');
            field.classList.add('border-neutral-200');
        }
    });

    // Validasi tanggal selesai tidak boleh sebelum tanggal mulai
    const mulai = document.getElementById('mulai_perbaikan').value;
    const selesai = document.getElementById('selesai_perbaikan').value;
    if (mulai && selesai && new Date(selesai) < new Date(mulai)) {
        isValid = false;
        document.getElementById('selesai_perbaikan').classList.add('border-danger-500');
        errorMessages.push('Tanggal selesai tidak boleh sebelum tanggal mulai');
        if (!firstInvalid) firstInvalid = document.getElementById('selesai_perbaikan');
    }

    if (!isValid) {
        let errorMessage = 'Mohon lengkapi data berikut:\n\n';
        errorMessages.forEach(msg => errorMessage += `• ${msg}\n`);
        alert(errorMessage);
        if (firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalid.focus();
        }
        return;
    }

    if (confirm('Apakah Anda yakin ingin menyimpan laporan perbaikan ini?')) {
        form.submit();
    }
}
</script>
@endpush