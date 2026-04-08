@extends('layouts.app')

@section('title', 'Buat Laporan Perbaikan - ' . $kerusakan->kode_laporan)

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Buat Laporan Perbaikan</h1>
        <p class="mt-1 text-gray-600 text-sm">Untuk keluhan: <span class="font-mono font-semibold">{{ $kerusakan->kode_laporan }}</span></p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('teknisi.keluhan.show', $kerusakan->kerusakanID) }}"
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
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

            {{-- Ringkasan Keluhan --}}
            <div class="bg-orange-50 border-l-4 border-orange-400 p-4 mb-6 rounded-r-lg">
                <div class="flex flex-wrap items-center gap-4">
                    <div>
                        <p class="text-xs text-orange-600 font-medium">Aset</p>
                        <p class="text-sm font-semibold text-orange-900">{{ $kerusakan->asset->nama_asset ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-orange-600 font-medium">Lokasi</p>
                        <p class="text-sm font-semibold text-orange-900">{{ $kerusakan->lokasi->nama_lokasi ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-orange-600 font-medium">Jenis Kerusakan</p>
                        <p class="text-sm font-semibold text-orange-900">{{ ucfirst($kerusakan->jenis_kerusakan) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-orange-600 font-medium">Prioritas</p>
                        <p class="text-sm font-semibold text-orange-900">{{ ucfirst($kerusakan->prioritas) }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('teknisi.keluhan.perbaikan.store', $kerusakan->kerusakanID) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Kode Perbaikan --}}
                    <div class="space-y-2">
                        <label for="kode_perbaikan" class="block text-sm font-medium text-gray-700">
                            Kode Perbaikan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kode_perbaikan" id="kode_perbaikan"
                               value="{{ old('kode_perbaikan', $kodePerbaikan) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 font-mono bg-gray-50 @error('kode_perbaikan') border-red-500 @enderror"
                               readonly>
                        @error('kode_perbaikan')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="space-y-2">
                        <label for="status" class="block text-sm font-medium text-gray-700">
                            Status Perbaikan <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('status') border-red-500 @enderror"
                                required>
                            <option value="dalam_perbaikan" {{ old('status') == 'dalam_perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                            <option value="selesai"         {{ old('status') == 'selesai'         ? 'selected' : '' }}>Selesai</option>
                            <option value="tidak_bisa_diperbaiki" {{ old('status') == 'tidak_bisa_diperbaiki' ? 'selected' : '' }}>Tidak Bisa Diperbaiki</option>
                        </select>
                        @error('status')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Mulai Perbaikan --}}
                    <div class="space-y-2">
                        <label for="mulai_perbaikan" class="block text-sm font-medium text-gray-700">
                            Mulai Perbaikan
                        </label>
                        <input type="date" name="mulai_perbaikan" id="mulai_perbaikan"
                               value="{{ old('mulai_perbaikan', date('Y-m-d')) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('mulai_perbaikan') border-red-500 @enderror">
                        @error('mulai_perbaikan')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Selesai Perbaikan --}}
                    <div class="space-y-2" id="selesaiField">
                        <label for="selesai_perbaikan" class="block text-sm font-medium text-gray-700">
                            Selesai Perbaikan
                        </label>
                        <input type="date" name="selesai_perbaikan" id="selesai_perbaikan"
                               value="{{ old('selesai_perbaikan') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('selesai_perbaikan') border-red-500 @enderror">
                        @error('selesai_perbaikan')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tindakan Perbaikan --}}
                    <div class="space-y-2 md:col-span-2">
                        <label for="tindakan_perbaikan" class="block text-sm font-medium text-gray-700">
                            Tindakan Perbaikan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="tindakan_perbaikan" id="tindakan_perbaikan" rows="4"
                                  placeholder="Jelaskan tindakan perbaikan yang dilakukan secara detail..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('tindakan_perbaikan') border-red-500 @enderror"
                                  required>{{ old('tindakan_perbaikan') }}</textarea>
                        @error('tindakan_perbaikan')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alasan Tidak Bisa (conditional) --}}
                    <div class="space-y-2 md:col-span-2 hidden" id="alasanField">
                        <label for="alasan_tidak_bisa" class="block text-sm font-medium text-gray-700">
                            Alasan Tidak Bisa Diperbaiki <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alasan_tidak_bisa" id="alasan_tidak_bisa" rows="3"
                                  placeholder="Jelaskan alasan mengapa aset tidak dapat diperbaiki..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('alasan_tidak_bisa') border-red-500 @enderror">{{ old('alasan_tidak_bisa') }}</textarea>
                        @error('alasan_tidak_bisa')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Komponen Diganti --}}
                    <div class="space-y-2">
                        <label for="komponen_diganti" class="block text-sm font-medium text-gray-700">
                            Komponen Diganti <span class="text-gray-400 text-xs">(Opsional)</span>
                        </label>
                        <input type="text" name="komponen_diganti" id="komponen_diganti"
                               value="{{ old('komponen_diganti') }}"
                               placeholder="Contoh: Baterai, layar, kabel..."
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('komponen_diganti') border-red-500 @enderror">
                        @error('komponen_diganti')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Biaya Aktual --}}
                    <div class="space-y-2">
                        <label for="biaya_aktual" class="block text-sm font-medium text-gray-700">
                            Biaya Aktual <span class="text-gray-400 text-xs">(Opsional)</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 text-sm pointer-events-none">Rp</span>
                            <input type="number" name="biaya_aktual" id="biaya_aktual"
                                   value="{{ old('biaya_aktual') }}"
                                   min="0" step="1000"
                                   placeholder="0"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('biaya_aktual') border-red-500 @enderror">
                        </div>
                        @error('biaya_aktual')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Foto Sesudah --}}
                    <div class="space-y-2">
                        <label for="foto_sesudah" class="block text-sm font-medium text-gray-700">
                            Foto Sesudah Perbaikan <span class="text-gray-400 text-xs">(Opsional)</span>
                        </label>
                        <input type="file" name="foto_sesudah" id="foto_sesudah"
                               accept="image/*"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm @error('foto_sesudah') border-red-500 @enderror">
                        @error('foto_sesudah')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Catatan --}}
                    <div class="space-y-2 md:col-span-2">
                        <label for="catatan_perbaikan" class="block text-sm font-medium text-gray-700">
                            Catatan Tambahan <span class="text-gray-400 text-xs">(Opsional)</span>
                        </label>
                        <textarea name="catatan_perbaikan" id="catatan_perbaikan" rows="3"
                                  placeholder="Catatan tambahan jika diperlukan..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('catatan_perbaikan') border-red-500 @enderror">{{ old('catatan_perbaikan') }}</textarea>
                        @error('catatan_perbaikan')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Form Actions --}}
                <div class="pt-6 border-t border-gray-200 mt-6">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('teknisi.keluhan.show', $kerusakan->kerusakanID) }}"
                           class="px-6 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition duration-150">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-6 py-3 bg-purple-600 border border-transparent rounded-lg font-medium text-white hover:bg-purple-700 transition duration-150">
                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
    const statusSelect  = document.getElementById('status');
    const alasanField   = document.getElementById('alasanField');
    const selesaiField  = document.getElementById('selesaiField');

    function toggleFields() {
        const val = statusSelect.value;
        alasanField.classList.toggle('hidden', val !== 'tidak_bisa_diperbaiki');
    }

    statusSelect.addEventListener('change', toggleFields);
    toggleFields();
});
</script>
@endpush