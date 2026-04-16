@extends('layouts.app')

@section('title', 'Staff Dashboard')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-neutral-900">Dashboard Staff Asset</h1>
        <p class="mt-1 text-neutral-500 text-sm">Kelola, laporkan, dan pantau kondisi aset sekolah.</p>
    </div>
    <div class="mt-4 sm:mt-0 flex items-center space-x-2 text-sm text-neutral-500">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <span>{{ now()->translatedFormat('l, d F Y') }}</span>
    </div>
</div>
@endsection

@section('content')

{{-- STAT CARDS --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">

    {{-- Total Asset --}}
    <div class="bg-white rounded-2xl shadow-card p-5 flex items-center space-x-4 border border-neutral-100 hover:shadow-card-hover transition-shadow duration-200">
        <div class="flex-shrink-0 w-14 h-14 rounded-xl bg-gradient-primary flex items-center justify-center shadow-dp">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">Total Asset</p>
            <p class="text-3xl font-bold text-neutral-900">{{ $totalAsset ?? 0 }}</p>
            <p class="text-xs text-neutral-400 mt-0.5">Di semua lokasi</p>
        </div>
    </div>

    {{-- Laporan Saya --}}
    <div class="bg-white rounded-2xl shadow-card p-5 flex items-center space-x-4 border border-neutral-100 hover:shadow-card-hover transition-shadow duration-200">
        <div class="flex-shrink-0 w-14 h-14 rounded-xl flex items-center justify-center shadow-dp" style="background-color: #ef4444;">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">Laporan Kerusakan</p>
            <p class="text-3xl font-bold text-neutral-900">{{ $totalKerusakan ?? 0 }}</p>
            <p class="text-xs font-medium mt-0.5" style="color: #ef4444;">{{ $kerusakanAktif ?? 0 }} belum selesai</p>
        </div>
    </div>

    {{-- Pengajuan Penghapusan --}}
    <div class="bg-white rounded-2xl shadow-card p-5 flex items-center space-x-4 border border-neutral-100 hover:shadow-card-hover transition-shadow duration-200">
        <div class="flex-shrink-0 w-14 h-14 rounded-xl flex items-center justify-center shadow-dp" style="background-color: #f59e0b;">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">Pengajuan Hapus</p>
            <p class="text-3xl font-bold text-neutral-900">{{ $totalPenghapusan ?? 0 }}</p>
            <p class="text-xs font-medium mt-0.5" style="color: #f59e0b;">{{ $penghapusanPending ?? 0 }} menunggu</p>
        </div>
    </div>

</div>

{{-- ROW 2 --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-6">

    {{-- Daftar Asset Terbaru --}}
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-card border border-neutral-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-100 flex items-center justify-between">
            <h2 class="text-base font-bold text-neutral-900">Asset Terbaru</h2>
            <a href="{{ route('asset.index') }}" class="text-xs text-primary-600 hover:text-primary-800 font-semibold">Lihat semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-100">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Asset</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Kondisi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-100">
                    @forelse ($assetTerbaru ?? [] as $asset)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-neutral-900">{{ $asset->nama_asset }}</div>
                            <div class="text-xs text-neutral-500">{{ $asset->kode_asset }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">{{ $asset->kategori->NamaKategori ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-600">{{ $asset->lokasi->NamaLokasi ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @php
                            $kondisiClass = match($asset->Kondisi ?? 'baik') {
                                'baik'  => 'bg-green-100 text-green-700',
                                'rusak' => 'bg-red-100 text-red-700',
                                default => 'bg-yellow-100 text-yellow-700',
                            };
                        @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $kondisiClass }}">
                                {{ ucfirst($asset->Kondisi ?? 'baik') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('asset.show', $asset->assetID) }}" class="text-xs text-primary-600 hover:underline font-semibold">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-sm text-neutral-400">Belum ada asset tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Aksi Cepat Staff --}}
    <div class="bg-white rounded-2xl shadow-card border border-neutral-100 p-6">
        <h2 class="text-base font-bold text-neutral-900 mb-4">Aksi Cepat</h2>
        <div class="space-y-3">

            <a href="{{ route('asset.create') }}" class="group flex items-center space-x-3 p-4 rounded-xl bg-primary-50 hover:bg-primary-100 transition-colors border border-primary-100">
                <div class="w-10 h-10 rounded-lg bg-primary-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-primary-800">Tambah Asset Baru</p>
                    <p class="text-xs text-primary-600">Daftarkan aset ke sistem</p>
                </div>
            </a>

            <a href="{{ route('kerusakan.create') }}" class="group flex items-center space-x-3 p-4 rounded-xl bg-red-50 hover:bg-red-100 transition-colors border border-red-100">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform" style="background-color: #ef4444;">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-red-800">Laporkan Kerusakan</p>
                    <p class="text-xs text-red-600">Catat kerusakan aset</p>
                </div>
            </a>

            <a href="{{ route('penghapusan.create') }}" class="group flex items-center space-x-3 p-4 rounded-xl bg-yellow-50 hover:bg-yellow-100 transition-colors border border-yellow-100">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform" style="background-color: #f59e0b;">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-yellow-800">Ajukan Penghapusan</p>
                    <p class="text-xs text-yellow-600">Kirim permintaan hapus aset</p>
                </div>
            </a>

            <a href="{{ route('asset.index') }}" class="group flex items-center space-x-3 p-4 rounded-xl bg-neutral-50 hover:bg-neutral-100 transition-colors border border-neutral-100">
                <div class="w-10 h-10 rounded-lg bg-neutral-500 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-neutral-700">Lihat Semua Asset</p>
                    <p class="text-xs text-neutral-500">Inventaris lengkap</p>
                </div>
            </a>

        </div>
    </div>

</div>

{{-- ROW 3: Laporan Kerusakan Saya --}}
<div class="bg-white rounded-2xl shadow-card border border-neutral-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-neutral-100 flex items-center justify-between">
        <h2 class="text-base font-bold text-neutral-900">Laporan Kerusakan Saya</h2>
        <a href="{{ route('kerusakan.index') }}" class="text-xs text-primary-600 hover:text-primary-800 font-semibold">Lihat semua →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-neutral-100">
            <thead class="bg-neutral-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Asset</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Keterangan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-100">
                @forelse ($kerusakanSaya ?? [] as $kerusakan)
                <tr class="hover:bg-neutral-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="text-sm font-semibold text-neutral-900">{{ $kerusakan->asset->NamaAsset ?? '-' }}</div>
                        <div class="text-xs text-neutral-500">{{ $kerusakan->asset->KodeAsset ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-neutral-600 max-w-xs truncate">{{ $kerusakan->Keterangan ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-neutral-500">{{ $kerusakan->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">
                        @php
                            $stClass = match($kerusakan->status_perbaikan ?? 'dilaporkan') {
                                'selesai'               => 'bg-green-100 text-green-700',
                                'dalam_perbaikan'       => 'bg-blue-100 text-blue-700',
                                'dalam_investigasi'     => 'bg-purple-100 text-purple-700',
                                'menunggu_part'         => 'bg-orange-100 text-orange-700',
                                'tidak_diperbaiki'      => 'bg-red-100 text-red-700',
                                'penghapusan_diusulkan' => 'bg-gray-100 text-gray-700',
                                default                 => 'bg-yellow-100 text-yellow-700',
                            };
                            $stLabel = match($kerusakan->status_perbaikan ?? 'dilaporkan') {
                                'dilaporkan'            => 'Dilaporkan',
                                'dalam_investigasi'     => 'Investigasi',
                                'dalam_perbaikan'       => 'Diperbaiki',
                                'menunggu_part'         => 'Tunggu Part',
                                'selesai'               => 'Selesai',
                                'tidak_diperbaiki'      => 'Tidak Diperbaiki',
                                'penghapusan_diusulkan' => 'Usul Hapus',
                                default                 => ucfirst($kerusakan->status_perbaikan ?? '-'),
                            };
                        @endphp
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $stClass }}">
                            {{ $stLabel }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('kerusakan.show', $kerusakan->kerusakanID) }}" class="text-xs text-primary-600 hover:underline font-semibold">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-sm text-neutral-400">Belum ada laporan kerusakan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection