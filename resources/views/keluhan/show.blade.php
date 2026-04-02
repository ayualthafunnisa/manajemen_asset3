@extends('layouts.app')

@section('title', 'Detail Keluhan - ' . $kerusakan->kode_laporan)

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-white shadow-sm border border-gray-200 px-4 py-3 rounded-lg">
        <li class="breadcrumb-item">
            <a href="" class="text-gray-600 hover:text-purple-600 transition duration-150">Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('keluhan.index') }}" class="text-gray-600 hover:text-purple-600 transition duration-150">Daftar Keluhan</a>
        </li>
        <li class="breadcrumb-item active text-gray-800" aria-current="page">Detail Keluhan</li>
    </ol>
</nav>
@endsection

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Detail Keluhan</h1>
        <p class="mt-1 text-gray-600 font-mono text-sm">{{ $kerusakan->kode_laporan }}</p>
    </div>
    <div class="mt-4 sm:mt-0 flex space-x-2">
        @if(in_array($kerusakan->status_perbaikan, ['dilaporkan']) && !$kerusakan->perbaikan)
        <a href="{{ route('keluhan.create', $kerusakan->kerusakanID) }}"
           class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Buat Laporan Perbaikan
        </a>
        @endif
        <a href="{{ route('keluhan.index') }}"
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
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Flash Messages --}}
    @if (session('success'))
    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg flex items-start gap-3">
        <svg class="h-5 w-5 text-green-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <p class="text-sm text-green-700">{{ session('success') }}</p>
    </div>
    @endif

    @if (session('error'))
    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg flex items-start gap-3">
        <svg class="h-5 w-5 text-red-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <p class="text-sm text-red-700">{{ session('error') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri: Detail Kerusakan --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Card: Info Kerusakan --}}
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-orange-50 px-6 py-4 border-b border-orange-100 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-orange-900">Informasi Kerusakan</h3>
                    @php
                        $prioritasClass = match($kerusakan->prioritas) {
                            'kritis' => 'bg-red-100 text-red-800',
                            'tinggi' => 'bg-orange-100 text-orange-800',
                            'sedang' => 'bg-yellow-100 text-yellow-800',
                            'rendah' => 'bg-green-100 text-green-800',
                            default  => 'bg-gray-100 text-gray-800',
                        };
                    @endphp
                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $prioritasClass }}">
                        Prioritas: {{ ucfirst($kerusakan->prioritas) }}
                    </span>
                </div>
                <div class="p-6">
                    {{-- Tingkat Kerusakan Progress --}}
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-600">Tingkat Kerusakan</span>
                            <span class="text-sm font-bold text-gray-900">{{ $kerusakan->tingkat_kerusakan }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            @php
                                $barColor = match(true) {
                                    $kerusakan->tingkat_kerusakan >= 100 => 'bg-red-600',
                                    $kerusakan->tingkat_kerusakan >= 75  => 'bg-orange-500',
                                    $kerusakan->tingkat_kerusakan >= 50  => 'bg-yellow-500',
                                    default                              => 'bg-green-500',
                                };
                            @endphp
                            <div class="h-2.5 rounded-full {{ $barColor }}" style="width: {{ $kerusakan->tingkat_kerusakan }}%"></div>
                        </div>
                    </div>

                    <table class="min-w-full divide-y divide-gray-100">
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500 w-1/3">Kode Laporan</td>
                                <td class="py-3 text-sm font-mono font-semibold text-gray-900">{{ $kerusakan->kode_laporan }}</td>
                            </tr>
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Jenis Kerusakan</td>
                                <td class="py-3 text-sm">
                                    @php
                                        $jenisClass = match($kerusakan->jenis_kerusakan) {
                                            'ringan' => 'bg-green-100 text-green-800',
                                            'sedang' => 'bg-yellow-100 text-yellow-800',
                                            'berat'  => 'bg-orange-100 text-orange-800',
                                            'total'  => 'bg-red-100 text-red-800',
                                            default  => 'bg-gray-100 text-gray-800',
                                        };
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $jenisClass }}">
                                        {{ ucfirst($kerusakan->jenis_kerusakan) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Tanggal Kerusakan</td>
                                <td class="py-3 text-sm text-gray-900">{{ \Carbon\Carbon::parse($kerusakan->tanggal_kerusakan)->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Tanggal Laporan</td>
                                <td class="py-3 text-sm text-gray-900">{{ \Carbon\Carbon::parse($kerusakan->tanggal_laporan)->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Dilaporkan Oleh</td>
                                <td class="py-3 text-sm text-gray-900">{{ $kerusakan->pelapor->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Estimasi Biaya</td>
                                <td class="py-3 text-sm text-gray-900">
                                    @if($kerusakan->estimasi_biaya)
                                        Rp {{ number_format($kerusakan->estimasi_biaya, 0, ',', '.') }}
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Status</td>
                                <td class="py-3 text-sm">
                                    @php
                                        $statusClass = match($kerusakan->status_perbaikan) {
                                            'dilaporkan' => 'bg-yellow-100 text-yellow-800',
                                            'diproses'   => 'bg-blue-100 text-blue-800',
                                            'selesai'    => 'bg-green-100 text-green-800',
                                            default      => 'bg-gray-100 text-gray-800',
                                        };
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $kerusakan->status_perbaikan)) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500 align-top">Deskripsi</td>
                                <td class="py-3 text-sm text-gray-900 whitespace-pre-line">{{ $kerusakan->deskripsi_kerusakan }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Foto Kerusakan --}}
            @if ($kerusakan->foto_kerusakan)
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Foto Kerusakan</h3>
                </div>
                <div class="p-6">
                    <img src="{{ Storage::url($kerusakan->foto_kerusakan) }}"
                         alt="Foto Kerusakan"
                         class="w-full max-h-80 object-cover rounded-lg border border-gray-200">
                </div>
            </div>
            @endif

            {{-- Laporan Perbaikan (jika ada) --}}
            @if ($kerusakan->perbaikan)
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-blue-50 px-6 py-4 border-b border-blue-100 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-blue-900">Laporan Perbaikan</h3>
                    <span class="font-mono text-sm text-blue-700">{{ $kerusakan->perbaikan->kode_perbaikan }}</span>
                </div>
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-100">
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500 w-1/3">Teknisi</td>
                                <td class="py-3 text-sm text-gray-900">{{ $kerusakan->perbaikan->teknisi->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Status Perbaikan</td>
                                <td class="py-3 text-sm">
                                    @php
                                        $pStatus = $kerusakan->perbaikan->status;
                                        $pStatusClass = match($pStatus) {
                                            'dalam_perbaikan'      => 'bg-blue-100 text-blue-800',
                                            'selesai'              => 'bg-green-100 text-green-800',
                                            'tidak_bisa_diperbaiki'=> 'bg-red-100 text-red-800',
                                            default                => 'bg-gray-100 text-gray-800',
                                        };
                                        $pStatusLabel = match($pStatus) {
                                            'dalam_perbaikan'       => 'Dalam Perbaikan',
                                            'selesai'               => 'Selesai',
                                            'tidak_bisa_diperbaiki' => 'Tidak Bisa Diperbaiki',
                                            default                 => ucfirst($pStatus),
                                        };
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $pStatusClass }}">
                                        {{ $pStatusLabel }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Mulai Perbaikan</td>
                                <td class="py-3 text-sm text-gray-900">
                                    {{ $kerusakan->perbaikan->mulai_perbaikan ? \Carbon\Carbon::parse($kerusakan->perbaikan->mulai_perbaikan)->format('d F Y') : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Selesai Perbaikan</td>
                                <td class="py-3 text-sm text-gray-900">
                                    {{ $kerusakan->perbaikan->selesai_perbaikan ? \Carbon\Carbon::parse($kerusakan->perbaikan->selesai_perbaikan)->format('d F Y') : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500 align-top">Tindakan</td>
                                <td class="py-3 text-sm text-gray-900 whitespace-pre-line">{{ $kerusakan->perbaikan->tindakan_perbaikan }}</td>
                            </tr>
                            @if($kerusakan->perbaikan->komponen_diganti)
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Komponen Diganti</td>
                                <td class="py-3 text-sm text-gray-900">{{ $kerusakan->perbaikan->komponen_diganti }}</td>
                            </tr>
                            @endif
                            @if($kerusakan->perbaikan->biaya_aktual)
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Biaya Aktual</td>
                                <td class="py-3 text-sm font-semibold text-green-700">
                                    Rp {{ number_format($kerusakan->perbaikan->biaya_aktual, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    {{-- Update Status Form (hanya jika status dalam_perbaikan dan milik teknisi ini) --}}
                    @if($kerusakan->perbaikan->status === 'dalam_perbaikan' && $kerusakan->perbaikan->teknisi_id === Auth::id())
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Perbarui Status Perbaikan</h4>
                        <form action="{{ route('keluhan.updateStatus', $kerusakan->perbaikan->perbaikanID) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PATCH')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Status Baru</label>
                                    <select name="status" id="statusUpdate"
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="dalam_perbaikan">Masih Dalam Perbaikan</option>
                                        <option value="selesai">Selesai</option>
                                        <option value="tidak_bisa_diperbaiki">Tidak Bisa Diperbaiki</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Selesai</label>
                                    <input type="date" name="selesai_perbaikan"
                                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            <div id="alasanField" class="hidden">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Alasan Tidak Bisa Diperbaiki <span class="text-red-500">*</span></label>
                                <textarea name="alasan_tidak_bisa" rows="2"
                                          class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"></textarea>
                            </div>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-150">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Perubahan Status
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            @endif

        </div>

        {{-- Kolom Kanan: Sidebar --}}
        <div class="space-y-6">

            {{-- Info Aset --}}
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-blue-50 px-6 py-4 border-b border-blue-100">
                    <h3 class="text-lg font-semibold text-blue-900">Informasi Aset</h3>
                </div>
                <div class="p-6">
                    @if($kerusakan->asset)
                    <table class="min-w-full">
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="py-2 text-sm font-medium text-gray-500">Kode Aset</td>
                                <td class="py-2 text-sm text-gray-900 font-mono">{{ $kerusakan->asset->kode_asset }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-sm font-medium text-gray-500">Nama Aset</td>
                                <td class="py-2 text-sm text-gray-900">{{ $kerusakan->asset->nama_asset }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-sm font-medium text-gray-500">Kategori</td>
                                <td class="py-2 text-sm text-gray-900">{{ $kerusakan->asset->kategori->nama_kategori ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                    @else
                    <p class="text-sm text-gray-500">Data aset tidak tersedia</p>
                    @endif
                </div>
            </div>

            {{-- Info Lokasi --}}
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-green-50 px-6 py-4 border-b border-green-100">
                    <h3 class="text-lg font-semibold text-green-900">Lokasi Aset</h3>
                </div>
                <div class="p-6">
                    @if($kerusakan->lokasi)
                    <table class="min-w-full">
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="py-2 text-sm font-medium text-gray-500">Nama Lokasi</td>
                                <td class="py-2 text-sm text-gray-900">{{ $kerusakan->lokasi->nama_lokasi }}</td>
                            </tr>
                            @if(isset($kerusakan->lokasi->gedung))
                            <tr>
                                <td class="py-2 text-sm font-medium text-gray-500">Gedung</td>
                                <td class="py-2 text-sm text-gray-900">{{ $kerusakan->lokasi->gedung }}</td>
                            </tr>
                            @endif
                            @if(isset($kerusakan->lokasi->lantai))
                            <tr>
                                <td class="py-2 text-sm font-medium text-gray-500">Lantai</td>
                                <td class="py-2 text-sm text-gray-900">{{ $kerusakan->lokasi->lantai }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    @else
                    <p class="text-sm text-gray-500">Data lokasi tidak tersedia</p>
                    @endif
                </div>
            </div>

            {{-- CTA Buat Perbaikan --}}
            @if(in_array($kerusakan->status_perbaikan, ['dilaporkan']) && !$kerusakan->perbaikan)
            <div class="bg-green-50 rounded-xl p-5 border border-green-200">
                <div class="flex items-start gap-3">
                    <svg class="h-6 w-6 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-green-800">Siap Ditangani</p>
                        <p class="text-xs text-green-700 mt-1">Keluhan ini belum memiliki laporan perbaikan. Buat laporan untuk mulai menangani.</p>
                    </div>
                </div>
                <a href="{{ route('keluhan.create', $kerusakan->kerusakanID) }}"
                   class="mt-4 w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Laporan Perbaikan
                </a>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const statusSelect  = document.getElementById('statusUpdate');
    const alasanField   = document.getElementById('alasanField');
    if (!statusSelect) return;

    statusSelect.addEventListener('change', function () {
        alasanField.classList.toggle('hidden', this.value !== 'tidak_bisa_diperbaiki');
    });
});
</script>
@endpush