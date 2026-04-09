@extends('layouts.app')

@section('title', 'Hitung Penyusutan Aset - Jobie')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-neutral-900">Hitung Penyusutan Aset</h1>
        <p class="mt-1 text-sm text-neutral-500">Lakukan perhitungan penyusutan aset baru</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('penyusutan.index') }}" 
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900">Form Penyusutan Aset</h2>
                    <p class="text-sm text-neutral-500">Hitung penyusutan aset menggunakan metode garis lurus atau saldo menurun</p>
                </div>
            </div>
        </div>

        {{-- Form Body --}}
        <div class="px-8 py-8">
            <form action="{{ route('penyusutan.store') }}" method="POST" id="penyusutanForm" class="space-y-8">
                @csrf

                {{-- Info Penting --}}
                <div class="bg-info-50 border-l-4 border-info-500 rounded-r-xl p-5">
                    <div class="flex items-start gap-3">
                        <svg class="h-5 w-5 text-info-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-info-800">Informasi</p>
                            <p class="text-sm text-info-700 mt-1">Nilai awal diambil dari nilai perolehan aset atau nilai akhir penyusutan terakhir secara otomatis.</p>
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
                                    class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('assetID') border-danger-500 @enderror"
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
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror

                            {{-- Info Aset Terpilih --}}
                            <div id="assetInfo" class="hidden mt-3 p-4 bg-neutral-50 rounded-lg border border-neutral-200">
                                <h4 class="font-medium text-neutral-900 mb-2">Informasi Aset:</h4>
                                <div class="grid grid-cols-2 gap-2 text-sm">
                                    <div class="text-neutral-600">Kode Aset:</div>
                                    <div class="font-medium font-mono" id="assetKode">-</div>
                                    <div class="text-neutral-600">Nama Aset:</div>
                                    <div class="font-medium" id="assetName">-</div>
                                    <div class="text-neutral-600">Nilai Perolehan:</div>
                                    <div class="font-medium" id="assetNilai">-</div>
                                    <div class="text-neutral-600">Nilai Residu:</div>
                                    <div class="font-medium" id="assetResidu">-</div>
                                    <div class="text-neutral-600">Umur Ekonomis:</div>
                                    <div class="font-medium" id="assetUmur">-</div>
                                </div>
                            </div>
                        </div>

                        {{-- Tahun --}}
                        <div class="space-y-2">
                            <label for="tahun" class="block text-sm font-semibold text-neutral-700">
                                Tahun <span class="text-danger-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <select name="tahun" id="tahun"
                                        class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('tahun') border-danger-500 @enderror"
                                        required>
                                    <option value="">-- Pilih Tahun --</option>
                                    @for($i = date('Y'); $i >= date('Y')-5; $i--)
                                    <option value="{{ $i }}" {{ old('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            @error('tahun')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Metode --}}
                        <div class="space-y-2">
                            <label for="metode" class="block text-sm font-semibold text-neutral-700">
                                Metode Penyusutan <span class="text-danger-500">*</span>
                            </label>
                            <select name="metode" id="metode"
                                    class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('metode') border-danger-500 @enderror"
                                    required>
                                <option value="">-- Pilih Metode --</option>
                                <option value="garis_lurus" {{ old('metode') == 'garis_lurus' ? 'selected' : '' }}>Garis Lurus (Straight Line)</option>
                                <option value="saldo_menurun" {{ old('metode') == 'saldo_menurun' ? 'selected' : '' }}>Saldo Menurun (Declining Balance)</option>
                            </select>
                            @error('metode')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="space-y-6">
                        {{-- Bulan --}}
                        <div class="space-y-2">
                            <label for="bulan" class="block text-sm font-semibold text-neutral-700">
                                Bulan <span class="text-neutral-400 text-xs">(Opsional)</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <select name="bulan" id="bulan"
                                        class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('bulan') border-danger-500 @enderror">
                                    <option value="">-- Pilih Bulan --</option>
                                    @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ old('bulan') == $i ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                                    </option>
                                    @endfor
                                </select>
                            </div>
                            @error('bulan')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Persentase (hanya untuk saldo menurun) --}}
                        <div class="space-y-2" id="persentaseField" style="{{ old('metode') == 'saldo_menurun' ? '' : 'display:none' }}">
                            <label for="persentase_penyusutan" class="block text-sm font-semibold text-neutral-700">
                                Persentase Penyusutan (%) <span class="text-danger-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <input type="number" name="persentase_penyusutan" id="persentase_penyusutan"
                                       value="{{ old('persentase_penyusutan', 25) }}"
                                       step="0.01" min="0" max="100"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('persentase_penyusutan') border-danger-500 @enderror">
                            </div>
                            <p class="text-xs text-neutral-500">Persentase penyusutan per tahun (contoh: 25% untuk 4 tahun)</p>
                            @error('persentase_penyusutan')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Catatan --}}
                        <div class="space-y-2">
                            <label for="catatan" class="block text-sm font-semibold text-neutral-700">
                                Catatan <span class="text-neutral-400 text-xs">(Opsional)</span>
                            </label>
                            <textarea name="catatan" id="catatan" rows="3"
                                      class="w-full px-4 py-3 border-2 border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 resize-y @error('catatan') border-danger-500 @enderror"
                                      placeholder="Tambahkan catatan jika diperlukan">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Preview Perhitungan --}}
                <div class="mt-4 p-5 bg-gradient-to-r from-primary-50 to-secondary-50 rounded-xl border border-primary-200">
                    <h3 class="font-semibold text-primary-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Preview Hasil Perhitungan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white p-4 rounded-xl shadow-sm">
                            <p class="text-xs text-neutral-500">Nilai Awal</p>
                            <p class="text-xl font-bold text-neutral-900" id="previewNilaiAwal">Rp 0</p>
                        </div>
                        <div class="bg-white p-4 rounded-xl shadow-sm">
                            <p class="text-xs text-neutral-500">Nilai Penyusutan</p>
                            <p class="text-xl font-bold text-primary-600" id="previewNilaiPenyusutan">Rp 0</p>
                        </div>
                        <div class="bg-white p-4 rounded-xl shadow-sm">
                            <p class="text-xs text-neutral-500">Nilai Akhir</p>
                            <p class="text-xl font-bold text-success-600" id="previewNilaiAkhir">Rp 0</p>
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
                        <a href="{{ route('penyusutan.index') }}" 
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
    const metodeSelect = document.getElementById('metode');
    const persentaseField = document.getElementById('persentaseField');
    const persentaseInput = document.getElementById('persentase_penyusutan');
    const assetSelect = document.getElementById('assetID');

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
            document.getElementById('assetKode').textContent = opt.dataset.kode;
            document.getElementById('assetName').textContent = opt.dataset.nama;
            document.getElementById('assetNilai').textContent = 'Rp ' + fmt(parseFloat(opt.dataset.nilai));
            document.getElementById('assetResidu').textContent = 'Rp ' + fmt(parseFloat(opt.dataset.residu || 0));
            document.getElementById('assetUmur').textContent = opt.dataset.umur + ' tahun';
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

        const nilai = parseFloat(opt.dataset.nilai) || 0;
        const residu = parseFloat(opt.dataset.residu) || 0;
        const umur = parseInt(opt.dataset.umur) || 1;
        const metode = metodeSelect.value;
        const persen = parseFloat(persentaseInput.value) || 0;

        let penyusutan = 0;
        if (metode === 'garis_lurus') {
            penyusutan = (nilai - residu) / umur;
        } else if (metode === 'saldo_menurun') {
            penyusutan = nilai * (persen / 100);
        }

        // Jangan melebihi nilai - residu
        penyusutan = Math.min(penyusutan, nilai - residu);
        const akhir = nilai - penyusutan;

        document.getElementById('previewNilaiAwal').textContent = 'Rp ' + fmt(nilai);
        document.getElementById('previewNilaiPenyusutan').textContent = 'Rp ' + fmt(penyusutan);
        document.getElementById('previewNilaiAkhir').textContent = 'Rp ' + fmt(akhir);
    }

    function fmt(angka) {
        return Math.round(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Real-time validation
    const requiredInputs = document.querySelectorAll('[required]');
    requiredInputs.forEach(input => {
        if (input.type !== 'hidden') {
            input.addEventListener('change', function() {
                if (this.value.trim()) {
                    this.classList.remove('border-danger-500');
                    this.classList.add('border-neutral-200');
                }
            });
        }
    });

    // Trigger jika ada old value
    if (assetSelect.value) assetSelect.dispatchEvent(new Event('change'));
    if (metodeSelect.value) metodeSelect.dispatchEvent(new Event('change'));
});

function confirmSubmit() {
    const form = document.getElementById('penyusutanForm');
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
            let fieldName = label ? label.innerText.replace('*', '').trim() : field.name;
            if (field.id === 'assetID') fieldName = 'Aset';
            if (field.id === 'persentase_penyusutan') fieldName = 'Persentase Penyusutan';
            errorMessages.push(`${fieldName} wajib diisi`);
            if (!firstInvalid) firstInvalid = field;
        } else {
            field.classList.remove('border-danger-500');
            field.classList.add('border-neutral-200');
        }
    });

    // Validasi khusus: jika metode saldo menurun, persentase harus antara 1-100
    const metode = document.getElementById('metode').value;
    if (metode === 'saldo_menurun') {
        const persentase = parseFloat(document.getElementById('persentase_penyusutan').value);
        if (isNaN(persentase) || persentase <= 0 || persentase > 100) {
            isValid = false;
            document.getElementById('persentase_penyusutan').classList.add('border-danger-500');
            errorMessages.push('Persentase penyusutan harus antara 1-100%');
            if (!firstInvalid) firstInvalid = document.getElementById('persentase_penyusutan');
        }
    }

    // Validasi preview tidak boleh nilai penyusutan = 0 jika nilai > residu
    const assetSelect = document.getElementById('assetID');
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
        errorMessages.forEach(msg => errorMessage += `• ${msg}\n`);
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