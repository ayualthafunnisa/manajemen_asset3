@extends('layouts.app')

@section('title', 'Edit Laporan Kerusakan - Jobie')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-neutral-900">Edit Laporan Kerusakan</h1>
        <p class="mt-1 text-sm text-neutral-500">Perbarui data laporan kerusakan aset</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('kerusakan.index') }}"
           class="inline-flex items-center px-4 py-2 border border-neutral-300 rounded-lg text-sm font-medium text-neutral-700 bg-white hover:bg-neutral-50 transition-all duration-200 shadow-sm hover:shadow">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar
        </a>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css">
<style>
    .ts-wrapper .ts-control {
        padding: 0.75rem 1rem;
        border: 2px solid #e5e5e5;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        min-height: 52px;
        box-shadow: none;
    }
    .ts-wrapper.focus .ts-control {
        border-color: #7c3aed;
        box-shadow: 0 0 0 2px rgba(124, 58, 237, 0.2);
        outline: none;
    }
    .ts-wrapper .ts-dropdown { 
        border-color: #7c3aed; 
        border-radius: 0.5rem; 
        box-shadow: 0 4px 16px rgba(0,0,0,.10); 
    }
    .ts-wrapper .ts-dropdown .active { 
        background: #ede9fe; 
        color: #5b21b6; 
    }
    #damage-bar-wrap { 
        margin-top: 0.5rem; 
    }
    #damage-bar { 
        height: 8px; 
        border-radius: 99px; 
        transition: width .4s ease, background-color .4s ease; 
    }
    #foto-preview { 
        max-height: 200px; 
        border-radius: 0.5rem; 
        border: 1px solid #e5e7eb; 
        margin-top: 0.5rem; 
    }
    #foto-preview.existing-photo {
        display: block;
    }
    .photo-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: 0.75rem;
        align-items: center;
    }
    .btn-change-photo {
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #4b5563;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-change-photo:hover {
        background: #e5e7eb;
        border-color: #d1d5db;
    }
    .current-photo-info {
        font-size: 0.875rem;
        color: #6b7280;
    }
    .hidden {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="w-full">
    <div class="bg-white rounded-xl shadow-card border border-neutral-200 overflow-hidden hover:shadow-card-hover transition-shadow duration-300">
        {{-- Header Form --}}
        <div class="px-8 py-6 border-b border-neutral-200 bg-gradient-to-r from-neutral-50 to-white">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-warning-100 rounded-lg">
                    <svg class="w-6 h-6 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900">Edit Laporan Kerusakan</h2>
                    <p class="text-sm text-neutral-500">Perbarui data kerusakan dengan detail dan akurat</p>
                </div>
            </div>
        </div>

        {{-- Form Body --}}
        <div class="px-8 py-8">
            <form action="{{ route('kerusakan.update', $kerusakan->kerusakanID) }}" method="POST" enctype="multipart/form-data" id="kerusakanForm" class="space-y-8">
                @csrf
                @method('PUT')

                {{-- Info Penting --}}
                <div class="bg-warning-50 border-l-4 border-warning-500 rounded-r-xl p-5">
                    <div class="flex items-start gap-3">
                        <svg class="h-5 w-5 text-warning-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-warning-800">Informasi Penting</p>
                            <p class="text-sm text-warning-700 mt-1">Status laporan: <strong>{{ ucfirst($kerusakan->status_perbaikan) }}</strong>. {{ $kerusakan->status_perbaikan === 'dilaporkan' ? 'Anda dapat mengedit data ini.' : 'Laporan yang sudah diproses tidak dapat diedit.' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Form Grid --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Left Column --}}
                    <div class="space-y-6">
                        {{-- Pilih Aset --}}
                        <div class="space-y-2">
                            <label for="assetID" class="block text-sm font-semibold text-neutral-700">
                                Pilih Aset <span class="text-danger-500">*</span>
                            </label>
                            <select name="assetID" id="assetID" class="w-full @error('assetID') border-danger-500 @enderror" required>
                                <option value="">-- Pilih Aset --</option>
                                @foreach($assets as $asset)
                                <option value="{{ $asset->assetID }}" {{ old('assetID', $kerusakan->assetID) == $asset->assetID ? 'selected' : '' }}>
                                    {{ $asset->kode_asset }} — {{ $asset->nama_asset }}
                                </option>
                                @endforeach
                            </select>
                            @error('assetID')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Lokasi --}}
                        <div class="space-y-2">
                            <label for="LokasiID" class="block text-sm font-semibold text-neutral-700">
                                Lokasi <span class="text-danger-500">*</span>
                            </label>
                            <select name="LokasiID" id="LokasiID"
                                    class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('LokasiID') border-danger-500 @enderror"
                                    required>
                                <option value="">-- Pilih Lokasi --</option>
                                @foreach($lokasis as $lokasi)
                                <option value="{{ $lokasi->LokasiID }}" {{ old('LokasiID', $kerusakan->LokasiID) == $lokasi->LokasiID ? 'selected' : '' }}>
                                    {{ $lokasi->KodeLokasi }} - {{ $lokasi->NamaLokasi }}
                                </option>
                                @endforeach
                            </select>
                            @error('LokasiID')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tanggal Laporan --}}
                        <div class="space-y-2">
                            <label for="tanggal_laporan" class="block text-sm font-semibold text-neutral-700">
                                Tanggal Laporan <span class="text-danger-500">*</span>
                            </label>
                            <input type="date" name="tanggal_laporan" id="tanggal_laporan"
                                   value="{{ old('tanggal_laporan', $kerusakan->tanggal_laporan->format('Y-m-d')) }}"
                                   class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('tanggal_laporan') border-danger-500 @enderror"
                                   required>
                            @error('tanggal_laporan')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tanggal Kerusakan --}}
                        <div class="space-y-2">
                            <label for="tanggal_kerusakan" class="block text-sm font-semibold text-neutral-700">
                                Tanggal Kerusakan <span class="text-danger-500">*</span>
                            </label>
                            <input type="date" name="tanggal_kerusakan" id="tanggal_kerusakan"
                                   value="{{ old('tanggal_kerusakan', $kerusakan->tanggal_kerusakan->format('Y-m-d')) }}"
                                   class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('tanggal_kerusakan') border-danger-500 @enderror"
                                   required>
                            @error('tanggal_kerusakan')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="space-y-6">
                        {{-- Kode Laporan (readonly) --}}
                        <div class="space-y-2">
                            <label for="kode_laporan" class="block text-sm font-semibold text-neutral-700">Kode Laporan</label>
                            <input type="text" name="kode_laporan" id="kode_laporan"
                                   value="{{ $kerusakan->kode_laporan }}"
                                   readonly
                                   disabled
                                   class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg bg-neutral-50 text-neutral-600">
                            <p class="text-xs text-neutral-500">Kode laporan tidak dapat diubah</p>
                        </div>

                        {{-- Jenis Kerusakan --}}
                        <div class="space-y-2">
                            <label for="jenis_kerusakan" class="block text-sm font-semibold text-neutral-700">
                                Jenis Kerusakan <span class="text-danger-500">*</span>
                            </label>
                            <select name="jenis_kerusakan" id="jenis_kerusakan"
                                    class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('jenis_kerusakan') border-danger-500 @enderror"
                                    required>
                                <option value="">-- Pilih Jenis Kerusakan --</option>
                                <option value="ringan" {{ old('jenis_kerusakan', $kerusakan->jenis_kerusakan) == 'ringan' ? 'selected' : '' }}>Ringan (25%)</option>
                                <option value="sedang" {{ old('jenis_kerusakan', $kerusakan->jenis_kerusakan) == 'sedang' ? 'selected' : '' }}>Sedang (50%)</option>
                                <option value="berat"  {{ old('jenis_kerusakan', $kerusakan->jenis_kerusakan) == 'berat'  ? 'selected' : '' }}>Berat (75%)</option>
                                <option value="total"  {{ old('jenis_kerusakan', $kerusakan->jenis_kerusakan) == 'total'  ? 'selected' : '' }}>Total (100%)</option>
                            </select>
                            @error('jenis_kerusakan')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tingkat Kerusakan (visual only) --}}
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-neutral-700">Tingkat Kerusakan (%)</label>
                            <input type="number" id="tingkat_display" readonly
                                   placeholder="Terisi otomatis dari jenis kerusakan"
                                   value="{{ $kerusakan->tingkat_kerusakan }}"
                                   class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg bg-neutral-50">
                            <div id="damage-bar-wrap">
                                <div class="w-full bg-neutral-200 rounded-full overflow-hidden" style="height:8px;">
                                    <div id="damage-bar" style="width:{{ $kerusakan->tingkat_kerusakan }}%;"></div>
                                </div>
                                <p id="damage-bar-label" class="text-xs text-neutral-500 mt-1"></p>
                            </div>
                        </div>

                        {{-- Estimasi Biaya --}}
                        <div class="space-y-2">
                            <label for="estimasi_biaya" class="block text-sm font-semibold text-neutral-700">Estimasi Biaya (Rp)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-neutral-500 text-sm pointer-events-none">Rp</span>
                                <input type="number" name="estimasi_biaya" id="estimasi_biaya"
                                       value="{{ old('estimasi_biaya', $kerusakan->estimasi_biaya) }}"
                                       min="0" step="1000"
                                       placeholder="0"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('estimasi_biaya') border-danger-500 @enderror">
                            </div>
                            @error('estimasi_biaya')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Deskripsi Kerusakan --}}
                <div class="space-y-2">
                    <label for="deskripsi_kerusakan" class="block text-sm font-semibold text-neutral-700">
                        Deskripsi Kerusakan <span class="text-danger-500">*</span>
                    </label>
                    <textarea name="deskripsi_kerusakan" id="deskripsi_kerusakan" rows="4"
                              class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 resize-y @error('deskripsi_kerusakan') border-danger-500 @enderror"
                              placeholder="Jelaskan kondisi dan lokasi kerusakan secara detail (min. 10 karakter)"
                              required>{{ old('deskripsi_kerusakan', $kerusakan->deskripsi_kerusakan) }}</textarea>
                    <p class="text-xs text-neutral-500">Deskripsi minimal 10 karakter</p>
                    @error('deskripsi_kerusakan')
                        <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Foto Kerusakan --}}
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-neutral-700">
                        Foto Kerusakan
                    </label>
                    
                    {{-- Foto saat ini --}}
                    @if($kerusakan->foto_kerusakan)
                    <div id="current-photo-container" class="mb-3">
                        <div class="flex items-start gap-3 p-3 bg-neutral-50 rounded-lg border border-neutral-200">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-neutral-700">Foto saat ini</p>
                                <p class="text-xs text-neutral-500 mt-1">File: {{ basename($kerusakan->foto_kerusakan) }}</p>
                                <div class="mt-2">
                                    <button type="button" id="view-current-photo" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                                        Lihat Foto
                                    </button>
                                    <span class="mx-2 text-neutral-300">|</span>
                                    <button type="button" id="change-photo-btn" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                                        Ganti Foto
                                    </button>
                                </div>
                            </div>
                        </div>
                        <img id="current-photo-preview" src="{{ Storage::url($kerusakan->foto_kerusakan) }}" 
                             alt="Foto kerusakan saat ini" 
                             class="mt-2 max-h-48 rounded-lg object-cover hidden">
                    </div>
                    @endif

                    {{-- Upload foto baru (hidden by default) --}}
                    <div id="new-photo-upload" class="{{ $kerusakan->foto_kerusakan ? 'hidden' : '' }}">
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-neutral-200 border-dashed rounded-lg hover:border-primary-400 hover:bg-primary-50 transition-all duration-200">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div class="flex text-sm text-neutral-600 justify-center">
                                    <label for="foto_kerusakan" class="cursor-pointer font-medium text-primary-600 hover:text-primary-500">
                                        <span>Upload file baru</span>
                                        <input id="foto_kerusakan" name="foto_kerusakan" type="file" class="sr-only" accept="image/jpg,image/jpeg,image/png,image/webp">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-neutral-500">PNG, JPG, WEBP. Maks 2 MB. Kosongkan jika tidak ingin mengubah foto.</p>
                                <p id="foto_name" class="text-xs text-primary-600 mt-2 hidden"></p>
                            </div>
                        </div>
                        <img id="foto-preview" src="#" alt="Preview foto baru" class="mt-2 max-h-48 rounded-lg object-cover hidden">
                    </div>

                    @error('foto_kerusakan')
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
                        <svg class="h-5 w-5 text-danger-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm text-danger-700">{{ session('error') }}</p>
                    </div>
                </div>
                @endif

                {{-- Form Actions --}}
                <div class="pt-6 border-t-2 border-neutral-100">
                    <div class="flex flex-col sm:flex-row justify-end gap-3">
                        <a href="{{ route('kerusakan.index') }}"
                           class="inline-flex items-center justify-center px-6 py-3 border-2 border-neutral-300 rounded-lg font-medium text-neutral-700 bg-white hover:bg-neutral-50 hover:border-neutral-400 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Batal
                        </a>
                        <button type="button" onclick="confirmSubmit()"
                                class="inline-flex items-center justify-center px-6 py-3 bg-gradient-primary rounded-lg font-medium text-white hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Perbarui Laporan
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
const damageMap = {
    ringan: { pct: 25, color: '#22c55e', label: 'Ringan — kerusakan kecil, masih berfungsi normal' },
    sedang: { pct: 50, color: '#f59e0b', label: 'Sedang — fungsi terganggu, perlu perbaikan segera' },
    berat:  { pct: 75, color: '#f97316', label: 'Berat — hampir tidak berfungsi, butuh perbaikan besar' },
    total:  { pct: 100, color: '#ef4444', label: 'Total — tidak berfungsi sama sekali' },
};

document.addEventListener('DOMContentLoaded', function () {
    new TomSelect('#assetID', { searchField: ['text'], maxOptions: 200, highlight: true });

    const jenisEl = document.getElementById('jenis_kerusakan');
    const displayEl = document.getElementById('tingkat_display');
    const barWrap = document.getElementById('damage-bar-wrap');
    const bar = document.getElementById('damage-bar');
    const barLabel = document.getElementById('damage-bar-label');

    function updateBar(val) {
        if (!val || !damageMap[val]) {
            return;
        }
        const d = damageMap[val];
        displayEl.value = d.pct;
        bar.style.width = d.pct + '%';
        bar.style.backgroundColor = d.color;
        barLabel.textContent = d.label;
        barWrap.style.display = 'block';
    }

    jenisEl.addEventListener('change', () => updateBar(jenisEl.value));
    if (jenisEl.value) updateBar(jenisEl.value);

    // Preview foto baru
    const fotoInput = document.getElementById('foto_kerusakan');
    const fotoPreview = document.getElementById('foto-preview');
    const fotoName = document.getElementById('foto_name');

    fotoInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            fotoName.textContent = `File baru: ${file.name}`;
            fotoName.classList.remove('hidden');
            const reader = new FileReader();
            reader.onload = e => { 
                fotoPreview.src = e.target.result; 
                fotoPreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            fotoName.classList.add('hidden');
            fotoPreview.classList.add('hidden');
        }
    });

    // Toggle upload foto baru
    const changePhotoBtn = document.getElementById('change-photo-btn');
    const currentPhotoContainer = document.getElementById('current-photo-container');
    const newPhotoUpload = document.getElementById('new-photo-upload');
    const viewCurrentPhoto = document.getElementById('view-current-photo');
    const currentPhotoPreview = document.getElementById('current-photo-preview');

    if (changePhotoBtn) {
        changePhotoBtn.addEventListener('click', function() {
            if (currentPhotoContainer) currentPhotoContainer.style.display = 'none';
            if (newPhotoUpload) newPhotoUpload.classList.remove('hidden');
        });
    }

    if (viewCurrentPhoto && currentPhotoPreview) {
        viewCurrentPhoto.addEventListener('click', function() {
            if (currentPhotoPreview.classList.contains('hidden')) {
                currentPhotoPreview.classList.remove('hidden');
                viewCurrentPhoto.textContent = 'Sembunyikan Foto';
            } else {
                currentPhotoPreview.classList.add('hidden');
                viewCurrentPhoto.textContent = 'Lihat Foto';
            }
        });
    }

    // Real-time validation
    const requiredInputs = document.querySelectorAll('[required]');
    requiredInputs.forEach(input => {
        if (input.type !== 'file' && input.id !== 'foto_kerusakan') {
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
    const form = document.getElementById('kerusakanForm');
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    let firstInvalid = null;
    let errorMessages = [];

    requiredFields.forEach(field => {
        if (field.id !== 'foto_kerusakan') {
            if (!field.value.trim()) {
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
        }
    });

    const tglLaporan = document.getElementById('tanggal_laporan').value;
    const tglKerusakan = document.getElementById('tanggal_kerusakan').value;
    if (tglLaporan && tglKerusakan && new Date(tglKerusakan) > new Date(tglLaporan)) {
        isValid = false;
        document.getElementById('tanggal_kerusakan').classList.add('border-danger-500');
        errorMessages.push('Tanggal kerusakan tidak boleh setelah tanggal laporan');
        if (!firstInvalid) firstInvalid = document.getElementById('tanggal_kerusakan');
    }

    const deskripsi = document.getElementById('deskripsi_kerusakan');
    if (deskripsi.value.trim().length < 10) {
        isValid = false;
        deskripsi.classList.add('border-danger-500');
        errorMessages.push('Deskripsi kerusakan minimal 10 karakter');
        if (!firstInvalid) firstInvalid = deskripsi;
    }

    const fotoInput = document.getElementById('foto_kerusakan');
    if (fotoInput.files && fotoInput.files[0]) {
        const fileSize = fotoInput.files[0].size;
        if (fileSize > 2 * 1024 * 1024) {
            isValid = false;
            fotoInput.closest('.border-dashed')?.classList.add('border-danger-500');
            errorMessages.push('Ukuran foto maksimal 2MB');
            if (!firstInvalid) firstInvalid = fotoInput;
        }
    }

    if (!isValid) {
        let errorMessage = 'Mohon lengkapi data berikut:\n\n';
        errorMessages.forEach(msg => errorMessage += `• ${msg}\n`);
        alert(errorMessage);
        if (firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            if (firstInvalid.type !== 'file') firstInvalid.focus();
        }
        return;
    }

    if (confirm('Apakah Anda yakin ingin memperbarui laporan kerusakan ini?')) {
        form.submit();
    }
}
</script>
@endpush