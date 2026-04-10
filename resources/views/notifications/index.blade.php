@extends('layouts.app')

@section('title', 'Semua Notifikasi')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
        <h1 class="text-2xl font-bold text-neutral-900 tracking-tight">Notifikasi</h1>
        <p class="mt-0.5 text-neutral-400 text-sm">Kelola semua notifikasi sistem Anda</p>
    </div>
    <div class="flex items-center space-x-2">
        {{-- Tab Navigation --}}
        <div class="flex items-center space-x-1 bg-neutral-100 rounded-xl p-1 mr-2">
            <a href="{{ route('notifications.index') }}" 
               class="px-4 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('notifications.index') ? 'bg-white text-primary-600 shadow-sm' : 'text-neutral-600 hover:text-primary-600' }} transition-all">
                Belum Dibaca
            </a>
            <a href="{{ route('notifications.history') }}" 
               class="px-4 py-2 text-sm font-semibold rounded-lg {{ request()->routeIs('notifications.history') ? 'bg-white text-primary-600 shadow-sm' : 'text-neutral-600 hover:text-primary-600' }} transition-all">
                Riwayat
            </a>
        </div>
        
        <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="inline">
            @csrf
            <button type="submit"
                    class="inline-flex items-center space-x-1.5 text-sm text-primary-600 hover:text-primary-800 font-semibold bg-primary-50 hover:bg-primary-100 px-4 py-2 rounded-xl transition-all duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Tandai semua dibaca</span>
            </button>
        </form>
        <form action="{{ route('notifications.clear-all') }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center space-x-1.5 text-sm text-red-600 hover:text-red-800 font-semibold bg-red-50 hover:bg-red-100 px-4 py-2 rounded-xl transition-all duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                <span>Hapus semua</span>
            </button>
        </form>
    </div>
</div>
@endsection

@section('content')

{{-- Stats Bar --}}
<div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-5">
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-soft px-5 py-4 flex items-center space-x-3">
        <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center">
            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-neutral-900">{{ $notifications->total() }}</p>
            <p class="text-xs text-neutral-400 font-medium">Belum Dibaca</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-soft px-5 py-4 flex items-center space-x-3 col-span-2 sm:col-span-1">
        <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-neutral-900">{{ $unreadCount }}</p>
            <p class="text-xs text-neutral-400 font-medium">Total Notifikasi</p>
        </div>
    </div>
</div>

{{-- Main Notification List --}}
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

    <div class="group relative flex items-start px-5 py-4 border-b border-neutral-50 last:border-0 hover:bg-neutral-50/60 transition-all duration-150 {{ !$notif->is_read ? 'bg-primary-50/30' : '' }}">

        {{-- Unread indicator bar --}}
        @if(!$notif->is_read)
        <div class="absolute left-0 top-0 bottom-0 w-0.5 bg-primary-500 rounded-r-full"></div>
        @endif

        {{-- Icon --}}
        <div class="flex-shrink-0 mr-4">
            <div class="relative w-11 h-11 rounded-xl {{ !$notif->is_read ? 'bg-primary-100' : 'bg-neutral-100' }} flex items-center justify-center text-xl transition-colors group-hover:bg-primary-100">
                {{ $notif->icon ?? ($notif->type == 'registration_pending' ? '📝' : '🔔') }}
                @if(!$notif->is_read)
                <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-primary-500 rounded-full border-2 border-white"></span>
                @endif
            </div>
        </div>

        {{-- Content --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-3">
                <div class="flex-1">
                    <div class="flex items-center flex-wrap gap-2 mb-0.5">
                        <p class="text-sm font-semibold {{ !$notif->is_read ? 'text-primary-900' : 'text-neutral-800' }}">
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
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex-shrink-0 flex items-center space-x-1">

                    {{-- View Detail --}}
                    <a href="{{ $detailLink }}"
                       @if(!$notif->is_read)
                       onclick="markRead({{ $notif->id }})"
                       @endif
                       class="inline-flex items-center space-x-1 text-xs text-primary-600 hover:text-primary-800 font-semibold bg-primary-50 hover:bg-primary-100 px-3 py-1.5 rounded-lg transition-all duration-150">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span>Detail</span>
                    </a>

                    {{-- Approve/Reject (for pending) --}}
                    @if($notif->type == 'registration_pending')
                        @php
                            $data = is_string($notif->data) ? json_decode($notif->data, true) : $notif->data;
                            $licenseId = $data['license_id'] ?? $data['user_id'] ?? 0;
                        @endphp
                        <button onclick="quickApprove({{ $licenseId }})"
                                class="inline-flex items-center text-xs text-green-700 hover:text-green-800 font-semibold bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-lg transition-all duration-150"
                                title="Setujui">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </button>
                        <button onclick="quickReject({{ $licenseId }})"
                                class="inline-flex items-center text-xs text-red-600 hover:text-red-800 font-semibold bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-all duration-150"
                                title="Tolak">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    @endif

                    {{-- Mark Read --}}
                    @if(!$notif->is_read)
                    <form action="{{ route('notifications.mark-read', $notif->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                class="p-1.5 text-neutral-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-150"
                                title="Tandai dibaca">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @empty
    <div class="py-24 text-center">
        <div class="w-20 h-20 bg-neutral-100 rounded-3xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
        </div>
        <p class="text-neutral-600 font-semibold text-lg">Tidak ada notifikasi</p>
        <p class="text-neutral-400 text-sm mt-1">Semua notifikasi akan muncul di sini</p>
    </div>
    @endforelse

    @if($notifications->hasPages())
    <div class="px-5 py-4 border-t border-neutral-100 bg-neutral-50/60">
        {{ $notifications->links() }}
    </div>
    @endif
</div>

{{-- Quick Approve Modal --}}
<div id="quickApproveModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl transform transition-all">
        <div class="p-6 text-center">
            <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="font-bold text-neutral-900 text-lg mb-1">Setujui Pendaftaran?</h3>
            <p class="text-sm text-neutral-500 mb-6">User akan menerima email aktivasi setelah disetujui.</p>
            <div class="flex space-x-3">
                <button onclick="closeQuickApproveModal()"
                        class="flex-1 bg-neutral-100 text-neutral-700 py-2.5 rounded-xl font-semibold hover:bg-neutral-200 transition text-sm">
                    Batal
                </button>
                <button onclick="submitQuickApprove()"
                        class="flex-1 bg-green-600 text-white py-2.5 rounded-xl font-semibold hover:bg-green-700 transition text-sm">
                    Ya, Setujui
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Quick Reject Modal --}}
<div id="quickRejectModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl">
        <div class="px-6 py-5 border-b border-neutral-100 flex justify-between items-center">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <h3 class="font-bold text-neutral-900">Tolak Pendaftaran</h3>
            </div>
            <button onclick="closeQuickRejectModal()" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-neutral-100 text-neutral-400 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="p-6">
            <label class="block text-sm font-medium text-neutral-700 mb-2">Alasan penolakan</label>
            <textarea id="rejectReason" rows="3"
                      class="w-full border border-neutral-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none resize-none transition"
                      placeholder="Contoh: Dokumen tidak lengkap atau tidak valid..."></textarea>
            <div class="mt-4 flex space-x-3">
                <button onclick="closeQuickRejectModal()"
                        class="flex-1 bg-neutral-100 text-neutral-700 py-2.5 rounded-xl font-semibold hover:bg-neutral-200 transition text-sm">
                    Batal
                </button>
                <button onclick="submitQuickReject()"
                        class="flex-1 bg-red-600 text-white py-2.5 rounded-xl font-semibold hover:bg-red-700 transition text-sm">
                    Tolak
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentLicenseId = null;

    function markRead(id) {
        fetch(`/notifications/${id}/read`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
        }).then(() => {
            location.reload();
        }).catch(e => console.error(e));
    }

    function quickApprove(licenseId) {
        currentLicenseId = licenseId;
        const modal = document.getElementById('quickApproveModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeQuickApproveModal() {
        const modal = document.getElementById('quickApproveModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        currentLicenseId = null;
    }

    function submitQuickApprove() {
        if (!currentLicenseId) return;
        const btn = event.target;
        btn.disabled = true;
        btn.innerHTML = '<span class="notif-spinner"></span> Memproses...';

        fetch(`/admin/approvals/${currentLicenseId}/approve`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1800);
            } else {
                showToast(data.message, 'error');
                btn.disabled = false;
                btn.innerHTML = 'Ya, Setujui';
            }
        })
        .catch(() => {
            showToast('Terjadi kesalahan, silakan coba lagi.', 'error');
            btn.disabled = false;
            btn.innerHTML = 'Ya, Setujui';
        });
    }

    function quickReject(licenseId) {
        currentLicenseId = licenseId;
        const modal = document.getElementById('quickRejectModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeQuickRejectModal() {
        const modal = document.getElementById('quickRejectModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.getElementById('rejectReason').value = '';
        currentLicenseId = null;
    }

    function submitQuickReject() {
        const reason = document.getElementById('rejectReason').value.trim();
        if (!reason) { showToast('Harap isi alasan penolakan.', 'error'); return; }

        const btn = event.target;
        btn.disabled = true;
        btn.innerHTML = '<span class="notif-spinner"></span> Memproses...';

        fetch(`/admin/approvals/${currentLicenseId}/reject`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ reason })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1800);
            } else {
                showToast(data.message, 'error');
                btn.disabled = false;
                btn.innerHTML = 'Tolak';
            }
        })
        .catch(() => {
            showToast('Terjadi kesalahan, silakan coba lagi.', 'error');
            btn.disabled = false;
            btn.innerHTML = 'Tolak';
        });
    }

    function showToast(message, type) {
        const existing = document.querySelector('.notif-toast');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.className = `notif-toast fixed bottom-6 right-6 z-[999] flex items-center space-x-3 px-5 py-3.5 rounded-2xl shadow-2xl text-white text-sm font-medium transform transition-all duration-300 translate-y-4 opacity-0 ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
        toast.innerHTML = `
            <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                ${type === 'success'
                    ? '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>'
                    : '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>'
                }
            </div>
            <span>${message}</span>`;
        document.body.appendChild(toast);
        requestAnimationFrame(() => {
            toast.classList.remove('translate-y-4', 'opacity-0');
        });
        setTimeout(() => {
            toast.classList.add('translate-y-4', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>

<style>
.notif-spinner {
    display: inline-block;
    width: 12px;
    height: 12px;
    border: 2px solid rgba(255,255,255,0.4);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
    vertical-align: middle;
    margin-right: 6px;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>

@endsection