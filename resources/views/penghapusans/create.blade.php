@extends('layouts.app')

@section('title', 'Ajukan Penghapusan Aset - Jobie')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-neutral-900">Ajukan Penghapusan Aset</h1>
        <p class="mt-1 text-sm text-neutral-500">Lakukan pengajuan penghapusan aset baru</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('penghapusan.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-neutral-300 rounded-lg text-sm font-medium text-neutral-700 bg-white hover:bg-neutral-50 transition-all duration-200 shadow-sm hover:shadow">
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
</style>
@endpush

@section('content')
<div class="w-full">
    <div class="bg-white rounded-xl shadow-card border border-neutral-200 overflow-hidden hover:shadow-card-hover transition-shadow duration-300">
        {{-- Header Form --}}
        <div class="px-8 py-6 border-b border-neutral-200 bg-gradient-to-r from-neutral-50 to-white">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-danger-100 rounded-lg">
                    <svg class="w-6 h-6 text-danger-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900">Form Pengajuan Penghapusan</h2>
                    <p class="text-sm text-neutral-500">Lengkapi data penghapusan aset dengan benar</p>
                </div>
            </div>
        </div>

        {{-- Form Body --}}
        <div class="px-8 py-8">
            <form action="{{ route('penghapusan.store') }}" method="POST" id="penghapusanForm" enctype="multipart/form-data" class="space-y-8">
                @csrf

                {{-- Peringatan --}}
                <div class="bg-danger-50 border-l-4 border-danger-500 rounded-r-xl p-5">
                    <div class="flex items-start gap-3">
                        <svg class="h-5 w-5 text-danger-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-danger-800">Penting!</p>
                            <p class="text-sm text-danger-700 mt-1">Penghapusan aset akan mengubah status aset menjadi "dihapus" setelah disetujui oleh admin sekolah. Pastikan data yang diisi sudah benar.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Left Column --}}
                    <div class="space-y-6">
                        {{-- Pilih Aset --}}
                        <div class="space-y-2">
                            <label for="assetID" class="block text-sm font-semibold text-neutral-700">
                                Pilih Aset <span class="text-danger-500">*</span>
                            </label>
                            <select name="assetID" id="assetID"
                                    class="w-full @error('assetID') border-danger-500 @enderror"
                                    required>
                                <option value="">-- Pilih Aset --</option>
                                @foreach($assets as $asset)
                                <option value="{{ $asset->assetID }}"
                                        data-nama="{{ $asset->nama_asset }}"
                                        data-kode="{{ $asset->kode_asset }}"
                                        data-nilai="{{ $asset->nilai_perolehan }}"
                                        data-status="{{ $asset->status_asset }}"
                                        {{ old('assetID') == $asset->assetID ? 'selected' : '' }}>
                                    {{ $asset->kode_asset }} - {{ $asset->nama_asset }} (Rp {{ number_format($asset->nilai_perolehan, 0, ',', '.') }})
                                </option>
                                @endforeach
                            </select>
                            @error('assetID')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror

                            {{-- Info Aset Terpilih --}}
                            <div id="assetInfo" class="hidden mt-3 p-4 bg-neutral-50 rounded-lg border border-neutral-200">
                                <h4 class="font-medium text-neutral-900 mb-2">Informasi Aset:</h4>
                                <div class="grid grid-cols-2 gap-2 text-sm">
                                    <div class="text-neutral-600">Kode Aset:</div>
                                    <div class="font-medium" id="assetKode">-</div>
                                    <div class="text-neutral-600">Nama Aset:</div>
                                    <div class="font-medium" id="assetName">-</div>
                                    <div class="text-neutral-600">Nilai Perolehan:</div>
                                    <div class="font-medium" id="assetNilai">-</div>
                                    <div class="text-neutral-600">Status Aset:</div>
                                    <div class="font-medium" id="assetStatus">-</div>
                                </div>
                            </div>
                        </div>

                        {{-- No Surat --}}
                        <div class="space-y-2">
                            <label for="no_surat_penghapusan" class="block text-sm font-semibold text-neutral-700">
                                No. Surat Penghapusan <span class="text-danger-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="no_surat_penghapusan" id="no_surat_penghapusan"
                                       value="{{ old('no_surat_penghapusan') }}"
                                       placeholder="Contoh: SK/PH/2024/001"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('no_surat_penghapusan') border-danger-500 @enderror"
                                       required>
                            </div>
                            @error('no_surat_penghapusan')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jenis Penghapusan --}}
                        <div class="space-y-2">
                            <label for="jenis_penghapusan" class="block text-sm font-semibold text-neutral-700">
                                Jenis Penghapusan <span class="text-danger-500">*</span>
                            </label>
                            <select name="jenis_penghapusan" id="jenis_penghapusan"
                                    class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('jenis_penghapusan') border-danger-500 @enderror"
                                    required>
                                <option value="">-- Pilih Jenis Penghapusan --</option>
                                <option value="rusak_total" {{ old('jenis_penghapusan') == 'rusak_total' ? 'selected' : '' }}>Rusak Total</option>
                                <option value="usang" {{ old('jenis_penghapusan') == 'usang' ? 'selected' : '' }}>Usang/Teknologi Tertinggal</option>
                                <option value="hilang" {{ old('jenis_penghapusan') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                                <option value="dijual" {{ old('jenis_penghapusan') == 'dijual' ? 'selected' : '' }}>Dijual</option>
                                <option value="hibah" {{ old('jenis_penghapusan') == 'hibah' ? 'selected' : '' }}>Hibah</option>
                                <option value="musnah" {{ old('jenis_penghapusan') == 'musnah' ? 'selected' : '' }}>Musnah</option>
                            </select>
                            @error('jenis_penghapusan')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Alasan Penghapusan --}}
                        <div class="space-y-2">
                            <label for="alasan_penghapusan" class="block text-sm font-semibold text-neutral-700">
                                Alasan Penghapusan <span class="text-danger-500">*</span>
                            </label>
                            <select name="alasan_penghapusan" id="alasan_penghapusan"
                                    class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('alasan_penghapusan') border-danger-500 @enderror"
                                    required>
                                <option value="">-- Pilih Alasan Penghapusan --</option>
                                <option value="kerusakan_permanen" {{ old('alasan_penghapusan') == 'kerusakan_permanen' ? 'selected' : '' }}>Kerusakan Permanen</option>
                                <option value="teknologi_tertinggal" {{ old('alasan_penghapusan') == 'teknologi_tertinggal' ? 'selected' : '' }}>Teknologi Tertinggal</option>
                                <option value="tidak_layak_pakai" {{ old('alasan_penghapusan') == 'tidak_layak_pakai' ? 'selected' : '' }}>Tidak Layak Pakai</option>
                                <option value="kehilangan" {{ old('alasan_penghapusan') == 'kehilangan' ? 'selected' : '' }}>Kehilangan</option>
                                <option value="penggantian" {{ old('alasan_penghapusan') == 'penggantian' ? 'selected' : '' }}>Penggantian Aset</option>
                                <option value="restrukturisasi" {{ old('alasan_penghapusan') == 'restrukturisasi' ? 'selected' : '' }}>Restrukturisasi</option>
                            </select>
                            @error('alasan_penghapusan')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="space-y-6">
                        {{-- Tanggal Pengajuan --}}
                        <div class="space-y-2">
                            <label for="tanggal_pengajuan" class="block text-sm font-semibold text-neutral-700">
                                Tanggal Pengajuan <span class="text-danger-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <input type="date" name="tanggal_pengajuan" id="tanggal_pengajuan"
                                       value="{{ old('tanggal_pengajuan', date('Y-m-d')) }}"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('tanggal_pengajuan') border-danger-500 @enderror"
                                       required>
                            </div>
                            @error('tanggal_pengajuan')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nilai Buku --}}
                        <div class="space-y-2">
                            <label for="nilai_buku" class="block text-sm font-semibold text-neutral-700">
                                Nilai Buku <span class="text-danger-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-neutral-500 text-sm pointer-events-none">Rp</span>
                                <input type="number" name="nilai_buku" id="nilai_buku"
                                       value="{{ old('nilai_buku') }}"
                                       min="0" step="1000"
                                       placeholder="0"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('nilai_buku') border-danger-500 @enderror"
                                       required>
                            </div>
                            @error('nilai_buku')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Harga Jual --}}
                        <div class="space-y-2" id="hargaJualField">
                            <label for="harga_jual" class="block text-sm font-semibold text-neutral-700">
                                Harga Jual <span class="text-neutral-400 text-xs">(Jika Dijual)</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-neutral-500 text-sm pointer-events-none">Rp</span>
                                <input type="number" name="harga_jual" id="harga_jual"
                                       value="{{ old('harga_jual') }}"
                                       min="0" step="1000"
                                       placeholder="0"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('harga_jual') border-danger-500 @enderror">
                            </div>
                            @error('harga_jual')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Dokumen Pendukung --}}
                        <div class="space-y-2">
                            <label for="dokumen_pendukung" class="block text-sm font-semibold text-neutral-700">
                                Dokumen Pendukung <span class="text-neutral-400 text-xs">(Opsional)</span>
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-neutral-200 border-dashed rounded-lg hover:border-primary-400 hover:bg-primary-50 transition-all duration-200">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <div class="flex text-sm text-neutral-600 justify-center">
                                        <label for="dokumen_pendukung" class="cursor-pointer font-medium text-primary-600 hover:text-primary-500">
                                            <span>Upload file</span>
                                            <input id="dokumen_pendukung" name="dokumen_pendukung" type="file" class="sr-only" accept=".pdf,.jpg,.jpeg,.png">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-neutral-500">PDF, JPG, JPEG, PNG maks. 2MB</p>
                                    <p id="dokumen_name" class="text-xs text-primary-600 mt-2 hidden"></p>
                                </div>
                            </div>
                            @error('dokumen_pendukung')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Deskripsi Penghapusan --}}
                <div class="space-y-2">
                    <label for="deskripsi_penghapusan" class="block text-sm font-semibold text-neutral-700">
                        Deskripsi Penghapusan <span class="text-danger-500">*</span>
                    </label>
                    <textarea name="deskripsi_penghapusan" id="deskripsi_penghapusan" rows="4"
                              class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 resize-y @error('deskripsi_penghapusan') border-danger-500 @enderror"
                              required>{{ old('deskripsi_penghapusan') }}</textarea>
                    <p class="text-xs text-neutral-500">Deskripsi minimal 20 karakter</p>
                    @error('deskripsi_penghapusan')
                        <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Keterangan --}}
                <div class="space-y-2">
                    <label for="keterangan" class="block text-sm font-semibold text-neutral-700">
                        Keterangan Tambahan <span class="text-neutral-400 text-xs">(Opsional)</span>
                    </label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                              class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 resize-y @error('keterangan') border-danger-500 @enderror">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Preview Perhitungan --}}
                <div class="mt-4 p-5 bg-primary-50 rounded-xl border border-primary-200">
                    <h3 class="font-semibold text-primary-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Preview Perhitungan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white p-3 rounded-lg shadow-sm">
                            <p class="text-xs text-neutral-500">Nilai Buku</p>
                            <p class="text-lg font-bold text-neutral-900" id="previewNilaiBuku">Rp 0</p>
                        </div>
                        <div class="bg-white p-3 rounded-lg shadow-sm">
                            <p class="text-xs text-neutral-500">Harga Jual</p>
                            <p class="text-lg font-bold text-neutral-900" id="previewHargaJual">Rp 0</p>
                        </div>
                        <div id="previewKerugianContainer" class="hidden">
                            <div class="bg-white p-3 rounded-lg shadow-sm">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-neutral-500" id="previewKerugianLabel">Kerugian/Keuntungan</span>
                                    <span class="text-lg font-bold" id="previewKerugianNilai">Rp 0</span>
                                </div>
                            </div>
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
                        <a href="{{ route('penghapusan.index') }}" 
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
                            Ajukan Penghapusan
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
    // TomSelect untuk Aset
    new TomSelect('#assetID', { 
        searchField: ['text'], 
        maxOptions: 200, 
        highlight: true,
        placeholder: 'Cari aset...'
    });
    
    const assetSelect = document.getElementById('assetID');
    const nilaiBuku = document.getElementById('nilai_buku');
    const hargaJual = document.getElementById('harga_jual');
    const jenisPenghapusan = document.getElementById('jenis_penghapusan');

    // Tampilkan info aset & isi nilai buku otomatis
    assetSelect.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        if (opt.value) {
            document.getElementById('assetKode').textContent = opt.dataset.kode;
            document.getElementById('assetName').textContent = opt.dataset.nama;
            document.getElementById('assetNilai').textContent = 'Rp ' + fmt(parseFloat(opt.dataset.nilai));
            document.getElementById('assetStatus').textContent = opt.dataset.status;
            document.getElementById('assetInfo').classList.remove('hidden');
            if (!nilaiBuku.value) nilaiBuku.value = opt.dataset.nilai;
            updatePreview();
        } else {
            document.getElementById('assetInfo').classList.add('hidden');
        }
    });

    // Toggle harga jual field berdasarkan jenis penghapusan
    jenisPenghapusan.addEventListener('change', function () {
        const hargaJualField = document.getElementById('hargaJualField');
        if (this.value === 'dijual') {
            hargaJualField.style.opacity = '1';
            document.getElementById('harga_jual').required = true;
        } else {
            hargaJualField.style.opacity = '0.5';
            document.getElementById('harga_jual').required = false;
            document.getElementById('harga_jual').value = '';
        }
        updatePreview();
    });

    nilaiBuku.addEventListener('input', updatePreview);
    hargaJual.addEventListener('input', updatePreview);

    function updatePreview() {
        const buku = parseFloat(nilaiBuku.value) || 0;
        const jual = parseFloat(hargaJual.value) || 0;

        document.getElementById('previewNilaiBuku').textContent = 'Rp ' + fmt(buku);
        document.getElementById('previewHargaJual').textContent = 'Rp ' + fmt(jual);

        const container = document.getElementById('previewKerugianContainer');
        if (jual > 0 && jenisPenghapusan.value === 'dijual') {
            const selisih = jual - buku;
            const label = document.getElementById('previewKerugianLabel');
            const nilaiEl = document.getElementById('previewKerugianNilai');

            if (selisih < 0) {
                label.textContent = 'Kerugian';
                nilaiEl.className = 'text-lg font-bold text-danger-600';
            } else if (selisih > 0) {
                label.textContent = 'Keuntungan';
                nilaiEl.className = 'text-lg font-bold text-success-600';
            } else {
                label.textContent = 'Impas';
                nilaiEl.className = 'text-lg font-bold text-neutral-600';
            }
            nilaiEl.textContent = 'Rp ' + fmt(Math.abs(selisih));
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }

    function fmt(n) {
        return Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Preview dokumen
    const dokumenInput = document.getElementById('dokumen_pendukung');
    const dokumenName = document.getElementById('dokumen_name');
    dokumenInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            dokumenName.textContent = `File: ${file.name}`;
            dokumenName.classList.remove('hidden');
        } else {
            dokumenName.classList.add('hidden');
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

    // Trigger jika ada old value
    if (assetSelect.value) assetSelect.dispatchEvent(new Event('change'));
    if (jenisPenghapusan.value) jenisPenghapusan.dispatchEvent(new Event('change'));
});

function confirmSubmit() {
    const form = document.getElementById('penghapusanForm');
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    let firstInvalid = null;
    let errorMessages = [];

    requiredFields.forEach(field => {
        if (field.type === 'file') {
            return;
        }
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('border-danger-500');
            field.classList.remove('border-neutral-200');
            const label = document.querySelector(`label[for="${field.id}"]`);
            let fieldName = label ? label.innerText.replace('*', '').trim() : field.name;
            if (field.id === 'assetID') fieldName = 'Aset';
            errorMessages.push(`${fieldName} wajib diisi`);
            if (!firstInvalid) firstInvalid = field;
        } else {
            field.classList.remove('border-danger-500');
            field.classList.add('border-neutral-200');
        }
    });

    const nilaiBuku = parseFloat(document.getElementById('nilai_buku').value) || 0;
    if (nilaiBuku <= 0) {
        isValid = false;
        document.getElementById('nilai_buku').classList.add('border-danger-500');
        errorMessages.push('Nilai buku harus lebih dari 0');
        if (!firstInvalid) firstInvalid = document.getElementById('nilai_buku');
    }

    const deskripsi = document.getElementById('deskripsi_penghapusan');
    if (deskripsi.value.trim().length < 20) {
        isValid = false;
        deskripsi.classList.add('border-danger-500');
        errorMessages.push('Deskripsi penghapusan minimal 20 karakter');
        if (!firstInvalid) firstInvalid = deskripsi;
    }

    const jenisPenghapusan = document.getElementById('jenis_penghapusan').value;
    if (jenisPenghapusan === 'dijual') {
        const hargaJual = document.getElementById('harga_jual');
        if (!hargaJual.value || parseFloat(hargaJual.value) <= 0) {
            isValid = false;
            hargaJual.classList.add('border-danger-500');
            errorMessages.push('Harga jual wajib diisi untuk jenis penghapusan "Dijual"');
            if (!firstInvalid) firstInvalid = hargaJual;
        }
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

    if (confirm('Apakah Anda yakin ingin mengajukan penghapusan aset ini?\n\nSetelah diajukan, penghapusan akan diproses oleh admin sekolah.')) {
        form.submit();
    }
}
</script>
@endpush