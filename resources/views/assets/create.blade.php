{{-- resources/views/assets/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Asset Baru - Jobie')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Tambah Asset Baru</h1>
        <p class="mt-1 text-gray-600">Isi form berikut untuk menambahkan asset baru</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('asset.index') }}" 
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
<div class="max-w-6xl mx-auto">
    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
        <div class="p-6 md:p-8">
            <form action="{{ route('asset.store') }}" method="POST" id="assetForm" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- 
                    CATATAN: InstansiID tidak ada di form ini.
                    Di-inject otomatis dari Auth::user()->InstansiID di controller.
                --}}

                <!-- Info Instansi (read-only) -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span class="text-sm font-medium text-blue-800">
                            Asset akan ditambahkan ke instansi: <strong>{{ Auth::user()->instansi->NamaSekolah ?? '-' }}</strong>
                        </span>
                    </div>
                </div>

                <!-- Informasi Dasar -->
                <div class="border-b border-gray-200 pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h2>

                    <!-- Kategori & Lokasi -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kategori -->
                        <div class="space-y-2">
                            <label for="KategoriID" class="block text-sm font-medium text-gray-700">
                                Kategori Asset <span class="text-red-500">*</span>
                            </label>
                            <select name="KategoriID" id="KategoriID" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('KategoriID') border-red-500 @enderror"
                                    required>
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->KategoriID }}" {{ old('KategoriID') == $kategori->KategoriID ? 'selected' : '' }}>
                                        {{ $kategori->KodeKategori }} - {{ $kategori->NamaKategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('KategoriID')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Lokasi -->
                        <div class="space-y-2">
                            <label for="LokasiID" class="block text-sm font-medium text-gray-700">
                                Lokasi Asset <span class="text-red-500">*</span>
                            </label>
                            <select name="LokasiID" id="LokasiID" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('LokasiID') border-red-500 @enderror"
                                    required>
                                <option value="">Pilih Lokasi</option>
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
                    </div>

                    <!-- Kode & Nama Asset -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div class="space-y-2">
                            <label for="kode_asset" class="block text-sm font-medium text-gray-700">
                                Kode Asset <span class="text-red-500">*</span>
                            </label>
                            <div class="flex">
                                <input type="text" name="kode_asset" id="kode_asset" 
                                       value="{{ old('kode_asset') }}"
                                       class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('kode_asset') border-red-500 @enderror"
                                       placeholder="Contoh: AST-20240101-001"
                                       required>
                                <button type="button" onclick="generateKodeAsset()"
                                        class="px-4 py-3 bg-gray-100 border border-l-0 border-gray-300 rounded-r-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500"
                                        title="Generate kode otomatis">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </button>
                            </div>
                            @error('kode_asset')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="nama_asset" class="block text-sm font-medium text-gray-700">
                                Nama Asset <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_asset" id="nama_asset" 
                                   value="{{ old('nama_asset') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('nama_asset') border-red-500 @enderror"
                                   placeholder="Contoh: Laptop Dell XPS 13"
                                   required>
                            @error('nama_asset')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Identitas Asset -->
                <div class="border-b border-gray-200 pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Identitas Asset</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="merk" class="block text-sm font-medium text-gray-700">Merk</label>
                            <input type="text" name="merk" id="merk" 
                                   value="{{ old('merk') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="Contoh: Dell">
                        </div>

                        <div class="space-y-2">
                            <label for="serial_number" class="block text-sm font-medium text-gray-700">Serial Number</label>
                            <input type="text" name="serial_number" id="serial_number" 
                                   value="{{ old('serial_number') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="Contoh: SN-2024-001">
                        </div>
                    </div>
                </div>

                <!-- Informasi Perolehan -->
                <div class="border-b border-gray-200 pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Perolehan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label for="sumber_perolehan" class="block text-sm font-medium text-gray-700">
                                Sumber Perolehan <span class="text-red-500">*</span>
                            </label>
                            <select name="sumber_perolehan" id="sumber_perolehan" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('sumber_perolehan') border-red-500 @enderror"
                                    required>
                                <option value="">Pilih Sumber</option>
                                <option value="pembelian" {{ old('sumber_perolehan') == 'pembelian' ? 'selected' : '' }}>Pembelian</option>
                                <option value="hibah" {{ old('sumber_perolehan') == 'hibah' ? 'selected' : '' }}>Hibah</option>
                                <option value="bantuan_pemerintah" {{ old('sumber_perolehan') == 'bantuan_pemerintah' ? 'selected' : '' }}>Bantuan Pemerintah</option>
                                <option value="produksi_sendiri" {{ old('sumber_perolehan') == 'produksi_sendiri' ? 'selected' : '' }}>Produksi Sendiri</option>
                                <option value="sewa" {{ old('sumber_perolehan') == 'sewa' ? 'selected' : '' }}>Sewa</option>
                            </select>
                            @error('sumber_perolehan')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="tanggal_perolehan" class="block text-sm font-medium text-gray-700">
                                Tanggal Perolehan <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_perolehan" id="tanggal_perolehan" 
                                   value="{{ old('tanggal_perolehan') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('tanggal_perolehan') border-red-500 @enderror"
                                   required>
                            @error('tanggal_perolehan')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="vendor" class="block text-sm font-medium text-gray-700">Vendor/Supplier</label>
                            <input type="text" name="vendor" id="vendor" 
                                   value="{{ old('vendor') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="Nama vendor">
                        </div>

                        <div class="space-y-2">
                            <label for="nilai_perolehan" class="block text-sm font-medium text-gray-700">
                                Nilai Perolehan (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="nilai_perolehan" id="nilai_perolehan" 
                                   value="{{ old('nilai_perolehan') }}"
                                   step="1000" min="0"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('nilai_perolehan') border-red-500 @enderror"
                                   placeholder="Contoh: 15000000"
                                   required>
                            @error('nilai_perolehan')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="nilai_residu" class="block text-sm font-medium text-gray-700">Nilai Residu (Rp)</label>
                            <input type="number" name="nilai_residu" id="nilai_residu" 
                                   value="{{ old('nilai_residu', 0) }}"
                                   step="1000" min="0"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="Contoh: 1000000">
                        </div>

                        <div class="space-y-2">
                            <label for="umur_ekonomis" class="block text-sm font-medium text-gray-700">
                                Umur Ekonomis (Tahun) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="umur_ekonomis" id="umur_ekonomis" 
                                   value="{{ old('umur_ekonomis') }}"
                                   min="1"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('umur_ekonomis') border-red-500 @enderror"
                                   placeholder="Contoh: 5"
                                   required>
                            @error('umur_ekonomis')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status & Kondisi -->
                <div class="border-b border-gray-200 pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Status & Kondisi</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="kondisi" class="block text-sm font-medium text-gray-700">
                                Kondisi <span class="text-red-500">*</span>
                            </label>
                            <select name="kondisi" id="kondisi" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('kondisi') border-red-500 @enderror"
                                    required>
                                <option value="">Pilih Kondisi</option>
                                <option value="baik" {{ old('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="rusak_ringan" {{ old('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                <option value="rusak_berat" {{ old('kondisi') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                <option value="tidak_berfungsi" {{ old('kondisi') == 'tidak_berfungsi' ? 'selected' : '' }}>Tidak Berfungsi</option>
                                <option value="hilang" {{ old('kondisi') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                            </select>
                            @error('kondisi')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="status_asset" class="block text-sm font-medium text-gray-700">
                                Status Asset <span class="text-red-500">*</span>
                            </label>
                            <select name="status_asset" id="status_asset" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('status_asset') border-red-500 @enderror"
                                    required>
                                <option value="">Pilih Status</option>
                                <option value="aktif" {{ old('status_asset') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="non_aktif" {{ old('status_asset') == 'non_aktif' ? 'selected' : '' }}>Non Aktif</option>
                                <option value="dipinjam" {{ old('status_asset') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                <option value="diperbaiki" {{ old('status_asset') == 'diperbaiki' ? 'selected' : '' }}>Diperbaiki</option>
                                <option value="tersimpan" {{ old('status_asset') == 'tersimpan' ? 'selected' : '' }}>Tersimpan</option>
                            </select>
                            @error('status_asset')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Jumlah & Satuan -->
                <div class="border-b border-gray-200 pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Jumlah & Satuan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="jumlah" class="block text-sm font-medium text-gray-700">
                                Jumlah <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="jumlah" id="jumlah" 
                                   value="{{ old('jumlah', 1) }}"
                                   min="1"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('jumlah') border-red-500 @enderror"
                                   required>
                            @error('jumlah')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="satuan" class="block text-sm font-medium text-gray-700">Satuan</label>
                            <input type="text" name="satuan" id="satuan" 
                                   value="{{ old('satuan', 'unit') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="Contoh: unit, buah, set">
                        </div>
                    </div>
                </div>

                <!-- File Pendukung -->
                <div class="border-b border-gray-200 pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">File Pendukung</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Gambar Asset</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-500 transition duration-150">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="gambar_asset" class="cursor-pointer font-medium text-purple-600 hover:text-purple-500">
                                            <span>Upload file</span>
                                            <input id="gambar_asset" name="gambar_asset" type="file" class="sr-only" accept="image/jpg,image/jpeg,image/png">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, JPEG maks. 2MB</p>
                                    <p id="gambar_name" class="text-xs text-purple-600 mt-2 hidden"></p>
                                </div>
                            </div>
                            @error('gambar_asset')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Dokumen Pendukung</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-500 transition duration-150">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="dokumen_pendukung" class="cursor-pointer font-medium text-purple-600 hover:text-purple-500">
                                            <span>Upload file</span>
                                            <input id="dokumen_pendukung" name="dokumen_pendukung" type="file" class="sr-only" accept=".pdf,.doc,.docx">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX maks. 4MB</p>
                                    <p id="dokumen_name" class="text-xs text-purple-600 mt-2 hidden"></p>
                                </div>
                            </div>
                            @error('dokumen_pendukung')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Keterangan -->
                <div class="pb-4">
                    <div class="space-y-2">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                  placeholder="Tambahkan keterangan jika diperlukan">{{ old('keterangan') }}</textarea>
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
                        <a href="{{ route('asset.index') }}" 
                           class="px-6 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition duration-150">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-purple-600 border border-transparent rounded-lg font-medium text-white hover:bg-purple-700 transition duration-150">
                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            Simpan Asset
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
    window.generateKodeAsset = function () {
        const date = new Date();
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
        document.getElementById('kode_asset').value = `AST-${year}${month}${day}-${random}`;
    };

    document.getElementById('gambar_asset').addEventListener('change', function (e) {
        const el = document.getElementById('gambar_name');
        const name = e.target.files[0]?.name;
        el.textContent = name ? `File: ${name}` : '';
        el.classList.toggle('hidden', !name);
    });

    document.getElementById('dokumen_pendukung').addEventListener('change', function (e) {
        const el = document.getElementById('dokumen_name');
        const name = e.target.files[0]?.name;
        el.textContent = name ? `File: ${name}` : '';
        el.classList.toggle('hidden', !name);
    });
});
</script>
@endpush