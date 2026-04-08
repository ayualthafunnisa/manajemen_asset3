@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-neutral-900">Dashboard Admin Sekolah</h1>
        <p class="mt-1 text-neutral-500 text-sm">Pantau dan kelola aset sekolah Anda dengan mudah.</p>
    </div>
    <div class="mt-4 sm:mt-0 flex items-center space-x-2 text-sm text-neutral-500">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <span>{{ now()->translatedFormat('l, d F Y') }}</span>
    </div>
</div>
@endsection



@section('content')

    @if($license)
    <div class="mb-6 p-4 rounded-xl border 
        @if($sisaHari <= 7) border-red-300 bg-red-50
        @elseif($sisaHari <= 30) border-yellow-300 bg-yellow-50
        @else border-green-300 bg-green-50
        @endif
    ">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm font-semibold">Status Lisensi</p>
                <p class="text-xs text-gray-600">
                    Berlaku sampai: {{ $license->expired_date->format('d M Y') }}
                </p>
            </div>
            <div class="text-right">
                <p class="text-lg font-bold">
                    {{ $sisaHari }} hari
                </p>
                <p class="text-xs">tersisa</p>
            </div>
        </div>

        @if($sisaHari <= 7)
            <p class="text-xs text-red-600 mt-2 font-semibold">
                ⚠️ Lisensi akan segera habis! Segera perpanjang.
            </p>
        @endif
    </div>
    @endif
{{-- STAT CARDS --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">

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
            <p class="text-xs text-accent-500 mt-0.5 font-medium">+{{ $assetBaru ?? 0 }} bulan ini</p>
        </div>
    </div>

    {{-- Asset Rusak --}}
    <div class="bg-white rounded-2xl shadow-card p-5 flex items-center space-x-4 border border-neutral-100 hover:shadow-card-hover transition-shadow duration-200">
        <div class="flex-shrink-0 w-14 h-14 rounded-xl flex items-center justify-center shadow-dp" style="background-color: #ef4444;">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">Asset Rusak</p>
            <p class="text-3xl font-bold text-neutral-900">{{ $assetRusak ?? 0 }}</p>
            <p class="text-xs font-medium mt-0.5" style="color: #ef4444;">Perlu perhatian</p>
        </div>
    </div>

    {{-- Penghapusan Pending --}}
    <div class="bg-white rounded-2xl shadow-card p-5 flex items-center space-x-4 border border-neutral-100 hover:shadow-card-hover transition-shadow duration-200">
        <div class="flex-shrink-0 w-14 h-14 rounded-xl flex items-center justify-center shadow-dp" style="background-color: #f59e0b;">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">Pengajuan Hapus</p>
            <p class="text-3xl font-bold text-neutral-900">{{ $penghapusanPending ?? 0 }}</p>
            <p class="text-xs font-medium mt-0.5" style="color: #f59e0b;">Menunggu approval</p>
        </div>
    </div>

    {{-- Total Kategori --}}
    <div class="bg-white rounded-2xl shadow-card p-5 flex items-center space-x-4 border border-neutral-100 hover:shadow-card-hover transition-shadow duration-200">
        <div class="flex-shrink-0 w-14 h-14 rounded-xl bg-gradient-secondary flex items-center justify-center shadow-dp">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">Kategori</p>
            <p class="text-3xl font-bold text-neutral-900">{{ $totalKategori ?? 0 }}</p>
            <p class="text-xs text-info-DEFAULT mt-0.5 font-medium">{{ $totalLokasi ?? 0 }} lokasi</p>
        </div>
    </div>

</div>

{{-- ROW 2 --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-6">

    {{-- Penghapusan Pending - Approval Panel --}}
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-card border border-neutral-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-100 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <h2 class="text-base font-bold text-neutral-900">Pengajuan Penghapusan</h2>
                @if(($penghapusanPending ?? 0) > 0)
                <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-warning-DEFAULT text-white">{{ $penghapusanPending }}</span>
                @endif
            </div>
            <a href="{{ route('penghapusan.index') }}" class="text-xs text-primary-600 hover:text-primary-800 font-semibold">Lihat semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-100">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Asset</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Diajukan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Alasan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-100">
                    @forelse ($penghapusanList ?? [] as $item)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-neutral-900">{{ $item->asset->NamaAsset ?? '-' }}</div>
                            <div class="text-xs text-neutral-500">{{ $item->asset->KodeAsset ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-600">{{ $item->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm text-neutral-600 max-w-xs truncate">{{ $item->Alasan ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <form action="{{ route('penghapusan.approve', $item->PenghapusanID) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 text-xs font-semibold bg-accent-500 text-white rounded-lg hover:bg-accent-600 transition-colors">Setuju</button>
                                </form>
                                <form action="{{ route('penghapusan.reject', $item->PenghapusanID) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 text-xs font-semibold bg-danger-DEFAULT text-white rounded-lg hover:bg-danger-dark transition-colors">Tolak</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-sm text-neutral-400">Tidak ada pengajuan penghapusan saat ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Akses Cepat --}}
    <div class="bg-white rounded-2xl shadow-card border border-neutral-100 p-6">
        <h2 class="text-base font-bold text-neutral-900 mb-4">Akses Cepat</h2>
        <div class="grid grid-cols-2 gap-3">

            <a href="{{ route('asset.create') }}" class="group flex flex-col items-center justify-center p-4 rounded-xl bg-primary-50 hover:bg-primary-100 transition-colors text-center border border-primary-100">
                <div class="w-10 h-10 rounded-lg bg-primary-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <span class="text-xs font-semibold text-primary-800">Tambah Asset</span>
            </a>

            <a href="{{ route('kategori.create') }}" class="group flex flex-col items-center justify-center p-4 rounded-xl text-center border border-blue-100 bg-blue-50 hover:bg-blue-100 transition-colors">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform" style="background-color: #3b82f6;">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
                <span class="text-xs font-semibold text-blue-800">Tambah Kategori</span>
            </a>

            <a href="{{ route('user.index') }}" class="group flex flex-col items-center justify-center p-4 rounded-xl text-center border border-green-100 bg-green-50 hover:bg-green-100 transition-colors">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform" style="background-color: #10b981;">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <span class="text-xs font-semibold text-green-800">Kelola User</span>
            </a>

            <a href="{{ route('penyusutan.index') }}" class="group flex flex-col items-center justify-center p-4 rounded-xl text-center border border-yellow-100 bg-yellow-50 hover:bg-yellow-100 transition-colors">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform" style="background-color: #f59e0b;">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <span class="text-xs font-semibold text-yellow-800">Penyusutan</span>
            </a>

        </div>
    </div>

</div>

{{-- ROW 3: Asset Rusak Terbaru --}}
<div class="bg-white rounded-2xl shadow-card border border-neutral-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-neutral-100 flex items-center justify-between">
        <h2 class="text-base font-bold text-neutral-900">Laporan Kerusakan Terbaru</h2>
        <a href="{{ route('kerusakan.index') }}" class="text-xs text-primary-600 hover:text-primary-800 font-semibold">Lihat semua →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-neutral-100">
            <thead class="bg-neutral-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Asset</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Lokasi</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Dilaporkan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-100">
                @forelse ($kerusakanTerbaru ?? [] as $kerusakan)
                <tr class="hover:bg-neutral-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="text-sm font-semibold text-neutral-900">{{ $kerusakan->asset->NamaAsset ?? '-' }}</div>
                        <div class="text-xs text-neutral-500">{{ $kerusakan->asset->KodeAsset ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-neutral-600">{{ $kerusakan->asset->lokasi->NamaLokasi ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-neutral-500">{{ $kerusakan->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">
                        @php
                            $statusClass = match($kerusakan->status_perbaikan ?? 'dilaporkan') {
                                'selesai'                  => 'bg-green-100 text-green-700',
                                'dalam_perbaikan'          => 'bg-blue-100 text-blue-700',
                                'dalam_investigasi'        => 'bg-purple-100 text-purple-700',
                                'menunggu_part'            => 'bg-orange-100 text-orange-700',
                                'tidak_diperbaiki'         => 'bg-red-100 text-red-700',
                                'penghapusan_diusulkan'    => 'bg-gray-100 text-gray-700',
                                default                    => 'bg-yellow-100 text-yellow-700',
                            };
                            $statusLabel = match($kerusakan->status_perbaikan ?? 'dilaporkan') {
                                'dilaporkan'               => 'Dilaporkan',
                                'dalam_investigasi'        => 'Investigasi',
                                'dalam_perbaikan'          => 'Diperbaiki',
                                'menunggu_part'            => 'Tunggu Part',
                                'selesai'                  => 'Selesai',
                                'tidak_diperbaiki'         => 'Tidak Diperbaiki',
                                'penghapusan_diusulkan'    => 'Usul Hapus',
                                default                    => ucfirst($kerusakan->status_perbaikan ?? '-'),
                            };
                        @endphp
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('kerusakan.show', $kerusakan->kerusakanID) }}" class="text-xs text-primary-600 hover:underline font-semibold">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-sm text-neutral-400">Tidak ada laporan kerusakan saat ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection