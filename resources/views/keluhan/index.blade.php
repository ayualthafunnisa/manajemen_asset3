@extends('layouts.app')

@section('title', 'Daftar Keluhan Aset - Teknisi')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-white shadow-sm border border-gray-200 px-4 py-3 rounded-lg">
        <li class="breadcrumb-item">
            <a href="" class="text-gray-600 hover:text-purple-600 transition duration-150">
                Dashboard
            </a>
        </li>
        <li class="breadcrumb-item active text-gray-800" aria-current="page">
            Daftar Keluhan
        </li>
    </ol>
</nav>
@endsection

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-neutral-900">Daftar Keluhan Aset</h1>
        <p class="mt-1 text-neutral-600">Keluhan dan kerusakan yang perlu ditangani</p>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">

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

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-neutral-200">
            <p class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Total Aktif</p>
            <p class="text-3xl font-bold text-neutral-900 mt-1">{{ $summary['total'] }}</p>
        </div>
        <div class="bg-yellow-50 rounded-xl p-5 shadow-sm border border-yellow-200">
            <p class="text-xs font-medium text-yellow-600 uppercase tracking-wider">Dilaporkan</p>
            <p class="text-3xl font-bold text-yellow-700 mt-1">{{ $summary['dilaporkan'] }}</p>
        </div>
        <div class="bg-blue-50 rounded-xl p-5 shadow-sm border border-blue-200">
            <p class="text-xs font-medium text-blue-600 uppercase tracking-wider">Diproses</p>
            <p class="text-3xl font-bold text-blue-700 mt-1">{{ $summary['diproses'] }}</p>
        </div>
        <div class="bg-red-50 rounded-xl p-5 shadow-sm border border-red-200">
            <p class="text-xs font-medium text-red-600 uppercase tracking-wider">Prioritas Kritis</p>
            <p class="text-3xl font-bold text-red-700 mt-1">{{ $summary['kritis'] }}</p>
        </div>
    </div>

    {{-- Filter & Table --}}
    <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-neutral-200">

        {{-- Filter Bar --}}
        <div class="p-6 border-b border-neutral-200">
            <form method="GET" action="{{ route('keluhan.index') }}" class="flex flex-col md:flex-row md:items-center gap-3">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari kode laporan atau nama aset..."
                           class="pl-10 pr-4 py-2 w-full border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                </div>
                <select name="prioritas"
                        class="border border-neutral-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                    <option value="">Semua Prioritas</option>
                    <option value="kritis"  {{ request('prioritas') == 'kritis'  ? 'selected' : '' }}>Kritis</option>
                    <option value="tinggi"  {{ request('prioritas') == 'tinggi'  ? 'selected' : '' }}>Tinggi</option>
                    <option value="sedang"  {{ request('prioritas') == 'sedang'  ? 'selected' : '' }}>Sedang</option>
                    <option value="rendah"  {{ request('prioritas') == 'rendah'  ? 'selected' : '' }}>Rendah</option>
                </select>
                <select name="jenis"
                        class="border border-neutral-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                    <option value="">Semua Jenis</option>
                    <option value="ringan" {{ request('jenis') == 'ringan' ? 'selected' : '' }}>Ringan</option>
                    <option value="sedang" {{ request('jenis') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                    <option value="berat"  {{ request('jenis') == 'berat'  ? 'selected' : '' }}>Berat</option>
                    <option value="total"  {{ request('jenis') == 'total'  ? 'selected' : '' }}>Total</option>
                </select>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition duration-150">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                    Filter
                </button>
                @if(request()->anyFilled(['search','prioritas','jenis']))
                <a href="{{ route('keluhan.index') }}"
                   class="inline-flex items-center px-4 py-2 border border-neutral-300 text-neutral-700 text-sm font-medium rounded-lg hover:bg-neutral-50 transition duration-150">
                    Reset
                </a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Kode Laporan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Aset</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Prioritas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    @forelse ($keluhanList as $index => $keluhan)
                    <tr class="hover:bg-neutral-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                            {{ $keluhanList->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-mono font-medium text-neutral-800">{{ $keluhan->kode_laporan }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-neutral-900">{{ $keluhan->asset->nama_asset ?? '-' }}</div>
                            <div class="text-xs text-neutral-500">{{ $keluhan->asset->kode_asset ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                            {{ $keluhan->lokasi->nama_lokasi ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $jenisClass = match($keluhan->jenis_kerusakan) {
                                    'ringan' => 'bg-green-100 text-green-800',
                                    'sedang' => 'bg-yellow-100 text-yellow-800',
                                    'berat'  => 'bg-orange-100 text-orange-800',
                                    'total'  => 'bg-red-100 text-red-800',
                                    default  => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $jenisClass }}">
                                {{ ucfirst($keluhan->jenis_kerusakan) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $prioritasClass = match($keluhan->prioritas) {
                                    'kritis' => 'bg-red-100 text-red-800',
                                    'tinggi' => 'bg-orange-100 text-orange-800',
                                    'sedang' => 'bg-yellow-100 text-yellow-800',
                                    'rendah' => 'bg-green-100 text-green-800',
                                    default  => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $prioritasClass }}">
                                {{ ucfirst($keluhan->prioritas) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClass = match($keluhan->status_perbaikan) {
                                    'dilaporkan' => 'bg-yellow-100 text-yellow-800',
                                    'diproses'   => 'bg-blue-100 text-blue-800',
                                    default      => 'bg-gray-100 text-gray-800',
                                };
                                $statusLabel = match($keluhan->status_perbaikan) {
                                    'dilaporkan' => 'Dilaporkan',
                                    'diproses'   => 'Diproses',
                                    default      => ucfirst($keluhan->status_perbaikan),
                                };
                            @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                            {{ \Carbon\Carbon::parse($keluhan->tanggal_laporan)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('keluhan.show', $keluhan->kerusakanID) }}"
                                   class="text-primary-600 hover:text-primary-900 transition duration-150"
                                   title="Lihat Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                @if($keluhan->status_perbaikan === 'dilaporkan' && !$keluhan->perbaikan)
                                <a href="{{ route('keluhan.create', $keluhan->kerusakanID) }}"
                                   class="text-green-600 hover:text-green-900 transition duration-150"
                                   title="Buat Laporan Perbaikan">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-16 text-center">
                            <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-neutral-900">Tidak ada keluhan aktif</h3>
                            <p class="mt-1 text-sm text-neutral-500">Semua keluhan sudah tertangani atau belum ada yang masuk.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($keluhanList->hasPages())
        <div class="px-6 py-4 border-t border-neutral-200">
            {{ $keluhanList->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection