@extends('layouts.app')

@section('title', 'Ajukan Penghapusan Aset - Jobie')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-white shadow-sm border border-gray-200 px-4 py-3 rounded-lg">
        <li class="breadcrumb-item">
            <a href="" class="text-gray-600 hover:text-purple-600 transition duration-150">Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('penghapusan.index') }}" class="text-gray-600 hover:text-purple-600 transition duration-150">Penghapusan Aset</a>
        </li>
        <li class="breadcrumb-item active text-gray-800" aria-current="page">Ajukan Baru</li>
    </ol>
</nav>
@endsection

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Ajukan Penghapusan Aset</h1>
        <p class="mt-1 text-gray-600">Lakukan pengajuan penghapusan aset baru</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('penghapusan.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition duration-150 ease-in-out">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
        <div class="p-6 md:p-8">
            <form action="{{ route('penghapusan.store') }}" method="POST" id="penghapusanForm" enctype="multipart/form-data">
                @csrf

                {{-- InstansiID, diajukan_oleh, status_penghapusan di-inject otomatis dari controller --}}

                <!-- Peringatan -->
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                <span class="font-bold">Penting:</span> Penghapusan aset akan mengubah status aset menjadi "dihapus" setelah disetujui oleh admin sekolah. Pastikan data yang diisi sudah benar.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Pilih Aset -->
                    <div class="space-y-2 md:col-span-2">
                        <label for="assetID" class="block text-sm font-medium text-gray-700">
                            Pilih Aset <span class="text-red-500">*</span>
                        </label>
                        <select name="assetID" id="assetID"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('assetID') border-red-500 @enderror"
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
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror

                        <!-- Info Aset Terpilih -->
                        <div id="assetInfo" class="hidden mt-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <h4 class="font-medium text-gray-900 mb-2">Informasi Aset:</h4>
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div class="text-gray-600">Kode Aset:</div>
                                <div class="font-medium" id="assetKode">-</div>
                                <div class="text-gray-600">Nama Aset:</div>
                                <div class="font-medium" id="assetName">-</div>
                                <div class="text-gray-600">Nilai Perolehan:</div>
                                <div class="font-medium" id="assetNilai">-</div>
                                <div class="text-gray-600">Status Aset:</div>
                                <div class="font-medium" id="assetStatus">-</div>
                            </div>
                        </div>
                    </div>

                    <!-- No Surat -->
                    <div class="space-y-2">
                        <label for="no_surat_penghapusan" class="block text-sm font-medium text-gray-700">
                            No. Surat Penghapusan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="no_surat_penghapusan" id="no_surat_penghapusan"
                               value="{{ old('no_surat_penghapusan') }}"
                               placeholder="Contoh: SK/PH/2024/001"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('no_surat_penghapusan') border-red-500 @enderror"
                               required>
                        @error('no_surat_penghapusan')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Pengajuan -->
                    <div class="space-y-2">
                        <label for="tanggal_pengajuan" class="block text-sm font-medium text-gray-700">
                            Tanggal Pengajuan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_pengajuan" id="tanggal_pengajuan"
                               value="{{ old('tanggal_pengajuan', date('Y-m-d')) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('tanggal_pengajuan') border-red-500 @enderror"
                               required>
                        @error('tanggal_pengajuan')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Penghapusan -->
                    <div class="space-y-2">
                        <label for="jenis_penghapusan" class="block text-sm font-medium text-gray-700">
                            Jenis Penghapusan <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_penghapusan" id="jenis_penghapusan"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('jenis_penghapusan') border-red-500 @enderror"
                                required>
                            <option value="">-- Pilih Jenis Penghapusan --</option>
                            <option value="rusak_total"  {{ old('jenis_penghapusan') == 'rusak_total'  ? 'selected' : '' }}>Rusak Total</option>
                            <option value="usang"        {{ old('jenis_penghapusan') == 'usang'        ? 'selected' : '' }}>Usang/Teknologi Tertinggal</option>
                            <option value="hilang"       {{ old('jenis_penghapusan') == 'hilang'       ? 'selected' : '' }}>Hilang</option>
                            <option value="dijual"       {{ old('jenis_penghapusan') == 'dijual'       ? 'selected' : '' }}>Dijual</option>
                            <option value="hibah"        {{ old('jenis_penghapusan') == 'hibah'        ? 'selected' : '' }}>Hibah</option>
                            <option value="musnah"       {{ old('jenis_penghapusan') == 'musnah'       ? 'selected' : '' }}>Musnah</option>
                        </select>
                        @error('jenis_penghapusan')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alasan Penghapusan -->
                    <div class="space-y-2">
                        <label for="alasan_penghapusan" class="block text-sm font-medium text-gray-700">
                            Alasan Penghapusan <span class="text-red-500">*</span>
                        </label>
                        <select name="alasan_penghapusan" id="alasan_penghapusan"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('alasan_penghapusan') border-red-500 @enderror"
                                required>
                            <option value="">-- Pilih Alasan Penghapusan --</option>
                            <option value="kerusakan_permanen"  {{ old('alasan_penghapusan') == 'kerusakan_permanen'  ? 'selected' : '' }}>Kerusakan Permanen</option>
                            <option value="teknologi_tertinggal"{{ old('alasan_penghapusan') == 'teknologi_tertinggal'? 'selected' : '' }}>Teknologi Tertinggal</option>
                            <option value="tidak_layak_pakai"   {{ old('alasan_penghapusan') == 'tidak_layak_pakai'   ? 'selected' : '' }}>Tidak Layak Pakai</option>
                            <option value="kehilangan"          {{ old('alasan_penghapusan') == 'kehilangan'          ? 'selected' : '' }}>Kehilangan</option>
                            <option value="penggantian"         {{ old('alasan_penghapusan') == 'penggantian'         ? 'selected' : '' }}>Penggantian Aset</option>
                            <option value="restrukturisasi"     {{ old('alasan_penghapusan') == 'restrukturisasi'     ? 'selected' : '' }}>Restrukturisasi</option>
                        </select>
                        @error('alasan_penghapusan')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nilai Buku -->
                    <div class="space-y-2">
                        <label for="nilai_buku" class="block text-sm font-medium text-gray-700">
                            Nilai Buku <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="nilai_buku" id="nilai_buku"
                               value="{{ old('nilai_buku') }}"
                               min="0" step="1000"
                               placeholder="Masukkan nilai buku terakhir"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('nilai_buku') border-red-500 @enderror"
                               required>
                        @error('nilai_buku')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga Jual -->
                    <div class="space-y-2">
                        <label for="harga_jual" class="block text-sm font-medium text-gray-700">
                            Harga Jual <span class="text-gray-400 text-xs">(Jika Dijual)</span>
                        </label>
                        <input type="number" name="harga_jual" id="harga_jual"
                               value="{{ old('harga_jual') }}"
                               min="0" step="1000"
                               placeholder="Masukkan harga jual"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('harga_jual') border-red-500 @enderror">
                        @error('harga_jual')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dokumen Pendukung -->
                    <div class="space-y-2 md:col-span-2">
                        <label for="dokumen_pendukung" class="block text-sm font-medium text-gray-700">
                            Dokumen Pendukung
                        </label>
                        <input type="file" name="dokumen_pendukung" id="dokumen_pendukung"
                               accept=".pdf,.jpg,.jpeg,.png"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('dokumen_pendukung') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">Format: PDF, JPG, JPEG, PNG. Maksimal 2MB.</p>
                        @error('dokumen_pendukung')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="mt-6 space-y-2">
                    <label for="deskripsi_penghapusan" class="block text-sm font-medium text-gray-700">
                        Deskripsi Penghapusan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="deskripsi_penghapusan" id="deskripsi_penghapusan" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('deskripsi_penghapusan') border-red-500 @enderror"
                              required>{{ old('deskripsi_penghapusan') }}</textarea>
                    @error('deskripsi_penghapusan')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Keterangan -->
                <div class="mt-6 space-y-2">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700">
                        Keterangan Tambahan
                    </label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('keterangan') border-red-500 @enderror">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preview Perhitungan -->
                <div class="mt-8 p-4 bg-purple-50 rounded-lg border border-purple-200">
                    <h3 class="font-medium text-purple-900 mb-3">Preview Perhitungan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white p-3 rounded-lg">
                            <p class="text-xs text-gray-500">Nilai Buku</p>
                            <p class="text-lg font-bold text-gray-900" id="previewNilaiBuku">Rp 0</p>
                        </div>
                        <div class="bg-white p-3 rounded-lg">
                            <p class="text-xs text-gray-500">Harga Jual</p>
                            <p class="text-lg font-bold text-gray-900" id="previewHargaJual">Rp 0</p>
                        </div>
                    </div>
                    <div id="previewKerugianContainer" class="hidden mt-3">
                        <div class="bg-amber-50 p-3 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-amber-700" id="previewKerugianLabel">Kerugian/Keuntungan</span>
                                <span class="text-lg font-bold" id="previewKerugianNilai">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="pt-6 border-t border-gray-200 mt-6">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('penghapusan.index') }}" 
                           class="px-6 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition duration-150 ease-in-out">
                            Batal
                        </a>
                        <button type="button" 
                                onclick="confirmSubmit()"
                                class="px-6 py-3 bg-purple-600 border border-transparent rounded-lg font-medium text-white hover:bg-purple-700 transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const assetSelect = document.getElementById('assetID');
    const nilaiBuku   = document.getElementById('nilai_buku');
    const hargaJual   = document.getElementById('harga_jual');

    // Tampilkan info aset & isi nilai buku otomatis
    assetSelect.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        if (opt.value) {
            document.getElementById('assetKode').textContent   = opt.dataset.kode;
            document.getElementById('assetName').textContent   = opt.dataset.nama;
            document.getElementById('assetNilai').textContent  = 'Rp ' + fmt(parseFloat(opt.dataset.nilai));
            document.getElementById('assetStatus').textContent = opt.dataset.status;
            document.getElementById('assetInfo').classList.remove('hidden');
            if (!nilaiBuku.value) nilaiBuku.value = opt.dataset.nilai;
            updatePreview();
        } else {
            document.getElementById('assetInfo').classList.add('hidden');
        }
    });

    nilaiBuku.addEventListener('input', updatePreview);
    hargaJual.addEventListener('input', updatePreview);

    function updatePreview() {
        const buku = parseFloat(nilaiBuku.value) || 0;
        const jual = parseFloat(hargaJual.value) || 0;

        document.getElementById('previewNilaiBuku').textContent  = 'Rp ' + fmt(buku);
        document.getElementById('previewHargaJual').textContent  = 'Rp ' + fmt(jual);

        const container = document.getElementById('previewKerugianContainer');
        if (jual > 0) {
            const selisih = jual - buku;
            const label   = document.getElementById('previewKerugianLabel');
            const nilaiEl = document.getElementById('previewKerugianNilai');

            if (selisih < 0) {
                label.textContent    = 'Kerugian';
                nilaiEl.className    = 'text-lg font-bold text-red-600';
            } else if (selisih > 0) {
                label.textContent    = 'Keuntungan';
                nilaiEl.className    = 'text-lg font-bold text-green-600';
            } else {
                label.textContent    = 'Impas';
                nilaiEl.className    = 'text-lg font-bold text-gray-600';
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

    // Trigger jika ada old value
    if (assetSelect.value) assetSelect.dispatchEvent(new Event('change'));
});

// Fungsi konfirmasi sebelum menyimpan
function confirmSubmit() {
    const form = document.getElementById('penghapusanForm');
    
    // Reset error styles
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    let firstInvalid = null;
    let errorMessages = [];
    
    // Validasi required fields
    requiredFields.forEach(field => {
        let fieldValue = field.value;
        
        // Handle khusus untuk file input (opsional)
        if (field.type === 'file') {
            // File tidak wajib, skip validasi
            field.classList.remove('border-red-500');
            return;
        }
        
        if (!fieldValue.trim()) {
            isValid = false;
            field.classList.add('border-red-500');
            
            const label = document.querySelector(`label[for="${field.id}"]`);
            let fieldName = label ? label.innerText.replace('*', '').trim() : field.name;
            
            // Handle khusus untuk select aset
            if (field.id === 'assetID') {
                fieldName = 'Aset';
            }
            
            errorMessages.push(`${fieldName} wajib diisi`);
            
            if (!firstInvalid) firstInvalid = field;
        } else {
            field.classList.remove('border-red-500');
        }
    });
    
    // Validasi khusus: nilai buku harus > 0
    const nilaiBuku = document.getElementById('nilai_buku');
    const nilaiBukuValue = parseFloat(nilaiBuku.value) || 0;
    
    if (nilaiBukuValue <= 0) {
        isValid = false;
        nilaiBuku.classList.add('border-red-500');
        errorMessages.push('Nilai buku harus lebih dari 0');
        
        if (!firstInvalid) firstInvalid = nilaiBuku;
    }
    
    // Validasi dokumen pendukung (jika diupload)
    const dokumenInput = document.getElementById('dokumen_pendukung');
    if (dokumenInput.files && dokumenInput.files[0]) {
        const fileSize = dokumenInput.files[0].size;
        const maxSize = 2 * 1024 * 1024; // 2MB
        const fileName = dokumenInput.files[0].name;
        const fileExt = fileName.split('.').pop().toLowerCase();
        const allowedExt = ['pdf', 'jpg', 'jpeg', 'png'];
        
        if (fileSize > maxSize) {
            isValid = false;
            dokumenInput.classList.add('border-red-500');
            errorMessages.push('Ukuran dokumen maksimal 2MB');
            
            if (!firstInvalid) firstInvalid = dokumenInput;
        } else if (!allowedExt.includes(fileExt)) {
            isValid = false;
            dokumenInput.classList.add('border-red-500');
            errorMessages.push('Format dokumen harus PDF, JPG, JPEG, atau PNG');
            
            if (!firstInvalid) firstInvalid = dokumenInput;
        }
    }
    
    // Validasi deskripsi minimal 20 karakter
    const deskripsi = document.getElementById('deskripsi_penghapusan');
    if (deskripsi.value.trim().length < 20) {
        isValid = false;
        deskripsi.classList.add('border-red-500');
        errorMessages.push('Deskripsi penghapusan minimal 20 karakter');
        
        if (!firstInvalid) firstInvalid = deskripsi;
    }
    
    // Validasi jika jenis penghapusan "dijual" maka harga jual harus diisi
    const jenisPenghapusan = document.getElementById('jenis_penghapusan').value;
    const hargaJual = document.getElementById('harga_jual');
    
    if (jenisPenghapusan === 'dijual') {
        if (!hargaJual.value || parseFloat(hargaJual.value) <= 0) {
            isValid = false;
            hargaJual.classList.add('border-red-500');
            errorMessages.push('Harga jual wajib diisi untuk jenis penghapusan "Dijual"');
            
            if (!firstInvalid) firstInvalid = hargaJual;
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
    if (confirm('Apakah Anda yakin ingin mengajukan penghapusan aset ini?\n\nSetelah diajukan, penghapusan akan diproses oleh admin sekolah.')) {
        form.submit();
    }
}
</script>
@endpush