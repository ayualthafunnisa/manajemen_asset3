@extends('layouts.app')

@section('title', 'Riwayat Notifikasi')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
        <h1 class="text-2xl font-bold text-neutral-900 tracking-tight">Riwayat Notifikasi</h1>
        <p class="mt-0.5 text-neutral-400 text-sm">Notifikasi yang sudah dibaca</p>
    </div>
    <div class="flex items-center space-x-2">
        <div class="flex items-center space-x-1 bg-neutral-100 rounded-xl p-1">
            <a href="{{ route('notifications.index') }}" 
               class="px-4 py-2 text-sm font-semibold rounded-lg text-neutral-600 hover:text-primary-600 transition-all">
                Belum Dibaca
            </a>
            <a href="{{ route('notifications.history') }}" 
               class="px-4 py-2 text-sm font-semibold rounded-lg bg-white text-primary-600 shadow-sm transition-all">
                Riwayat
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="bg-white rounded-2xl shadow-soft border border-neutral-100 overflow-hidden">
    @forelse ($notifications as $notif)
    @php
        $detailLink = '#';
        if ($notif->type == 'registration_pending') {
            $data = is_string($notif->data) ? json_decode($notif->data, true) : $notif->data;
            $detailLink = route('admin.approvals.show', $data['license_id'] ?? $data['user_id'] ?? 0);
        }

        $typeConfig = [
            'registration_pending' => ['color' => 'amber', 'label' => 'Pendaftaran'],
            'approval' => ['color' => 'green', 'label' => 'Persetujuan'],
            'rejection' => ['color' => 'red', 'label' => 'Penolakan'],
            'info' => ['color' => 'blue', 'label' => 'Info'],
        ];
        $cfg = $typeConfig[$notif->type] ?? ['color' => 'neutral', 'label' => 'Sistem'];
    @endphp

    <div class="group relative flex items-start px-5 py-4 border-b border-neutral-50 last:border-0 hover:bg-neutral-50/60 transition-all duration-150">
        {{-- Icon --}}
        <div class="flex-shrink-0 mr-4">
            <div class="w-11 h-11 rounded-xl bg-neutral-100 flex items-center justify-center text-xl">
                {{ $notif->icon ?? ($notif->type == 'registration_pending' ? '📝' : '🔔') }}
            </div>
        </div>

        {{-- Content --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-3">
                <div class="flex-1">
                    <div class="flex items-center flex-wrap gap-2 mb-0.5">
                        <p class="text-sm font-semibold text-neutral-600">
                            {{ $notif->title }}
                        </p>
                        <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-md
                            {{ $cfg['color'] === 'amber' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $cfg['color'] === 'green' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $cfg['color'] === 'red' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $cfg['color'] === 'blue' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $cfg['color'] === 'neutral' ? 'bg-neutral-100 text-neutral-600' : '' }}">
                            {{ $cfg['label'] }}
                        </span>
                    </div>
                    <p class="text-sm text-neutral-500 leading-relaxed">{{ $notif->message }}</p>
                    <div class="flex items-center space-x-1.5 mt-2">
                        <svg class="w-3.5 h-3.5 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-xs text-neutral-400">{{ $notif->created_at->diffForHumans() }}</span>
                        <span class="text-neutral-200">·</span>
                        <span class="text-xs text-neutral-400">{{ $notif->created_at->format('d M Y, H:i') }}</span>
                        @if($notif->read_at)
                        <span class="text-neutral-200">·</span>
                        <span class="text-xs text-neutral-400">Dibaca: {{ $notif->read_at->diffForHumans() }}</span>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex-shrink-0">
                    <a href="{{ $detailLink }}"
                       class="inline-flex items-center space-x-1 text-xs text-primary-600 hover:text-primary-800 font-semibold bg-primary-50 hover:bg-primary-100 px-3 py-1.5 rounded-lg transition-all duration-150">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span>Detail</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @empty
    <div class="py-24 text-center">
        <div class="w-20 h-20 bg-neutral-100 rounded-3xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <p class="text-neutral-600 font-semibold text-lg">Tidak ada riwayat notifikasi</p>
        <p class="text-neutral-400 text-sm mt-1">Notifikasi yang sudah dibaca akan muncul di sini</p>
    </div>
    @endforelse

    @if($notifications->hasPages())
    <div class="px-5 py-4 border-t border-neutral-100 bg-neutral-50/60">
        {{ $notifications->links() }}
    </div>
    @endif
</div>
@endsection