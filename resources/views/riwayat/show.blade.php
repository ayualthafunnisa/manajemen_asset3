@extends('layouts.app')

@section('title', 'Detail Riwayat — ' . $perbaikan->kode_perbaikan)

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-white shadow-sm border border-gray-200 px-4 py-3 rounded-lg">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.teknisi') }}" class="text-gray-600 hover:text-purple-600 transition duration-150">Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('riwayat.index') }}" class="text-gray-600 hover:text-purple-600 transition duration-150">Riwayat Perbaikan</a>
        </li>
        <li class="breadcrumb-item active text-gray-800" aria-current="page">Detail Riwayat</li>
    </ol>
</nav>
@endsection

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Detail Riwayat Perbaikan</h1>
        <p class="mt-1 text-gray-600">{{ $perbaikan->kode_perbaikan }}</p>
    </div>
    <div class="mt-4 sm:mt-0 flex gap-2">
        <a href="{{ route('riwayat.pdf', $perbaikan->perbaikanID) }}"
           class="inline-flex items-center px-4 py-2 border border-red-500 text-red-600 rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-red-50 transition duration-150" target="_blank">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            Cetak PDF
        </a>
        <a href="{{ route('riwayat.index') }}"
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
<div class="max-w-5xl mx-auto">

    {{-- Status Bar --}}
    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200 mb-6">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center divide-y md:divide-y-0 md:divide-x divide-gray-200">
                <div class="py-3 md:py-0">
                    <p class="text-xs text-gray-500 mb-2">Status</p>
                    @php
                        $badgeMap = [
                            'selesai'               => 'bg-green-100 text-green-800',
                            'tidak_bisa_diperbaiki' => 'bg-red-100 text-red-800',
                            'dalam_perbaikan'       => 'bg-blue-100 text-blue-800',
                        ];
                    @endphp
                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $badgeMap[$perbaikan->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $perbaikan->label_status }}
                    </span>
                </div>
                <div class="py-3 md:py-0 md:pl-4">
                    <p class="text-xs text-gray-500 mb-1">Mulai Perbaikan</p>
                    <p class="text-sm font-semibold text-gray-900">
                        {{ $perbaikan->mulai_perbaikan ? $perbaikan->mulai_perbaikan->format('d M Y H:i') : '-' }}
                    </p>
                </div>
                <div class="py-3 md:py-0 md:pl-4">
                    <p class="text-xs text-gray-500 mb-1">Selesai Perbaikan</p>
                    <p class="text-sm font-semibold text-gray-900">
                        {{ $perbaikan->selesai_perbaikan ? $perbaikan->selesai_perbaikan->format('d M Y H:i') : '-' }}
                    </p>
                </div>
                <div class="py-3 md:py-0 md:pl-4">
                    <p class="text-xs text-gray-500 mb-1">Biaya Aktual</p>
                    <p class="text-sm font-semibold text-green-700">
                        {{ $perbaikan->biaya_aktual ? 'Rp ' . number_format($perbaikan->biaya_aktual, 0, ',', '.') : '-' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Tindakan Perbaikan --}}
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-gray-900">
                        <svg class="w-5 h-5 inline mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Tindakan Perbaikan
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-2">Tindakan yang Dilakukan</p>
                        <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-700 whitespace-pre-line">{{ $perbaikan->tindakan_perbaikan }}</div>
                    </div>

                    @if($perbaikan->catatan_perbaikan)
                    <div>
                        <p class="text-xs text-gray-500 mb-2">Catatan Tambahan</p>
                        <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-700">{{ $perbaikan->catatan_perbaikan }}</div>
                    </div>
                    @endif

                    @if($perbaikan->alasan_tidak_bisa)
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                        <p class="text-sm font-semibold text-red-700 mb-1">Alasan Tidak Bisa Diperbaiki</p>
                        <p class="text-sm text-red-600">{{ $perbaikan->alasan_tidak_bisa }}</p>
                    </div>
                    @endif

                    @if($perbaikan->komponen_diganti || $perbaikan->biaya_aktual)
                    <div class="grid grid-cols-2 gap-4 pt-2 border-t border-gray-100">
                        @if($perbaikan->komponen_diganti)
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Komponen Diganti</p>
                            <p class="text-sm font-medium text-gray-900">{{ $perbaikan->komponen_diganti }}</p>
                        </div>
                        @endif
                        @if($perbaikan->biaya_aktual)
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Biaya Aktual</p>
                            <p class="text-sm font-medium text-green-700">Rp {{ number_format($perbaikan->biaya_aktual, 0, ',', '.') }}</p>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            {{-- Keluhan Terkait --}}
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-orange-50 px-6 py-4 border-b border-orange-100 flex items-center justify-between">
                    <h3 class="text-base font-semibold text-orange-900">Keluhan Terkait</h3>
                    <a href="{{ route('keluhan.show', $perbaikan->kerusakanID) }}"
                       class="inline-flex items-center px-3 py-1 border border-orange-300 text-orange-700 rounded-lg hover:bg-orange-100 transition text-xs font-medium">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Lihat Keluhan
                    </a>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Kode Laporan</p>
                            <p class="text-sm font-medium text-gray-900 font-mono">{{ $perbaikan->kerusakan->kode_laporan ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Aset</p>
                            <p class="text-sm font-medium text-gray-900">{{ $perbaikan->kerusakan->asset->nama_asset ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Lokasi</p>
                            <p class="text-sm font-medium text-gray-900">{{ $perbaikan->kerusakan->lokasi->nama_lokasi ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Jenis Kerusakan</p>
                            <p class="text-sm font-medium text-gray-900">{{ ucfirst($perbaikan->kerusakan->jenis_kerusakan ?? '-') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Dilaporkan Oleh</p>
                            <p class="text-sm font-medium text-gray-900">{{ $perbaikan->kerusakan->pelapor->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Estimasi Biaya</p>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $perbaikan->kerusakan->estimasi_biaya ? 'Rp ' . number_format($perbaikan->kerusakan->estimasi_biaya, 0, ',', '.') : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Kolom Kanan --}}
        <div class="space-y-6">

            {{-- Foto Sesudah --}}
            @if($perbaikan->foto_sesudah)
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-gray-900">Foto Sesudah Perbaikan</h3>
                </div>
                <div class="p-3">
                    <img src="{{ Storage::url($perbaikan->foto_sesudah) }}"
                         class="w-full rounded-lg object-cover border border-gray-200" style="max-height:220px"
                         alt="Foto Sesudah">
                </div>
            </div>
            @endif

            {{-- Foto Sebelum --}}
            @if($perbaikan->kerusakan->foto_kerusakan)
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-gray-900">Foto Sebelum Perbaikan</h3>
                </div>
                <div class="p-3">
                    <img src="{{ Storage::url($perbaikan->kerusakan->foto_kerusakan) }}"
                         class="w-full rounded-lg object-cover border border-gray-200" style="max-height:180px"
                         alt="Foto Sebelum">
                </div>
            </div>
            @endif

            {{-- Info Teknisi --}}
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-blue-50 px-6 py-4 border-b border-blue-100">
                    <h3 class="text-base font-semibold text-blue-900">Info Teknisi</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $perbaikan->teknisi->name ?? '-' }}</p>
                            <p class="text-sm text-gray-500">{{ $perbaikan->teknisi->email ?? '' }}</p>
                        </div>
                    </div>
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="py-2 text-gray-500">Kode Perbaikan</td>
                                <td class="py-2 font-medium text-purple-700 font-mono text-right">{{ $perbaikan->kode_perbaikan }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-gray-500">Dibuat</td>
                                <td class="py-2 font-medium text-gray-900 text-right">{{ $perbaikan->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection