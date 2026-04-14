@extends('layouts.app')

@section('title', 'Dashboard Teknisi')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-neutral-900">Dashboard Teknisi</h1>
        <p class="mt-1 text-neutral-500 text-sm">Pantau keluhan masuk dan riwayat perbaikan kamu.</p>
    </div>
    <div class="mt-4 sm:mt-0 flex items-center space-x-2 text-sm text-neutral-500">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <span>{{ now()->translatedFormat('l, d F Y') }}</span>
    </div>
</div>
@endsection

@section('content')

{{-- ── STAT CARDS ──────────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">

    {{-- Keluhan Aktif --}}
    <div class="bg-white rounded-2xl shadow-card p-5 flex items-center space-x-4 border border-neutral-100 hover:shadow-card-hover transition-shadow duration-200">
        <div class="flex-shrink-0 w-14 h-14 rounded-xl flex items-center justify-center shadow-dp" style="background-color: #f59e0b;">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">Keluhan Aktif</p>
            <p class="text-3xl font-bold text-neutral-900">{{ $keluhanAktif }}</p>
            <p class="text-xs font-medium mt-0.5" style="color: #f59e0b;">Perlu ditangani</p>
        </div>
    </div>

    {{-- Prioritas Kritis --}}
    <div class="bg-white rounded-2xl shadow-card p-5 flex items-center space-x-4 border border-neutral-100 hover:shadow-card-hover transition-shadow duration-200">
        <div class="flex-shrink-0 w-14 h-14 rounded-xl flex items-center justify-center shadow-dp" style="background-color: #ef4444;">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">Prioritas Kritis</p>
            <p class="text-3xl font-bold text-neutral-900">{{ $keluhanKritis }}</p>
            <p class="text-xs font-medium mt-0.5" style="color: #ef4444;">Segera tangani</p>
        </div>
    </div>

    {{-- Total Perbaikan --}}
    <div class="bg-white rounded-2xl shadow-card p-5 flex items-center space-x-4 border border-neutral-100 hover:shadow-card-hover transition-shadow duration-200">
        <div class="flex-shrink-0 w-14 h-14 rounded-xl bg-gradient-primary flex items-center justify-center shadow-dp">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">Total Perbaikan</p>
            <p class="text-3xl font-bold text-neutral-900">{{ $totalPerbaikan }}</p>
            <p class="text-xs text-green-600 font-medium mt-0.5">{{ $perbaikanSelesai }} selesai</p>
        </div>
    </div>

    {{-- Total Biaya --}}
    <div class="bg-white rounded-2xl shadow-card p-5 flex items-center space-x-4 border border-neutral-100 hover:shadow-card-hover transition-shadow duration-200">
        <div class="flex-shrink-0 w-14 h-14 rounded-xl bg-gradient-secondary flex items-center justify-center shadow-dp">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">Total Biaya</p>
            <p class="text-xl font-bold text-neutral-900">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</p>
            <p class="text-xs text-neutral-400 mt-0.5 font-medium">Dari perbaikan selesai</p>
        </div>
    </div>

</div>

{{-- ── ROW 2: Keluhan Terbaru + Aksi Cepat ────────────────────────────────── --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-6">

    {{-- Keluhan Masuk Terbaru --}}
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-card border border-neutral-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-100 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <h2 class="text-base font-bold text-neutral-900">Keluhan Masuk</h2>
                @if($keluhanAktif > 0)
                    <span class="px-2 py-0.5 text-xs font-bold rounded-full text-white" style="background-color:#f59e0b">
                        {{ $keluhanAktif }}
                    </span>
                @endif
            </div>
            <a href="{{ route('keluhan.index') }}" class="text-xs text-primary-600 hover:text-primary-800 font-semibold">
                Lihat semua →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-100">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Aset</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Prioritas</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-100">
                    @forelse($keluhanTerbaru as $keluhan)
                    @php
                        $prioritasClass = match($keluhan->prioritas) {
                            'kritis' => 'bg-red-100 text-red-700',
                            'tinggi' => 'bg-orange-100 text-orange-700',
                            'sedang' => 'bg-blue-100 text-blue-700',
                            default  => 'bg-neutral-100 text-neutral-600',
                        };
                        $statusClass = $keluhan->status_perbaikan === 'dilaporkan'
                            ? 'bg-yellow-100 text-yellow-700'
                            : 'bg-blue-100 text-blue-700';
                        $statusLabel = $keluhan->status_perbaikan === 'dilaporkan' ? 'Menunggu' : 'Diproses';
                    @endphp
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-neutral-900">{{ $keluhan->asset->nama_asset ?? '-' }}</div>
                            <div class="text-xs text-neutral-500 font-mono">{{ $keluhan->kode_laporan }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-600">{{ $keluhan->lokasi->nama_lokasi ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $prioritasClass }}">
                                {{ ucfirst($keluhan->prioritas) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('keluhan.show', $keluhan->kerusakanID) }}"
                               class="text-xs text-primary-600 hover:underline font-semibold">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-sm text-neutral-400">
                            🎉 Tidak ada keluhan yang perlu ditangani saat ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Aksi Cepat --}}
    <div class="bg-white rounded-2xl shadow-card border border-neutral-100 p-6">
        <h2 class="text-base font-bold text-neutral-900 mb-4">Aksi Cepat</h2>
        <div class="space-y-3">

            <a href="{{ route('keluhan.index') }}"
               class="group flex items-center space-x-3 p-4 rounded-xl bg-yellow-50 hover:bg-yellow-100 transition-colors border border-yellow-100">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform"
                     style="background-color: #f59e0b;">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-yellow-800">Lihat Keluhan Masuk</p>
                    <p class="text-xs text-yellow-600">{{ $keluhanAktif }} keluhan aktif</p>
                </div>
            </a>

            <a href="{{ route('riwayat.index') }}"
               class="group flex items-center space-x-3 p-4 rounded-xl bg-green-50 hover:bg-green-100 transition-colors border border-green-100">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform"
                     style="background-color: #10b981;">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-green-800">Riwayat Perbaikan</p>
                    <p class="text-xs text-green-600">{{ $perbaikanSelesai }} selesai</p>
                </div>
            </a>

            @if($keluhanKritis > 0)
            <div class="p-4 rounded-xl bg-red-50 border border-red-200">
                <div class="flex items-center space-x-2 mb-1">
                    <svg class="w-4 h-4 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-sm font-bold text-red-800">Peringatan!</p>
                </div>
                <p class="text-xs text-red-700">Ada <strong>{{ $keluhanKritis }} keluhan kritis</strong> yang menunggu penanganan segera.</p>
                <a href="{{ route('keluhan.index', ['prioritas' => 'kritis']) }}"
                   class="mt-2 inline-block text-xs font-semibold text-red-700 hover:underline">
                    Tangani sekarang →
                </a>
            </div>
            @else
            <div class="p-4 rounded-xl bg-green-50 border border-green-200">
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-semibold text-green-800">Tidak ada keluhan kritis</p>
                </div>
                <p class="text-xs text-green-700 mt-1">Semua aset kritis dalam kondisi terkendali.</p>
            </div>
            @endif

        </div>
    </div>
</div>

{{-- ── ROW 3: Riwayat Perbaikan Terbaru ───────────────────────────────────── --}}
<div class="bg-white rounded-2xl shadow-card border border-neutral-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-neutral-100 flex items-center justify-between">
        <h2 class="text-base font-bold text-neutral-900">Riwayat Perbaikan Terbaru</h2>
        <a href="{{ route('riwayat.index') }}" class="text-xs text-primary-600 hover:text-primary-800 font-semibold">
            Lihat semua →
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-neutral-100">
            <thead class="bg-neutral-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Kode Perbaikan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Aset</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Lokasi</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Biaya Aktual</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Selesai</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-100">
                @forelse($riwayatTerbaru as $r)
                @php
                    $stClass = match($r->status) {
                        'selesai'               => 'bg-green-100 text-green-700',
                        'tidak_bisa_diperbaiki' => 'bg-red-100 text-red-700',
                        default                 => 'bg-neutral-100 text-neutral-600',
                    };
                @endphp
                <tr class="hover:bg-neutral-50 transition-colors">
                    <td class="px-6 py-4">
                        <span class="font-mono text-xs font-semibold text-primary-600">{{ $r->kode_perbaikan }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-semibold text-neutral-900">{{ $r->kerusakan->asset->nama_asset ?? '-' }}</div>
                        <div class="text-xs text-neutral-500 font-mono">{{ $r->kerusakan->kode_laporan ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-neutral-600">{{ $r->kerusakan->lokasi->NamaLokasi ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-neutral-700">
                        {{ $r->biaya_aktual ? 'Rp ' . number_format($r->biaya_aktual, 0, ',', '.') : '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-neutral-500">
                        {{ $r->selesai_perbaikan ? $r->selesai_perbaikan->format('d/m/Y') : '-' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $stClass }}">
                            {{ $r->label_status }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('riwayat.show', $r->perbaikanID) }}"
                           class="text-xs text-primary-600 hover:underline font-semibold">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-sm text-neutral-400">
                        Belum ada riwayat perbaikan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection