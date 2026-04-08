@extends('layouts.app')

@section('title', 'Riwayat Perbaikan - Teknisi')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-neutral-900">Riwayat Perbaikan</h1>
        <p class="mt-1 text-neutral-600">Semua perbaikan yang telah kamu selesaikan</p>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">

    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg flex items-start">
        <svg class="h-5 w-5 text-green-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <p class="text-sm text-green-700">{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-200">

        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-6 bg-neutral-50 border-b border-neutral-200">
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <p class="text-sm text-neutral-600">Total Ditangani</p>
                <p class="text-2xl font-bold text-neutral-900">{{ $summary['total'] }}</p>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-green-400">
                <p class="text-sm text-neutral-600">Berhasil Diperbaiki</p>
                <p class="text-2xl font-bold text-green-600">{{ $summary['selesai'] }}</p>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-red-400">
                <p class="text-sm text-neutral-600">Tidak Bisa Diperbaiki</p>
                <p class="text-2xl font-bold text-red-600">{{ $summary['tidak_bisa'] }}</p>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-yellow-400">
                <p class="text-sm text-neutral-600">Total Biaya</p>
                <p class="text-lg font-bold text-yellow-700">Rp {{ number_format($summary['total_biaya'], 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Filter --}}
        <div class="p-6 border-b border-neutral-200 bg-gray-50">
            <form method="GET" action="{{ route('riwayat.index') }}" class="flex flex-col md:flex-row md:items-center gap-3">
                <div class="relative flex-1 max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Kode perbaikan atau nama aset..."
                           class="pl-10 pr-4 py-2 w-full border border-neutral-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <select name="status" class="border border-neutral-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500">
                        <option value="">Semua Status</option>
                        <option value="selesai"               @selected(request('status')==='selesai')>Selesai</option>
                        <option value="tidak_bisa_diperbaiki" @selected(request('status')==='tidak_bisa_diperbaiki')>Tidak Bisa Diperbaiki</option>
                    </select>
                    <select name="bulan" class="border border-neutral-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500">
                        <option value="">Semua Bulan</option>
                        @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" @selected(request('bulan') == $m)>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition duration-150">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('riwayat.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-neutral-300 text-neutral-700 text-sm font-medium rounded-lg hover:bg-neutral-50 transition duration-150">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Table --}}
        @if($riwayatList->isEmpty())
        <div class="px-6 py-12 text-center">
            <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-neutral-900">Belum ada riwayat perbaikan</h3>
            <p class="mt-1 text-sm text-neutral-500">Perbaikan yang sudah selesai akan muncul di sini.</p>
            <div class="mt-6">
                <a href="{{ route('teknisi.keluhan.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Lihat Keluhan Masuk
                </a>
            </div>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Kode Perbaikan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Keluhan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Aset</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Biaya Aktual</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Selesai</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200" id="tableBody">
                    @foreach($riwayatList as $index => $r)
                    @php
                        $statusColor = [
                            'selesai'               => 'bg-green-100 text-green-800',
                            'tidak_bisa_diperbaiki' => 'bg-red-100 text-red-800',
                            'dalam_perbaikan'       => 'bg-blue-100 text-blue-800',
                        ];
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                            {{ $riwayatList->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-mono font-medium text-purple-700">{{ $r->kode_perbaikan }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-mono text-neutral-500">{{ $r->kerusakan->kode_laporan ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-neutral-900">{{ $r->kerusakan->asset->nama_asset ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                            {{ $r->kerusakan->lokasi->nama_lokasi ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                            {{ $r->biaya_aktual ? 'Rp ' . number_format($r->biaya_aktual, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                            {{ $r->selesai_perbaikan ? $r->selesai_perbaikan->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full inline-flex items-center {{ $statusColor[$r->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $r->label_status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('riwayat.show', $r->perbaikanID) }}"
                                   title="Detail" class="text-purple-600 hover:text-purple-900 transition duration-150">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('riwayat.pdf', $r->perbaikanID) }}"
                                   title="Download PDF" target="_blank" class="text-red-500 hover:text-red-700 transition duration-150">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($riwayatList->hasPages())
        <div class="px-6 py-4 border-t border-neutral-200 bg-gray-50">
            {{ $riwayatList->withQueryString()->links() }}
        </div>
        @endif
        @endif

    </div>
</div>
@endsection