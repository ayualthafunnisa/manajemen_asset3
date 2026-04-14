@extends('layouts.app')

@section('title', 'Detail Penghapusan Aset')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Detail Penghapusan Aset</h1>
        <p class="mt-1 text-gray-600">Informasi lengkap pengajuan penghapusan aset</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('penghapusan.index') }}"
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
<div class="max-w-6xl mx-auto">

    @if(session('success'))
    <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg flex items-start">
        <svg class="h-5 w-5 text-green-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <p class="text-sm text-green-700">{{ session('success') }}</p>
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
        <p class="text-sm text-red-700">{{ session('error') }}</p>
    </div>
    @endif

    {{-- Status Banner --}}
    @php
        $statusColors = [
            'diajukan'  => 'yellow',
            'disetujui' => 'green',
            'ditolak'   => 'red',
        ];
        $statusLabels = [
            'diajukan'  => 'Menunggu Persetujuan Admin Sekolah',
            'disetujui' => 'Disetujui — Aset Telah Dihapus',
            'ditolak'   => 'Ditolak',
        ];
        $statusIcons = [
            'diajukan'  => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            'disetujui' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            'ditolak'   => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
        ];
        $color = $statusColors[$penghapusan->status_penghapusan] ?? 'gray';
        $icon  = $statusIcons[$penghapusan->status_penghapusan] ?? 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
    @endphp
    <div class="mb-6 bg-{{ $color }}-50 border-l-4 border-{{ $color }}-400 p-4 rounded-lg flex items-center">
        <svg class="h-5 w-5 text-{{ $color }}-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
        </svg>
        <p class="text-sm text-{{ $color }}-700">
            Status: <span class="font-bold">{{ $statusLabels[$penghapusan->status_penghapusan] ?? ucfirst($penghapusan->status_penghapusan) }}</span>
        </p>
    </div>

    {{-- Info khusus untuk petugas jika ditolak: bisa ajukan ulang --}}
    @if(auth()->user()->role === 'petugas' && $penghapusan->status_penghapusan === 'ditolak')
    <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        <div>
            <p class="text-sm font-medium text-blue-800">Pengajuan Ditolak</p>
            <p class="text-sm text-blue-700 mt-1">Anda dapat memperbaiki pengajuan dan mengajukan ulang. Silakan perhatikan alasan penolakan dari admin.</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom kiri --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-red-50 px-6 py-4 border-b border-red-100">
                    <h3 class="text-lg font-semibold text-red-900">Informasi Penghapusan</h3>
                </div>
                <div class="p-6">

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Nilai Buku</p>
                            <p class="text-xl font-bold text-gray-900">Rp {{ number_format($penghapusan->nilai_buku, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Harga Jual</p>
                            <p class="text-xl font-bold text-gray-900">
                                {{ $penghapusan->harga_jual ? 'Rp '.number_format($penghapusan->harga_jual, 0, ',', '.') : '-' }}
                            </p>
                        </div>
                    </div>

                    @if($penghapusan->kerugian_keuntungan !== null)
                    @php $isRugi = $penghapusan->kerugian_keuntungan < 0; @endphp
                    <div class="bg-{{ $isRugi ? 'red' : 'green' }}-50 p-4 rounded-lg mb-6 flex justify-between items-center">
                        <span class="font-medium text-{{ $isRugi ? 'red' : 'green' }}-700">
                            {{ $isRugi ? 'Kerugian' : 'Keuntungan' }}
                        </span>
                        <span class="text-xl font-bold text-{{ $isRugi ? 'red' : 'green' }}-700">
                            Rp {{ number_format(abs($penghapusan->kerugian_keuntungan), 0, ',', '.') }}
                        </span>
                    </div>
                    @endif

                    <table class="min-w-full divide-y divide-gray-200">
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500 w-1/3">No. Surat</td>
                                <td class="py-3 text-sm text-gray-900 font-medium font-mono">{{ $penghapusan->no_surat_penghapusan }}</td>
                            </tr>
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Tanggal Pengajuan</td>
                                <td class="py-3 text-sm text-gray-900">{{ \Carbon\Carbon::parse($penghapusan->tanggal_pengajuan)->format('d/m/Y') }}</td>
                            </tr>
                            @if($penghapusan->tanggal_penghapusan)
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Tanggal Penghapusan</td>
                                <td class="py-3 text-sm text-gray-900">{{ \Carbon\Carbon::parse($penghapusan->tanggal_penghapusan)->format('d/m/Y') }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Alasan Penghapusan</td>
                                <td class="py-3 text-sm text-gray-900">
                                    @php
                                        $alasanLabel = [
                                            'kerusakan_permanen'   => 'Kerusakan Permanen',
                                            'teknologi_tertinggal' => 'Teknologi Tertinggal',
                                            'tidak_layak_pakai'    => 'Tidak Layak Pakai',
                                            'kehilangan'           => 'Kehilangan',
                                            'penggantian'          => 'Penggantian',
                                            'restrukturisasi'      => 'Restrukturisasi',
                                        ];
                                    @endphp
                                    {{ $alasanLabel[$penghapusan->alasan_penghapusan] ?? ucfirst(str_replace('_', ' ', $penghapusan->alasan_penghapusan)) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Deskripsi</td>
                                <td class="py-3 text-sm text-gray-900 whitespace-pre-line">{{ $penghapusan->deskripsi_penghapusan }}</td>
                            </tr>
                            @if($penghapusan->keterangan)
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Keterangan</td>
                                <td class="py-3 text-sm text-gray-900">{{ $penghapusan->keterangan }}</td>
                            </tr>
                            @endif
                            @if($penghapusan->alasan_penolakan)
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Alasan Penolakan</td>
                                <td class="py-3">
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                        <p class="text-sm text-red-700 font-medium">{{ $penghapusan->alasan_penolakan }}</p>
                                    </div>
                                </td>
                            </tr>
                            @endif
                            @if($penghapusan->dokumen_pendukung)
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500">Dokumen Pendukung</td>
                                <td class="py-3 text-sm">
                                    <a href="{{ asset('storage/'.$penghapusan->dokumen_pendukung) }}" target="_blank"
                                       class="text-purple-600 hover:text-purple-900 inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        Lihat Dokumen
                                    </a>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    {{-- Timeline / Riwayat Proses --}}
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <h4 class="font-medium text-gray-900 mb-4">Riwayat Proses</h4>
                        <div class="relative">
                            {{-- Garis vertikal --}}
                            <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>

                            <div class="space-y-4">
                                {{-- Step 1: Diajukan --}}
                                <div class="relative flex items-start pl-10">
                                    <div class="absolute left-0 w-8 h-8 rounded-full bg-purple-100 border-2 border-purple-400 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">Pengajuan Dikirim</p>
                                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($penghapusan->tanggal_pengajuan)->format('d/m/Y') }}</p>
                                        <p class="text-xs text-gray-500">Oleh: {{ $penghapusan->pengaju->name ?? '-' }}</p>
                                    </div>
                                </div>

                                {{-- Step 2: Menunggu / Disetujui / Ditolak --}}
                                @if($penghapusan->status_penghapusan === 'diajukan')
                                <div class="relative flex items-start pl-10">
                                    <div class="absolute left-0 w-8 h-8 rounded-full bg-yellow-100 border-2 border-yellow-400 flex items-center justify-center animate-pulse">
                                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-yellow-700">Menunggu Persetujuan Admin</p>
                                        <p class="text-xs text-gray-500">Pengajuan sedang direview oleh admin sekolah</p>
                                    </div>
                                </div>

                                @elseif($penghapusan->status_penghapusan === 'disetujui')
                                <div class="relative flex items-start pl-10">
                                    <div class="absolute left-0 w-8 h-8 rounded-full bg-green-100 border-2 border-green-500 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-green-700">Disetujui oleh Admin</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $penghapusan->tanggal_persetujuan ? \Carbon\Carbon::parse($penghapusan->tanggal_persetujuan)->format('d/m/Y H:i') : '-' }}
                                        </p>
                                        <p class="text-xs text-gray-500">Oleh: {{ $penghapusan->penyetuju->name ?? '-' }}</p>
                                    </div>
                                </div>
                                {{-- Step 3: Aset dihapus --}}
                                <div class="relative flex items-start pl-10">
                                    <div class="absolute left-0 w-8 h-8 rounded-full bg-red-100 border-2 border-red-500 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-red-700">Status Aset Diubah → Dihapus</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $penghapusan->tanggal_penghapusan ? \Carbon\Carbon::parse($penghapusan->tanggal_penghapusan)->format('d/m/Y') : '-' }}
                                        </p>
                                        <p class="text-xs text-gray-500">Aset telah dikeluarkan dari daftar aset aktif</p>
                                    </div>
                                </div>

                                @elseif($penghapusan->status_penghapusan === 'ditolak')
                                <div class="relative flex items-start pl-10">
                                    <div class="absolute left-0 w-8 h-8 rounded-full bg-red-100 border-2 border-red-500 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-red-700">Ditolak oleh Admin</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $penghapusan->tanggal_persetujuan ? \Carbon\Carbon::parse($penghapusan->tanggal_persetujuan)->format('d/m/Y H:i') : '-' }}
                                        </p>
                                        <p class="text-xs text-gray-500">Oleh: {{ $penghapusan->penyetuju->name ?? '-' }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom kanan --}}
        <div class="space-y-6">

            {{-- Info Aset --}}
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-blue-50 px-6 py-4 border-b border-blue-100">
                    <h3 class="text-lg font-semibold text-blue-900">Informasi Aset</h3>
                </div>
                <div class="p-6">
                    @if($penghapusan->asset)
                    <table class="min-w-full">
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="py-2 text-sm text-gray-500">Kode Aset</td>
                                <td class="py-2 text-sm font-medium text-gray-900 font-mono">{{ $penghapusan->asset->kode_asset }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-sm text-gray-500">Nama Aset</td>
                                <td class="py-2 text-sm font-medium text-gray-900">{{ $penghapusan->asset->nama_asset }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-sm text-gray-500">Nilai Perolehan</td>
                                <td class="py-2 text-sm text-gray-900">Rp {{ number_format($penghapusan->asset->nilai_perolehan, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-sm text-gray-500">Status Aset</td>
                                <td class="py-2 text-sm">
                                    @php
                                        $asetStatusColor = [
                                            'aktif'   => 'bg-green-100 text-green-800',
                                            'rusak'   => 'bg-yellow-100 text-yellow-800',
                                            'dihapus' => 'bg-red-100 text-red-800',
                                        ];
                                        $asetStatusLabel = [
                                            'aktif'   => 'Aktif',
                                            'rusak'   => 'Rusak',
                                            'dihapus' => 'Dihapus',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $asetStatusColor[$penghapusan->asset->status_asset] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $asetStatusLabel[$penghapusan->asset->status_asset] ?? ucfirst($penghapusan->asset->status_asset) }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    @else
                    <p class="text-sm text-gray-500">Data aset tidak tersedia.</p>
                    @endif
                </div>
            </div>

            {{-- Info Pengajuan --}}
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Pengajuan</h3>
                </div>
                <div class="p-6">
                    <table class="min-w-full">
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="py-2 text-sm text-gray-500">Diajukan Oleh</td>
                                <td class="py-2 text-sm text-gray-900">{{ $penghapusan->pengaju->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-sm text-gray-500">Email</td>
                                <td class="py-2 text-sm text-gray-900">{{ $penghapusan->pengaju->email ?? '-' }}</td>
                            </tr>
                            @if($penghapusan->penyetuju)
                            <tr>
                                <td class="py-2 text-sm text-gray-500">Diproses Oleh</td>
                                <td class="py-2 text-sm text-gray-900">{{ $penghapusan->penyetuju->name }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td class="py-2 text-sm text-gray-500">Dibuat</td>
                                <td class="py-2 text-sm text-gray-900">{{ $penghapusan->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tombol Aksi Admin: approve / reject --}}
            @if(auth()->user()->role === 'admin_sekolah' && $penghapusan->status_penghapusan === 'diajukan')
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-yellow-200">
                <div class="bg-yellow-50 px-6 py-4 border-b border-yellow-100">
                    <h4 class="font-semibold text-yellow-900">Tindakan Admin</h4>
                    <p class="text-xs text-yellow-700 mt-1">Setelah disetujui, status aset otomatis berubah menjadi <strong>dihapus</strong>.</p>
                </div>
                <div class="p-6 space-y-3">
                    <form action="{{ route('penghapusan.approve', $penghapusan->penghapusanID) }}" method="POST">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('Setujui pengajuan penghapusan ini?\n\nPerhatian: Status aset akan berubah menjadi DIHAPUS dan tidak dapat dibatalkan!')"
                                class="w-full px-4 py-3 bg-green-600 rounded-lg font-medium text-white hover:bg-green-700 transition duration-150 flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Setujui Penghapusan
                        </button>
                    </form>
                    <button type="button" onclick="showRejectModal()"
                            class="w-full px-4 py-3 bg-red-600 rounded-lg font-medium text-white hover:bg-red-700 transition duration-150 flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Tolak Pengajuan
                    </button>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

{{-- Modal Reject --}}
<div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="hideRejectModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('penghapusan.reject', $penghapusan->penghapusanID) }}" method="POST">
                @csrf
                <div class="bg-white px-6 pt-5 pb-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-red-100">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="ml-4 w-full">
                            <h3 class="text-lg font-medium text-gray-900">Tolak Pengajuan Penghapusan</h3>
                            <p class="text-sm text-gray-500 mt-1 mb-3">Berikan alasan penolakan (minimal 10 karakter). Petugas dapat memperbaiki dan mengajukan ulang.</p>
                            <textarea name="alasan_penolakan" rows="4" required minlength="10"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                                      placeholder="Masukkan alasan penolakan..."></textarea>
                            @error('alasan_penolakan')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3 flex flex-row-reverse gap-2">
                    <button type="submit"
                            class="inline-flex justify-center rounded-md border border-transparent px-4 py-2 bg-red-600 text-sm font-medium text-white hover:bg-red-700">
                        Tolak Pengajuan
                    </button>
                    <button type="button" onclick="hideRejectModal()"
                            class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}
function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
@endpush