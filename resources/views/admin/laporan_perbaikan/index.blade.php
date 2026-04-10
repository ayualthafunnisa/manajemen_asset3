@extends('layouts.app')
@section('content')

{{-- Summary Cards --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-neutral-100">
        <p class="text-xs text-neutral-400 font-medium uppercase tracking-wide">Total Laporan</p>
        <p class="text-3xl font-extrabold text-neutral-800 mt-1">{{ $summary['total'] }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-neutral-100">
        <p class="text-xs text-neutral-400 font-medium uppercase tracking-wide">Selesai</p>
        <p class="text-3xl font-extrabold text-green-600 mt-1">{{ $summary['selesai'] }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-neutral-100">
        <p class="text-xs text-neutral-400 font-medium uppercase tracking-wide">Tidak Bisa Diperbaiki</p>
        <p class="text-3xl font-extrabold text-red-500 mt-1">{{ $summary['tidak_bisa'] }}</p>
    </div>
</div>

{{-- Filter --}}
<div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-5 mb-6">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari kode, teknisi, aset..."
               class="flex-1 min-w-48 px-4 py-2.5 rounded-xl border border-neutral-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-300">

        <select name="status" class="px-4 py-2.5 rounded-xl border border-neutral-200 text-sm focus:outline-none">
            <option value="">Semua Status</option>
            <option value="selesai" @selected(request('status') === 'selesai')>Selesai</option>
            <option value="tidak_bisa_diperbaiki" @selected(request('status') === 'tidak_bisa_diperbaiki')>Tidak Bisa Diperbaiki</option>
        </select>

        <select name="bulan" class="px-4 py-2.5 rounded-xl border border-neutral-200 text-sm focus:outline-none">
            <option value="">Semua Bulan</option>
            @foreach(range(1,12) as $m)
                <option value="{{ $m }}" @selected(request('bulan') == $m)>
                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                </option>
            @endforeach
        </select>

        <button class="px-5 py-2.5 bg-primary-600 text-white rounded-xl text-sm font-semibold hover:bg-primary-700 transition">
            Filter
        </button>
        <a href="{{ route('laporan_masuk.index') }}"
           class="px-5 py-2.5 bg-neutral-100 text-neutral-600 rounded-xl text-sm font-semibold hover:bg-neutral-200 transition">
            Reset
        </a>
    </form>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl shadow-sm border border-neutral-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-neutral-50 border-b border-neutral-100">
            <tr>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-neutral-400 uppercase tracking-wide">Kode</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-neutral-400 uppercase tracking-wide">Aset</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-neutral-400 uppercase tracking-wide">Teknisi</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-neutral-400 uppercase tracking-wide">Tanggal Selesai</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-neutral-400 uppercase tracking-wide">Status</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-neutral-400 uppercase tracking-wide">Dibaca</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-neutral-400 uppercase tracking-wide">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-neutral-50">
            @forelse($laporanList as $item)
            <tr class="hover:bg-neutral-50 transition {{ !$item->dibaca_admin ? 'bg-primary-50/30' : '' }}">
                <td class="px-5 py-4 font-mono text-xs text-neutral-600">{{ $item->kode_perbaikan }}</td>
                <td class="px-5 py-4 text-neutral-700 font-medium">
                    {{ $item->kerusakan->asset->nama_asset ?? '-' }}
                </td>
                <td class="px-5 py-4 text-neutral-600">{{ $item->teknisi->name ?? '-' }}</td>
                <td class="px-5 py-4 text-neutral-500 text-xs">
                    {{ $item->selesai_perbaikan ? $item->selesai_perbaikan->format('d M Y') : '-' }}
                </td>
                <td class="px-5 py-4">
                    @if($item->status === 'selesai')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            Selesai
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-600">
                            Tidak Bisa Diperbaiki
                        </span>
                    @endif
                </td>
                <td class="px-5 py-4">
                    @if($item->dibaca_admin)
                        <span class="text-xs text-neutral-400">Sudah dibaca</span>
                    @else
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-primary-600">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 inline-block"></span>
                            Baru
                        </span>
                    @endif
                </td>
                <td class="px-5 py-4">
                    <a href="{{ route('laporan_masuk.lihat', $item->perbaikanID) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary-600 text-white text-xs font-semibold rounded-lg hover:bg-primary-700 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download PDF
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-5 py-16 text-center text-neutral-400 text-sm">
                    Belum ada laporan perbaikan masuk.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($laporanList->hasPages())
    <div class="px-5 py-4 border-t border-neutral-100">
        {{ $laporanList->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection