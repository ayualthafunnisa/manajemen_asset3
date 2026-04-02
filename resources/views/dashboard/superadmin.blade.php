@extends('layouts.app')

@section('title', 'Super Admin Dashboard')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-neutral-900">Super Admin Dashboard</h1>
        <p class="mt-1 text-neutral-500 text-sm">Selamat datang kembali! Berikut ringkasan sistem secara keseluruhan.</p>
    </div>
    <div class="mt-4 sm:mt-0 flex items-center space-x-2 text-sm text-neutral-500">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <span>{{ now()->translatedFormat('l, d F Y') }}</span>
    </div>
</div>
@endsection

@section('content')

{{-- STAT CARDS --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">

    {{-- Total Instansi --}}
    <div class="bg-white rounded-2xl shadow-card p-5 flex items-center space-x-4 border border-neutral-100 hover:shadow-card-hover transition-shadow duration-200">
        <div class="flex-shrink-0 w-14 h-14 rounded-xl bg-gradient-primary flex items-center justify-center shadow-dp">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">Total Instansi</p>
            <p class="text-3xl font-bold text-neutral-900">{{ $totalInstansi ?? 0 }}</p>
            <p class="text-xs text-accent-500 mt-0.5 font-medium">+{{ $instansiBaru ?? 0 }} bulan ini</p>
        </div>
    </div>

    {{-- Total User --}}
    <div class="bg-white rounded-2xl shadow-card p-5 flex items-center space-x-4 border border-neutral-100 hover:shadow-card-hover transition-shadow duration-200">
        <div class="flex-shrink-0 w-14 h-14 rounded-xl bg-gradient-secondary flex items-center justify-center shadow-dp">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">Total User</p>
            <p class="text-3xl font-bold text-neutral-900">{{ $totalUser ?? 0 }}</p>
            <p class="text-xs text-info-DEFAULT mt-0.5 font-medium">{{ $userAktif ?? 0 }} aktif</p>
        </div>
    </div>

    {{-- Total Asset --}}
    <div class="bg-white rounded-2xl shadow-card p-5 flex items-center space-x-4 border border-neutral-100 hover:shadow-card-hover transition-shadow duration-200">
        <div class="flex-shrink-0 w-14 h-14 rounded-xl bg-gradient-accent flex items-center justify-center shadow-dp">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">Total Asset</p>
            <p class="text-3xl font-bold text-neutral-900">{{ $totalAsset ?? 0 }}</p>
            <p class="text-xs text-accent-500 mt-0.5 font-medium">Semua instansi</p>
        </div>
    </div>

    {{-- Penghapusan Pending --}}
    <div class="bg-white rounded-2xl shadow-card p-5 flex items-center space-x-4 border border-neutral-100 hover:shadow-card-hover transition-shadow duration-200">
        <div class="flex-shrink-0 w-14 h-14 rounded-xl bg-warning-DEFAULT flex items-center justify-center shadow-dp">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">Pending Approval</p>
            <p class="text-3xl font-bold text-neutral-900">{{ $pendingApproval ?? 0 }}</p>
            <p class="text-xs text-warning-DEFAULT mt-0.5 font-medium">Butuh tindakan</p>
        </div>
    </div>

</div>

{{-- ROW 2: Tabel Instansi + Aktivitas Terbaru --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    {{-- Daftar Instansi --}}
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-card border border-neutral-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-100 flex items-center justify-between">
            <h2 class="text-base font-bold text-neutral-900">Daftar Instansi</h2>
            <a href="{{ route('instansi.index') }}" class="text-xs text-primary-600 hover:text-primary-800 font-semibold">Lihat semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-100">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Instansi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Admin</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Asset</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-100">
                    @forelse ($instansis ?? [] as $instansi)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 rounded-lg bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-sm flex-shrink-0">
                                    {{ strtoupper(substr($instansi->NamaInstansi, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-neutral-900">{{ $instansi->NamaInstansi }}</div>
                                    <div class="text-xs text-neutral-500">{{ $instansi->KodeInstansi }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-600">{{ $instansi->user_count ?? 0 }} user</td>
                        <td class="px-6 py-4 text-sm text-neutral-600">{{ $instansi->assets_count ?? 0 }} asset</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-accent-100 text-accent-700">Aktif</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-sm text-neutral-400">Belum ada instansi terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Aktivitas Terbaru --}}
    <div class="bg-white rounded-2xl shadow-card border border-neutral-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-100">
            <h2 class="text-base font-bold text-neutral-900">Aktivitas Terbaru</h2>
        </div>
        <div class="divide-y divide-neutral-100">
            @forelse ($aktivitas ?? [] as $item)
            <div class="px-6 py-4 flex items-start space-x-3">
                <div class="w-2 h-2 mt-2 rounded-full bg-primary-500 flex-shrink-0"></div>
                <div>
                    <p class="text-sm text-neutral-800">{{ $item->deskripsi }}</p>
                    <p class="text-xs text-neutral-400 mt-0.5">{{ $item->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <div class="px-6 py-10 text-center text-sm text-neutral-400">Belum ada aktivitas.</div>
            @endforelse
        </div>
    </div>

</div>

@endsection