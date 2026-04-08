@extends('layouts.app')

@section('title', 'Detail Approval')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-neutral-900">Detail Approval</h1>
        <p class="mt-1 text-neutral-500 text-sm">Detail informasi pendaftaran admin sekolah</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('admin.approvals') }}" class="inline-flex items-center px-4 py-2 bg-neutral-100 text-neutral-700 rounded-lg hover:bg-neutral-200 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Informasi User -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Card Informasi Pendaftaran -->
        <div class="bg-white rounded-2xl shadow-card border border-neutral-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-neutral-100 bg-gradient-to-r from-primary-50 to-white">
                <h2 class="text-lg font-bold text-neutral-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Informasi Admin Sekolah
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-semibold text-neutral-500 uppercase">Nama Lengkap</label>
                        <p class="text-base font-semibold text-neutral-900 mt-1">{{ $user->name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-neutral-500 uppercase">Email</label>
                        <p class="text-base font-semibold text-neutral-900 mt-1">{{ $user->email ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-neutral-500 uppercase">Nomor HP</label>
                        <p class="text-base font-semibold text-neutral-900 mt-1">{{ $user->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-neutral-500 uppercase">Tanggal Daftar</label>
                        <p class="text-base font-semibold text-neutral-900 mt-1">{{ $user->created_at->format('d F Y H:i') ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-neutral-500 uppercase">Status Akun</label>
                        <div class="mt-1">
                            @if($user->status == 'pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Menunggu Approval
                                </span>
                            @elseif($user->status == 'active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Ditolak
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Informasi Lisensi -->
        <div class="bg-white rounded-2xl shadow-card border border-neutral-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-neutral-100 bg-gradient-to-r from-primary-50 to-white">
                <h2 class="text-lg font-bold text-neutral-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"></path>
                    </svg>
                    Informasi Lisensi
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-semibold text-neutral-500 uppercase">Tipe Lisensi</label>
                        <p class="text-base font-semibold text-neutral-900 mt-1">
                            @if($license->license_type == 'premium')
                                <span class="text-purple-600">Premium (1 Tahun)</span>
                            @else
                                <span class="text-blue-600">Basic (1 Bulan)</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-neutral-500 uppercase">Harga</label>
                        <p class="text-base font-semibold text-neutral-900 mt-1">
                            Rp {{ number_format($totalPayment, 0, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-neutral-500 uppercase">Kode Lisensi</label>
                        <p class="text-sm font-mono bg-neutral-100 p-2 rounded mt-1">{{ $license->kode_lisensi ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-neutral-500 uppercase">Status Pembayaran</label>
                        <div class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Lunas
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar: Action Buttons -->
    <div class="space-y-6">
        <!-- Card Action -->
        <div class="bg-white rounded-2xl shadow-card border border-neutral-100 overflow-hidden sticky top-6">
            <div class="px-6 py-4 border-b border-neutral-100 bg-gradient-to-r from-primary-50 to-white">
                <h2 class="text-lg font-bold text-neutral-900">Tindakan</h2>
            </div>
            <div class="p-6 space-y-4">
                @if($license->approval_status == 'pending')
                    <button onclick="confirmApprove()" 
                            class="w-full bg-green-600 text-white py-3 rounded-xl font-semibold hover:bg-green-700 transition flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Setujui Pendaftaran
                    </button>
                    
                    <button onclick="showRejectModal()" 
                            class="w-full bg-red-600 text-white py-3 rounded-xl font-semibold hover:bg-red-700 transition flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Tolak Pendaftaran
                    </button>
                @elseif($license->approval_status == 'approved')
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                        <svg class="w-12 h-12 text-green-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-green-800 font-semibold">Sudah Disetujui</p>
                        <p class="text-sm text-green-600 mt-1">{{ $license->approved_at->format('d F Y H:i') }}</p>
                    </div>
                @else
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                        <svg class="w-12 h-12 text-red-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-red-800 font-semibold">Ditolak</p>
                        @if($license->rejection_reason)
                            <p class="text-sm text-red-600 mt-1">Alasan: {{ $license->rejection_reason }}</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Card Informasi Tambahan -->
        <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100">
            <div class="flex items-start space-x-3">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-blue-900">Informasi</p>
                    <p class="text-xs text-blue-700 mt-1">
                        Setelah menyetujui pendaftaran, sistem akan otomatis mengirimkan email aktivasi ke alamat email admin sekolah.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div id="rejectModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl transform transition-all">
        <div class="px-6 py-4 border-b border-neutral-100 flex justify-between items-center">
            <h3 class="font-bold text-neutral-900 text-lg">Tolak Pendaftaran</h3>
            <button onclick="closeRejectModal()" class="text-neutral-400 hover:text-neutral-600 text-2xl leading-5">&times;</button>
        </div>
        <div class="p-6">
            <p class="text-sm text-neutral-600 mb-4">Berikan alasan penolakan untuk admin sekolah:</p>
            <textarea id="rejectReason" rows="4" class="w-full border border-neutral-200 rounded-xl px-4 py-2.5 text-sm focus:ring-primary-500 focus:border-primary-500" placeholder="Contoh: Data tidak lengkap, pembayaran tidak valid, dll..."></textarea>
            <div class="mt-6 flex space-x-3">
                <button onclick="submitReject()" class="flex-1 bg-red-600 text-white py-2.5 rounded-xl font-semibold hover:bg-red-700 transition">
                    Tolak
                </button>
                <button onclick="closeRejectModal()" class="flex-1 bg-neutral-100 text-neutral-700 py-2.5 rounded-xl font-semibold hover:bg-neutral-200 transition">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentLicenseId = {{ $license->id }};
    
    function confirmApprove() {
        if (confirm('Apakah Anda yakin ingin menyetujui pendaftaran ini?\n\nUser akan menerima email aktivasi.')) {
            approveUser();
        }
    }
    
    function approveUser() {
        const button = event.target;
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<div class="loading-spinner"></div> Memproses...';
        
        fetch(`/admin/approvals/${currentLicenseId}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => {
                    window.location.href = '{{ route("admin.approvals") }}';
                }, 2000);
            } else {
                showNotification(data.message, 'error');
                button.disabled = false;
                button.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan, silakan coba lagi.', 'error');
            button.disabled = false;
            button.innerHTML = originalText;
        });
    }
    
    function showRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
        document.getElementById('rejectModal').classList.add('flex');
    }
    
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejectModal').classList.remove('flex');
        document.getElementById('rejectReason').value = '';
    }
    
    function submitReject() {
        const reason = document.getElementById('rejectReason').value.trim();
        
        if (!reason) {
            showNotification('Harap isi alasan penolakan.', 'error');
            return;
        }
        
        const button = event.target;
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<div class="loading-spinner"></div> Memproses...';
        
        fetch(`/admin/approvals/${currentLicenseId}/reject`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => {
                    window.location.href = '{{ route("admin.approvals") }}';
                }, 2000);
            } else {
                showNotification(data.message, 'error');
                button.disabled = false;
                button.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan, silakan coba lagi.', 'error');
            button.disabled = false;
            button.innerHTML = originalText;
        });
    }
    
    function showNotification(message, type) {
        // Implement toast notification
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white ${type === 'success' ? 'bg-green-600' : 'bg-red-600'} transform transition-all duration-300`;
        toast.innerHTML = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
</script>

<style>
    .loading-spinner {
        width: 16px;
        height: 16px;
        border: 2px solid white;
        border-top: 2px solid transparent;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        display: inline-block;
        margin-right: 8px;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endsection