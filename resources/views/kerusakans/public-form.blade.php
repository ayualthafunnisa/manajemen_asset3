<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapor Kerusakan - {{ $asset->nama_asset }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 min-h-screen py-8 px-4">

<div class="max-w-lg mx-auto">

    {{-- Header --}}
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-red-100 rounded-full mb-3">
            <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h1 class="text-xl font-bold text-gray-900">Laporan Kerusakan Asset</h1>
        <p class="text-sm text-gray-500 mt-1">Isi form berikut untuk melaporkan kerusakan</p>
    </div>

    <form action="{{ route('kerusakan.public.store', $asset->kode_asset) }}" 
          method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- Kode Laporan Hidden --}}
        <input type="hidden" name="kode_laporan" value="{{ $kodeLaporan }}">

        {{-- Info Asset — READ ONLY --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-3">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Informasi Asset</h2>
            
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                <div class="flex-1">
                    <p class="text-xs text-gray-500">Kode Asset</p>
                    <p class="font-mono font-semibold text-gray-900">{{ $asset->kode_asset }}</p>
                </div>
                <div class="flex-1">
                    <p class="text-xs text-gray-500">Nama Asset</p>
                    <p class="font-semibold text-gray-900">{{ $asset->nama_asset }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                <div class="flex-1">
                    <p class="text-xs text-gray-500">Lokasi</p>
                    <p class="font-semibold text-gray-900">{{ $asset->lokasi->NamaLokasi ?? '-' }}</p>
                </div>
                <div class="flex-1">
                    <p class="text-xs text-gray-500">Kategori</p>
                    <p class="font-semibold text-gray-900">{{ $asset->kategori->nama_kategori ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- Identitas Pelapor --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Identitas Pelapor</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Pelapor <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_pelapor" 
                       value="{{ old('nama_pelapor') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('nama_pelapor') border-red-500 @enderror"
                       placeholder="Nama lengkap Anda" maxlength="100" required>
                @error('nama_pelapor')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    No. Telepon <span class="text-red-500">*</span>
                </label>
                <input type="text" name="telepon_pelapor" id="telepon_pelapor"
                       value="{{ old('telepon_pelapor') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('telepon_pelapor') border-red-500 @enderror"
                       placeholder="Contoh: 081234567890" maxlength="20" required>
                @error('telepon_pelapor')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Detail Kerusakan --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Detail Kerusakan</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tanggal Kerusakan <span class="text-red-500">*</span>
                </label>
                <input type="date" name="tanggal_kerusakan"
                       value="{{ old('tanggal_kerusakan', date('Y-m-d')) }}"
                       max="{{ date('Y-m-d') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 @error('tanggal_kerusakan') border-red-500 @enderror"
                       required>
                @error('tanggal_kerusakan')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Jenis Kerusakan <span class="text-red-500">*</span>
                </label>
                <select name="jenis_kerusakan"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 @error('jenis_kerusakan') border-red-500 @enderror"
                        required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="ringan" {{ old('jenis_kerusakan') == 'ringan' ? 'selected' : '' }}>Ringan</option>
                    <option value="sedang" {{ old('jenis_kerusakan') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                    <option value="berat"  {{ old('jenis_kerusakan') == 'berat'  ? 'selected' : '' }}>Berat</option>
                    <option value="total"  {{ old('jenis_kerusakan') == 'total'  ? 'selected' : '' }}>Total</option>
                </select>
                @error('jenis_kerusakan')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Deskripsi Kerusakan <span class="text-red-500">*</span>
                </label>
                <textarea name="deskripsi_kerusakan" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 resize-y @error('deskripsi_kerusakan') border-red-500 @enderror"
                          placeholder="Jelaskan kondisi kerusakan secara detail (min. 10 karakter)"
                          required>{{ old('deskripsi_kerusakan') }}</textarea>
                @error('deskripsi_kerusakan')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Foto Kerusakan <span class="text-red-500">*</span>
                </label>
                <input type="file" name="foto_kerusakan" id="foto_kerusakan"
                       accept="image/jpg,image/jpeg,image/png,image/webp"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg @error('foto_kerusakan') border-red-500 @enderror"
                       required>
                <p class="text-xs text-gray-500 mt-1">PNG, JPG, WEBP. Maks 2 MB.</p>
                <img id="foto-preview" src="#" alt="preview" 
                     class="hidden mt-2 max-h-48 rounded-lg object-cover border border-gray-200">
                @error('foto_kerusakan')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full py-4 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition duration-200">
            Kirim Laporan Kerusakan
        </button>

    </form>
</div>

<script>
    // Preview foto
    document.getElementById('foto_kerusakan').addEventListener('change', function() {
        const preview = document.getElementById('foto-preview');
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = e => { preview.src = e.target.result; preview.classList.remove('hidden'); };
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Telepon hanya angka
    document.getElementById('telepon_pelapor').addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9+]/g, '');
    });
</script>

</body>
</html>