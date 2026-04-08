@extends('layouts.app')

@section('title', 'Edit Lokasi Asset - Jobie')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Lokasi Asset</h1>
        <p class="mt-1 text-gray-600">Perbarui informasi lokasi #{{ $lokasi->LokasiID }}</p>
    </div>
    <div class="mt-4 sm:mt-0 flex space-x-2">
        <a href="{{ route('lokasi.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition duration-150 ease-in-out">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
        <form action="{{ route('lokasi.destroy', $lokasi->LokasiID) }}" method="POST" id="deleteForm">
            @csrf
            @method('DELETE')
            <button type="button" onclick="confirmDelete()"
                    class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md font-semibold text-xs text-red-700 uppercase tracking-widest hover:bg-red-50 transition duration-150 ease-in-out">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Hapus
            </button>
        </form>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Section -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="p-6 md:p-8">
                    <form action="{{ route('lokasi.update', $lokasi->LokasiID) }}" method="POST" id="editForm" class="space-y-6">
                        @csrf
                        @method('PUT')

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

                        <!-- Instansi & Kode Lokasi -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Instansi
                                </label>
                                <input type="text" 
                                    value="{{ auth()->user()->instansi->NamaSekolah ?? '-' }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100"
                                    readonly>
                            </div>

                            <div class="space-y-2">
                                <label for="KodeLokasi" class="block text-sm font-medium text-gray-700">
                                    Kode Lokasi <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="KodeLokasi" id="KodeLokasi"
                                       value="{{ old('KodeLokasi', $lokasi->KodeLokasi) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('KodeLokasi') border-red-500 @enderror"
                                       placeholder="Contoh: LOK-001"
                                       maxlength="20"
                                       required>
                                @error('KodeLokasi')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Nama Lokasi -->
                        <div class="space-y-2">
                            <label for="NamaLokasi" class="block text-sm font-medium text-gray-700">
                                Nama Lokasi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="NamaLokasi" id="NamaLokasi"
                                   value="{{ old('NamaLokasi', $lokasi->NamaLokasi) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('NamaLokasi') border-red-500 @enderror"
                                   placeholder="Contoh: Ruang Kelas 10A"
                                   maxlength="100"
                                   required>
                            @error('NamaLokasi')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
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
                                    <option value="ruangan" {{ old('JenisLokasi', $lokasi->JenisLokasi) == 'ruangan' ? 'selected' : '' }}>Ruangan</option>
                                    <option value="gudang" {{ old('JenisLokasi', $lokasi->JenisLokasi) == 'gudang' ? 'selected' : '' }}>Gudang</option>
                                    <option value="laboratorium" {{ old('JenisLokasi', $lokasi->JenisLokasi) == 'laboratorium' ? 'selected' : '' }}>Laboratorium</option>
                                    <option value="lapangan" {{ old('JenisLokasi', $lokasi->JenisLokasi) == 'lapangan' ? 'selected' : '' }}>Lapangan</option>
                                    <option value="lainnya" {{ old('JenisLokasi', $lokasi->JenisLokasi) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
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
                                    <option value="active" {{ old('Status', $lokasi->Status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="maintenance" {{ old('Status', $lokasi->Status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="tidak_aktif" {{ old('Status', $lokasi->Status) == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                    <option value="renovasi" {{ old('Status', $lokasi->Status) == 'renovasi' ? 'selected' : '' }}>Renovasi</option>
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
                                       value="{{ old('PenanggungJawab', $lokasi->PenanggungJawab) }}"
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
                                       value="{{ old('TeleponPenanggungJawab', $lokasi->TeleponPenanggungJawab) }}"
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
                                      placeholder="Tambahkan keterangan jika diperlukan">{{ old('Keterangan', $lokasi->Keterangan) }}</textarea>
                            @error('Keterangan')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="pt-6 border-t border-gray-200">
                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('lokasi.index') }}" 
                                   class="px-6 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition duration-150 ease-in-out">
                                    Batal
                                </a>
                                <button type="submit" 
                                        class="px-6 py-3 bg-purple-600 border border-transparent rounded-lg font-medium text-white hover:bg-purple-700 transition duration-150 ease-in-out">
                                    <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Perbarui Lokasi
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="space-y-6">
            <!-- Lokasi Info Card -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-purple-50 px-6 py-4 border-b border-purple-100">
                    <h3 class="text-lg font-semibold text-purple-900">Informasi Lokasi</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">ID Lokasi</p>
                        <p class="text-lg font-bold text-gray-900 mt-1">#{{ $lokasi->LokasiID }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Kode Lokasi</p>
                        <p class="text-sm font-mono font-semibold text-purple-700 mt-1">{{ $lokasi->KodeLokasi }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Status</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1
                            @if($lokasi->Status == 'active') bg-green-100 text-green-800
                            @elseif($lokasi->Status == 'maintenance') bg-yellow-100 text-yellow-800
                            @elseif($lokasi->Status == 'renovasi') bg-blue-100 text-blue-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $lokasi->Status)) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Jenis Lokasi</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1
                            @if($lokasi->JenisLokasi == 'ruangan') bg-blue-100 text-blue-800
                            @elseif($lokasi->JenisLokasi == 'gudang') bg-yellow-100 text-yellow-800
                            @elseif($lokasi->JenisLokasi == 'laboratorium') bg-purple-100 text-purple-800
                            @elseif($lokasi->JenisLokasi == 'lapangan') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($lokasi->JenisLokasi) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Instansi</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $lokasi->instansi->NamaSekolah ?? '-' }}</p>
                    </div>
                    @if($lokasi->PenanggungJawab)
                    <div>
                        <p class="text-sm font-medium text-gray-500">Penanggung Jawab</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $lokasi->PenanggungJawab }}</p>
                        @if($lokasi->TeleponPenanggungJawab)
                        <p class="text-xs text-gray-500">{{ $lokasi->TeleponPenanggungJawab }}</p>
                        @endif
                    </div>
                    @endif
                    <div>
                        <p class="text-sm font-medium text-gray-500">Dibuat</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $lokasi->created_at->format('d F Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Terakhir Diupdate</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $lokasi->updated_at->format('d F Y, H:i') }}</p>
                    </div>
                    @if($lokasi->Keterangan)
                    <div>
                        <p class="text-sm font-medium text-gray-500">Keterangan</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $lokasi->Keterangan }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('lokasi.create') }}" 
                       class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Tambah Lokasi Baru</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="{{ route('lokasi.index') }}" 
                       class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Lihat Semua Lokasi</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.confirmDelete = function() {
        if (confirm('Apakah Anda yakin ingin menghapus lokasi ini?\n\nTindakan ini tidak dapat dibatalkan.')) {
            document.getElementById('deleteForm').submit();
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        // Format telepon - hanya angka dan tanda +
        document.getElementById('TeleponPenanggungJawab').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9+]/g, '');
        });

        // Unsaved changes warning
        const form = document.getElementById('editForm');
        const fields = ['InstansiID', 'KodeLokasi', 'NamaLokasi', 'JenisLokasi', 'Status', 'PenanggungJawab', 'TeleponPenanggungJawab', 'Keterangan'];
        const initialValues = {};
        fields.forEach(id => {
            const el = document.getElementById(id);
            if (el) initialValues[id] = el.value;
        });

        const hasChanges = () => fields.some(id => {
            const el = document.getElementById(id);
            return el && el.value !== initialValues[id];
        });

        window.addEventListener('beforeunload', (e) => {
            if (hasChanges()) {
                e.preventDefault();
                e.returnValue = 'Perubahan belum disimpan. Yakin ingin meninggalkan halaman?';
            }
        });

        form.addEventListener('submit', () => {
            window.removeEventListener('beforeunload', () => {});
        });
    });
</script>
@endpush