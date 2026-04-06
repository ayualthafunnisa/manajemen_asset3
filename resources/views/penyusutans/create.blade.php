@extends('layouts.app')

@section('title', 'Hitung Penyusutan Aset - Jobie')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-white shadow-sm border border-gray-200 px-4 py-3 rounded-lg">
        <li class="breadcrumb-item">
            <a href="" class="text-gray-600 hover:text-purple-600 transition duration-150">Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('penyusutan.index') }}" class="text-gray-600 hover:text-purple-600 transition duration-150">Penyusutan Aset</a>
        </li>
        <li class="breadcrumb-item active text-gray-800" aria-current="page">Hitung Baru</li>
    </ol>
</nav>
@endsection

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Hitung Penyusutan Aset</h1>
        <p class="mt-1 text-gray-600">Lakukan perhitungan penyusutan aset baru</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('penyusutan.index') }}" 
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
            <form action="{{ route('penyusutan.store') }}" method="POST" id="penyusutanForm">
                @csrf

                {{-- InstansiID di-inject otomatis dari controller, tidak perlu dropdown --}}

                <!-- Info -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                <span class="font-bold">Informasi:</span> Nilai awal diambil dari nilai perolehan aset atau nilai akhir penyusutan terakhir secara otomatis.
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
                                    data-nilai="{{ $asset->nilai_perolehan }}"
                                    data-residu="{{ $asset->nilai_residu ?? 0 }}"
                                    data-umur="{{ $asset->umur_ekonomis }}"
                                    data-nama="{{ $asset->nama_asset }}"
                                    data-kode="{{ $asset->kode_asset }}"
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
                                <div class="text-gray-600">Nama Aset:</div>
                                <div class="font-medium" id="assetName">-</div>
                                <div class="text-gray-600">Nilai Perolehan:</div>
                                <div class="font-medium" id="assetNilai">-</div>
                                <div class="text-gray-600">Nilai Residu:</div>
                                <div class="font-medium" id="assetResidu">-</div>
                                <div class="text-gray-600">Umur Ekonomis:</div>
                                <div class="font-medium" id="assetUmur">-</div>
                            </div>
                        </div>
                    </div>

                    <!-- Tahun -->
                    <div class="space-y-2">
                        <label for="tahun" class="block text-sm font-medium text-gray-700">
                            Tahun <span class="text-red-500">*</span>
                        </label>
                        <select name="tahun" id="tahun"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('tahun') border-red-500 @enderror"
                                required>
                            <option value="">-- Pilih Tahun --</option>
                            @for($i = date('Y'); $i >= date('Y')-5; $i--)
                            <option value="{{ $i }}" {{ old('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        @error('tahun')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bulan -->
                    <div class="space-y-2">
                        <label for="bulan" class="block text-sm font-medium text-gray-700">
                            Bulan <span class="text-gray-400 text-xs">(Opsional)</span>
                        </label>
                        <select name="bulan" id="bulan"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('bulan') border-red-500 @enderror">
                            <option value="">-- Pilih Bulan --</option>
                            @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ old('bulan') == $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                            </option>
                            @endfor
                        </select>
                        @error('bulan')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Metode -->
                    <div class="space-y-2">
                        <label for="metode" class="block text-sm font-medium text-gray-700">
                            Metode Penyusutan <span class="text-red-500">*</span>
                        </label>
                        <select name="metode" id="metode"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('metode') border-red-500 @enderror"
                                required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="garis_lurus"   {{ old('metode') == 'garis_lurus'   ? 'selected' : '' }}>Garis Lurus</option>
                            <option value="saldo_menurun" {{ old('metode') == 'saldo_menurun' ? 'selected' : '' }}>Saldo Menurun</option>
                        </select>
                        @error('metode')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Persentase (hanya untuk saldo menurun) -->
                    <div class="space-y-2" id="persentaseField" style="{{ old('metode') == 'saldo_menurun' ? '' : 'display:none' }}">
                        <label for="persentase_penyusutan" class="block text-sm font-medium text-gray-700">
                            Persentase Penyusutan (%) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="persentase_penyusutan" id="persentase_penyusutan"
                               value="{{ old('persentase_penyusutan', 25) }}"
                               step="0.01" min="0" max="100"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('persentase_penyusutan') border-red-500 @enderror">
                        @error('persentase_penyusutan')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Catatan -->
                    <div class="space-y-2 md:col-span-2">
                        <label for="catatan" class="block text-sm font-medium text-gray-700">
                            Catatan <span class="text-gray-400 text-xs">(Opsional)</span>
                        </label>
                        <textarea name="catatan" id="catatan" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('catatan') border-red-500 @enderror">{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Preview Perhitungan -->
                <div class="mt-8 p-4 bg-purple-50 rounded-lg border border-purple-200">
                    <h3 class="font-medium text-purple-900 mb-3">Preview Hasil Perhitungan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white p-3 rounded-lg">
                            <p class="text-xs text-gray-500">Nilai Awal</p>
                            <p class="text-lg font-bold text-gray-900" id="previewNilaiAwal">Rp 0</p>
                        </div>
                        <div class="bg-white p-3 rounded-lg">
                            <p class="text-xs text-gray-500">Nilai Penyusutan</p>
                            <p class="text-lg font-bold text-orange-600" id="previewNilaiPenyusutan">Rp 0</p>
                        </div>
                        <div class="bg-white p-3 rounded-lg">
                            <p class="text-xs text-gray-500">Nilai Akhir</p>
                            <p class="text-lg font-bold text-green-600" id="previewNilaiAkhir">Rp 0</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="pt-6 border-t border-gray-200 mt-6">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('penyusutan.index') }}" 
                           class="px-6 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition duration-150 ease-in-out">
                            Batal
                        </a>
                        <button type="button" 
                                onclick="confirmSubmit()"
                                class="px-6 py-3 bg-purple-600 border border-transparent rounded-lg font-medium text-white hover:bg-purple-700 transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Hitung & Simpan Penyusutan
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
    const metodeSelect    = document.getElementById('metode');
    const persentaseField = document.getElementById('persentaseField');
    const persentaseInput = document.getElementById('persentase_penyusutan');
    const assetSelect     = document.getElementById('assetID');

    // Toggle persentase field
    metodeSelect.addEventListener('change', function () {
        if (this.value === 'saldo_menurun') {
            persentaseField.style.display = 'block';
            persentaseInput.required = true;
        } else {
            persentaseField.style.display = 'none';
            persentaseInput.required = false;
        }
        updatePreview();
    });

    // Tampilkan info aset
    assetSelect.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        if (opt.value) {
            document.getElementById('assetName').textContent   = opt.dataset.nama;
            document.getElementById('assetNilai').textContent  = 'Rp ' + fmt(parseFloat(opt.dataset.nilai));
            document.getElementById('assetResidu').textContent = 'Rp ' + fmt(parseFloat(opt.dataset.residu || 0));
            document.getElementById('assetUmur').textContent   = opt.dataset.umur + ' tahun';
            document.getElementById('assetInfo').classList.remove('hidden');
        } else {
            document.getElementById('assetInfo').classList.add('hidden');
        }
        updatePreview();
    });

    persentaseInput.addEventListener('input', updatePreview);

    function updatePreview() {
        const opt = assetSelect.options[assetSelect.selectedIndex];
        if (!opt || !opt.value) return;

        const nilai   = parseFloat(opt.dataset.nilai)  || 0;
        const residu  = parseFloat(opt.dataset.residu) || 0;
        const umur    = parseInt(opt.dataset.umur)     || 1;
        const metode  = metodeSelect.value;
        const persen  = parseFloat(persentaseInput.value) || 0;

        let penyusutan = 0;
        if (metode === 'garis_lurus') {
            penyusutan = (nilai - residu) / umur;
        } else if (metode === 'saldo_menurun') {
            penyusutan = nilai * (persen / 100);
        }

        // Jangan melebihi nilai - residu
        penyusutan = Math.min(penyusutan, nilai - residu);
        const akhir = nilai - penyusutan;

        document.getElementById('previewNilaiAwal').textContent        = 'Rp ' + fmt(nilai);
        document.getElementById('previewNilaiPenyusutan').textContent  = 'Rp ' + fmt(penyusutan);
        document.getElementById('previewNilaiAkhir').textContent       = 'Rp ' + fmt(akhir);
    }

    function fmt(angka) {
        return Math.round(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Real-time validation untuk required fields
    const requiredInputs = document.querySelectorAll('[required]');
    requiredInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.value.trim()) {
                this.classList.remove('border-red-500');
            }
        });
    });

    // Trigger jika ada old value
    if (assetSelect.value) assetSelect.dispatchEvent(new Event('change'));
    if (metodeSelect.value) metodeSelect.dispatchEvent(new Event('change'));
});

// Fungsi konfirmasi sebelum menyimpan
function confirmSubmit() {
    const form = document.getElementById('penyusutanForm');
    
    // Reset error styles
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    let firstInvalid = null;
    let errorMessages = [];
    
    // Validasi required fields
    requiredFields.forEach(field => {
        let fieldValue = field.value;
        
        if (!fieldValue.trim()) {
            isValid = false;
            field.classList.add('border-red-500');
            
            const label = document.querySelector(`label[for="${field.id}"]`);
            let fieldName = label ? label.innerText.replace('*', '').trim() : field.name;
            
            // Handle khusus untuk select aset
            if (field.id === 'assetID') {
                fieldName = 'Aset';
            } else if (field.id === 'persentase_penyusutan') {
                fieldName = 'Persentase Penyusutan';
            }
            
            errorMessages.push(`${fieldName} wajib diisi`);
            
            if (!firstInvalid) firstInvalid = field;
        } else {
            field.classList.remove('border-red-500');
        }
    });
    
    // Validasi khusus: aset harus dipilih
    const assetSelect = document.getElementById('assetID');
    if (!assetSelect.value) {
        isValid = false;
        assetSelect.classList.add('border-red-500');
        errorMessages.push('Aset wajib dipilih');
        
        if (!firstInvalid) firstInvalid = assetSelect;
    }
    
    // Validasi khusus: jika metode saldo menurun, persentase harus antara 1-100
    const metode = document.getElementById('metode').value;
    if (metode === 'saldo_menurun') {
        const persentase = parseFloat(document.getElementById('persentase_penyusutan').value);
        if (isNaN(persentase) || persentase <= 0 || persentase > 100) {
            isValid = false;
            document.getElementById('persentase_penyusutan').classList.add('border-red-500');
            errorMessages.push('Persentase penyusutan harus antara 1-100%');
            
            if (!firstInvalid) firstInvalid = document.getElementById('persentase_penyusutan');
        }
    }
    
    // Validasi preview tidak boleh nilai penyusutan = 0 jika nilai > residu
    const opt = assetSelect.options[assetSelect.selectedIndex];
    if (opt && opt.value) {
        const nilai = parseFloat(opt.dataset.nilai) || 0;
        const residu = parseFloat(opt.dataset.residu) || 0;
        
        if (nilai > residu) {
            let previewPenyusutan = 0;
            if (metode === 'garis_lurus') {
                const umur = parseInt(opt.dataset.umur) || 1;
                previewPenyusutan = (nilai - residu) / umur;
            } else if (metode === 'saldo_menurun') {
                const persen = parseFloat(document.getElementById('persentase_penyusutan').value) || 0;
                previewPenyusutan = nilai * (persen / 100);
            }
            
            if (previewPenyusutan <= 0 && nilai > residu) {
                isValid = false;
                errorMessages.push('Perhitungan penyusutan menghasilkan nilai 0. Periksa kembali parameter yang dimasukkan.');
            }
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
    
    // Tampilkan konfirmasi dengan preview hasil
    const nilaiAwal = document.getElementById('previewNilaiAwal').textContent;
    const nilaiPenyusutan = document.getElementById('previewNilaiPenyusutan').textContent;
    const nilaiAkhir = document.getElementById('previewNilaiAkhir').textContent;
    
    const confirmMessage = `Apakah Anda yakin ingin menghitung dan menyimpan penyusutan ini?\n\n` +
                          `📊 Preview Perhitungan:\n` +
                          `• Nilai Awal: ${nilaiAwal}\n` +
                          `• Nilai Penyusutan: ${nilaiPenyusutan}\n` +
                          `• Nilai Akhir: ${nilaiAkhir}\n\n` +
                          `Data penyusutan akan disimpan dan tidak dapat diubah.`;
    
    if (confirm(confirmMessage)) {
        form.submit();
    }
}
</script>
@endpush