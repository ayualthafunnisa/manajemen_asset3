@extends('layouts.app')

@section('title', 'Tambah Laporan Kerusakan - Jobie')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Tambah Laporan Kerusakan</h1>
        <p class="mt-1 text-gray-600">Buat laporan kerusakan aset baru</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('kerusakan.index') }}"
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition duration-150 ease-in-out">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css">
<style>
    .ts-wrapper .ts-control {
        padding: 0.625rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        min-height: 48px;
        box-shadow: none;
    }
    .ts-wrapper.focus .ts-control {
        border-color: #8b5cf6;
        box-shadow: 0 0 0 2px rgba(139, 92, 246, 0.3);
        outline: none;
    }
    .ts-wrapper .ts-dropdown { border-color: #8b5cf6; border-radius: 0.5rem; box-shadow: 0 4px 16px rgba(0,0,0,.10); }
    .ts-wrapper .ts-dropdown .active { background: #ede9fe; color: #5b21b6; }
    input[readonly] { background-color: #f3f4f6; cursor: not-allowed; color: #6b7280; }
    #damage-bar-wrap { display: none; margin-top: 0.5rem; }
    #damage-bar { height: 8px; border-radius: 99px; transition: width .4s ease, background-color .4s ease; }
    /* Preview foto */
    #foto-preview { display: none; max-height: 200px; border-radius: 0.5rem; border: 1px solid #e5e7eb; margin-top: 0.5rem; }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
        <div class="p-6 md:p-8">
            <form action="{{ route('kerusakan.store') }}" method="POST"
                  enctype="multipart/form-data" id="kerusakanForm">
                @csrf

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <svg class="h-5 w-5 text-yellow-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <p class="ml-3 text-sm text-yellow-700">
                            <span class="font-bold">Informasi:</span>
                            Pastikan data sesuai kondisi kerusakan. Prioritas terisi otomatis dari jenis kerusakan.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Pilih Aset (Tom Select) --}}
                    <div class="space-y-2 md:col-span-2">
                        <label for="assetID" class="block text-sm font-medium text-gray-700">
                            Pilih Aset <span class="text-red-500">*</span>
                        </label>
                        <select name="assetID" id="assetID"
                                class="w-full @error('assetID') border-red-500 @enderror"
                                required>
                            <option value="">-- Pilih Aset --</option>
                            @foreach($assets as $asset)
                            <option value="{{ $asset->assetID }}" {{ old('assetID') == $asset->assetID ? 'selected' : '' }}>
                                {{ $asset->kode_asset }} — {{ $asset->nama_asset }}
                            </option>
                            @endforeach
                        </select>
                        @error('assetID')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Lokasi --}}
                    <div class="space-y-2">
                        <label for="LokasiID" class="block text-sm font-medium text-gray-700">
                            Lokasi <span class="text-red-500">*</span>
                        </label>
                        <select name="LokasiID" id="LokasiID"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('LokasiID') border-red-500 @enderror"
                                required>
                            <option value="">-- Pilih Lokasi --</option>
                            @foreach($lokasis as $lokasi)
                            <option value="{{ $lokasi->LokasiID }}" {{ old('LokasiID') == $lokasi->LokasiID ? 'selected' : '' }}>
                                {{ $lokasi->KodeLokasi }} - {{ $lokasi->NamaLokasi }}
                            </option>
                            @endforeach
                        </select>
                        @error('LokasiID')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kode Laporan (readonly, auto-generate) --}}
                    <div class="space-y-2">
                        <label for="kode_laporan" class="block text-sm font-medium text-gray-700">
                            Kode Laporan
                        </label>
                        <input type="text" name="kode_laporan" id="kode_laporan"
                               value="{{ old('kode_laporan', $kodeLaporan ?? '') }}"
                               readonly
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg @error('kode_laporan') border-red-500 @enderror">
                        @error('kode_laporan')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Laporan --}}
                    <div class="space-y-2">
                        <label for="tanggal_laporan" class="block text-sm font-medium text-gray-700">
                            Tanggal Laporan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_laporan" id="tanggal_laporan"
                               value="{{ old('tanggal_laporan', date('Y-m-d')) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('tanggal_laporan') border-red-500 @enderror"
                               required>
                        @error('tanggal_laporan')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Kerusakan --}}
                    <div class="space-y-2">
                        <label for="tanggal_kerusakan" class="block text-sm font-medium text-gray-700">
                            Tanggal Kerusakan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_kerusakan" id="tanggal_kerusakan"
                               value="{{ old('tanggal_kerusakan', date('Y-m-d')) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('tanggal_kerusakan') border-red-500 @enderror"
                               required>
                        @error('tanggal_kerusakan')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jenis Kerusakan --}}
                    <div class="space-y-2">
                        <label for="jenis_kerusakan" class="block text-sm font-medium text-gray-700">
                            Jenis Kerusakan <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_kerusakan" id="jenis_kerusakan"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('jenis_kerusakan') border-red-500 @enderror"
                                required>
                            <option value="">-- Pilih Jenis Kerusakan --</option>
                            <option value="ringan" {{ old('jenis_kerusakan') == 'ringan' ? 'selected' : '' }}>Ringan (25%)</option>
                            <option value="sedang" {{ old('jenis_kerusakan') == 'sedang' ? 'selected' : '' }}>Sedang (50%)</option>
                            <option value="berat"  {{ old('jenis_kerusakan') == 'berat'  ? 'selected' : '' }}>Berat (75%)</option>
                            <option value="total"  {{ old('jenis_kerusakan') == 'total'  ? 'selected' : '' }}>Total (100%)</option>
                        </select>
                        @error('jenis_kerusakan')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tingkat Kerusakan (visual only, tidak dikirim — dihitung di controller) --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Tingkat Kerusakan (%)</label>
                        <input type="number" id="tingkat_display"
                               readonly placeholder="Terisi otomatis dari jenis kerusakan"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                        <div id="damage-bar-wrap">
                            <div class="w-full bg-gray-200 rounded-full overflow-hidden" style="height:8px;">
                                <div id="damage-bar" style="width:0%;"></div>
                            </div>
                            <p id="damage-bar-label" class="text-xs text-gray-500 mt-1"></p>
                        </div>
                    </div>

                    {{-- Estimasi Biaya --}}
                    <div class="space-y-2 md:col-span-2">
                        <label for="estimasi_biaya" class="block text-sm font-medium text-gray-700">
                            Estimasi Biaya (Rp)
                        </label>
                        <input type="number" name="estimasi_biaya" id="estimasi_biaya"
                               value="{{ old('estimasi_biaya') }}"
                               min="0" step="1000"
                               placeholder="Masukkan estimasi biaya perbaikan (opsional)"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('estimasi_biaya') border-red-500 @enderror">
                        @error('estimasi_biaya')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Deskripsi Kerusakan --}}
                <div class="mt-6 space-y-2">
                    <label for="deskripsi_kerusakan" class="block text-sm font-medium text-gray-700">
                        Deskripsi Kerusakan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="deskripsi_kerusakan" id="deskripsi_kerusakan" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('deskripsi_kerusakan') border-red-500 @enderror"
                              placeholder="Jelaskan kondisi dan lokasi kerusakan secara detail (min. 10 karakter)"
                              required>{{ old('deskripsi_kerusakan') }}</textarea>
                    @error('deskripsi_kerusakan')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Foto Kerusakan (wajib — kolom NOT NULL di migration) --}}
                <div class="mt-6 space-y-2">
                    <label for="foto_kerusakan" class="block text-sm font-medium text-gray-700">
                        Foto Kerusakan <span class="text-red-500">*</span>
                    </label>
                    <input type="file" name="foto_kerusakan" id="foto_kerusakan"
                           accept="image/*"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('foto_kerusakan') border-red-500 @enderror"
                           required>
                    <p class="text-xs text-gray-500">Format: JPG, PNG, WEBP. Maks 2 MB.</p>
                    <img id="foto-preview" src="#" alt="Preview foto kerusakan">
                    @error('foto_kerusakan')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Form Actions --}}
                <div class="pt-6 border-t border-gray-200 mt-6">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('kerusakan.index') }}"
                           class="px-6 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition duration-150 ease-in-out">
                            Batal
                        </a>
                        <button type="button"
                                onclick="confirmSubmit()"
                                class="px-6 py-3 bg-purple-600 border border-transparent rounded-lg font-medium text-white hover:bg-purple-700 transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Laporan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    new TomSelect('#assetID', {
        searchField: ['text'],
        maxOptions: 200,
        highlight: true,
    });

    // ── Damage bar ────────────────────────────────────────────────────────────
    const damageMap = {
        ringan: { pct: 25,  color: '#22c55e', label: 'Ringan — kerusakan kecil, masih berfungsi normal' },
        sedang: { pct: 50,  color: '#f59e0b', label: 'Sedang — fungsi terganggu, perlu perbaikan segera' },
        berat:  { pct: 75,  color: '#f97316', label: 'Berat — hampir tidak berfungsi, butuh perbaikan besar' },
        total:  { pct: 100, color: '#ef4444', label: 'Total — tidak berfungsi sama sekali' },
    };

    const jenisEl   = document.getElementById('jenis_kerusakan');
    const displayEl = document.getElementById('tingkat_display');
    const barWrap   = document.getElementById('damage-bar-wrap');
    const bar       = document.getElementById('damage-bar');
    const barLabel  = document.getElementById('damage-bar-label');

    function updateBar(val) {
        if (!val || !damageMap[val]) {
            displayEl.value = '';
            barWrap.style.display = 'none';
            return;
        }
        const d = damageMap[val];
        displayEl.value           = d.pct;
        bar.style.width           = d.pct + '%';
        bar.style.backgroundColor = d.color;
        barLabel.textContent      = d.label;
        barWrap.style.display     = 'block';
    }

    jenisEl.addEventListener('change', () => updateBar(jenisEl.value));
    if (jenisEl.value) updateBar(jenisEl.value);

    // ── Preview foto ──────────────────────────────────────────────────────────
    document.getElementById('foto_kerusakan').addEventListener('change', function () {
        const preview = document.getElementById('foto-preview');
        const file    = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });

    // Real-time validation untuk required fields
    const requiredInputs = document.querySelectorAll('[required]');
    requiredInputs.forEach(input => {
        if (input.type !== 'file') {
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
        }
    });
});

// Fungsi konfirmasi sebelum menyimpan
function confirmSubmit() {
    const form = document.getElementById('kerusakanForm');
    
    // Reset error styles
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    let firstInvalid = null;
    let errorMessages = [];
    
    // Validasi required fields
    requiredFields.forEach(field => {
        let fieldValue = field.value;
        
        // Handle khusus untuk file input
        if (field.type === 'file') {
            if (!field.files || field.files.length === 0) {
                isValid = false;
                field.classList.add('border-red-500');
                
                const label = document.querySelector(`label[for="${field.id}"]`);
                const fieldName = label ? label.innerText.replace('*', '').trim() : field.name;
                errorMessages.push(`${fieldName} wajib diunggah`);
                
                if (!firstInvalid) firstInvalid = field;
            } else {
                field.classList.remove('border-red-500');
            }
        } else if (field.type !== 'hidden') {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('border-red-500');
                
                const label = document.querySelector(`label[for="${field.id}"]`);
                const fieldName = label ? label.innerText.replace('*', '').trim() : field.name;
                errorMessages.push(`${fieldName} wajib diisi`);
                
                if (!firstInvalid) firstInvalid = field;
            } else {
                field.classList.remove('border-red-500');
            }
        }
    });
    
    // Validasi khusus: tanggal kerusakan tidak boleh setelah tanggal laporan
    const tanggalLaporan = document.getElementById('tanggal_laporan').value;
    const tanggalKerusakan = document.getElementById('tanggal_kerusakan').value;
    
    if (tanggalLaporan && tanggalKerusakan) {
        const tglLaporan = new Date(tanggalLaporan);
        const tglKerusakan = new Date(tanggalKerusakan);
        
        if (tglKerusakan > tglLaporan) {
            isValid = false;
            document.getElementById('tanggal_kerusakan').classList.add('border-red-500');
            errorMessages.push('Tanggal kerusakan tidak boleh setelah tanggal laporan');
            
            if (!firstInvalid) firstInvalid = document.getElementById('tanggal_kerusakan');
        }
    }
    
    // Validasi deskripsi minimal 10 karakter
    const deskripsi = document.getElementById('deskripsi_kerusakan');
    if (deskripsi.value.trim().length < 10) {
        isValid = false;
        deskripsi.classList.add('border-red-500');
        errorMessages.push('Deskripsi kerusakan minimal 10 karakter');
        
        if (!firstInvalid) firstInvalid = deskripsi;
    }
    
    // Validasi file gambar (ukuran maks 2MB)
    const fotoInput = document.getElementById('foto_kerusakan');
    if (fotoInput.files && fotoInput.files[0]) {
        const fileSize = fotoInput.files[0].size;
        const maxSize = 2 * 1024 * 1024; // 2MB
        
        if (fileSize > maxSize) {
            isValid = false;
            fotoInput.classList.add('border-red-500');
            errorMessages.push('Ukuran foto maksimal 2MB');
            
            if (!firstInvalid) firstInvalid = fotoInput;
        }
    }
    
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
    
    // Tampilkan konfirmasi
    if (confirm('Apakah Anda yakin ingin menyimpan laporan kerusakan ini?')) {
        form.submit();
    }
}
</script>
@endpush