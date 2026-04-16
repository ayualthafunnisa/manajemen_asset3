@extends('layouts.app')

@section('title', 'Data Penghapusan Aset')

@if(!in_array(auth()->user()->role, ['super_admin']))
@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-neutral-900">Data Penghapusan Aset</h1>
        <p class="mt-1 text-neutral-600">Kelola pengajuan dan persetujuan penghapusan aset</p>
    </div>

    {{-- Tombol ajukan hanya untuk petugas --}}
    @if(auth()->user()->role === 'petugas')
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('penghapusan.create') }}"
           class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Ajukan Penghapusan Baru
        </a>
    </div>
    @endif
</div>
@endsection
@endif

@section('content')
<div class="space-y-6">

    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg flex items-start">
        <svg class="h-5 w-5 text-green-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <p class="text-sm text-green-700">{{ session('success') }}</p>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg flex items-start">
        <svg class="h-5 w-5 text-red-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <p class="text-sm text-red-700">{{ session('error') }}</p>
    </div>
    @endif

    <div class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-200">

        {{-- Filter --}}
        <div class="p-6 border-b border-neutral-200 bg-gray-50">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="relative flex-1 max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <input type="text" id="search" placeholder="Cari nomor surat, aset, atau pengaju..."
                           class="pl-10 pr-4 py-2 w-full border border-neutral-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    {{-- Filter Instansi untuk Super Admin --}}
                    @if(auth()->user()->role === 'super_admin' && isset($instansis))
                    <select id="filter_instansi" class="border border-neutral-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500">
                        <option value="">Semua Instansi</option>
                        @foreach($instansis as $instansi)
                            <option value="{{ $instansi->InstansiID }}" {{ request('instansi') == $instansi->InstansiID ? 'selected' : '' }}>
                                {{ $instansi->NamaSekolah }}
                            </option>
                        @endforeach
                    </select>
                    @endif
                    <select id="statusFilter" class="border border-neutral-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500">
                        <option value="">Semua Status</option>
                        <option value="diajukan">Diajukan</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                    <select id="alasanFilter" class="border border-neutral-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500">
                        <option value="">Semua Alasan</option>
                        <option value="kerusakan permanen">Kerusakan Permanen</option>
                        <option value="teknologi tertinggal">Teknologi Tertinggal</option>
                        <option value="tidak layak pakai">Tidak Layak Pakai</option>
                        <option value="kehilangan">Kehilangan</option>
                        <option value="penggantian">Penggantian</option>
                        <option value="restrukturisasi">Restrukturisasi</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-6 bg-neutral-50 border-b border-neutral-200">
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <p class="text-sm text-neutral-600">Total Pengajuan</p>
                <p class="text-2xl font-bold text-neutral-900">{{ $summary['total'] }}</p>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-yellow-400">
                <p class="text-sm text-neutral-600">Menunggu Approval</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $summary['diajukan'] }}</p>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-green-400">
                <p class="text-sm text-neutral-600">Disetujui</p>
                <p class="text-2xl font-bold text-green-600">{{ $summary['disetujui'] }}</p>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-red-400">
                <p class="text-sm text-neutral-600">Ditolak</p>
                <p class="text-2xl font-bold text-red-600">{{ $summary['ditolak'] }}</p>
            </div>
        </div>

        {{-- Info banner untuk admin: ada pengajuan menunggu --}}
        @if(auth()->user()->role === 'admin_sekolah' && $summary['diajukan'] > 0)
        <div class="mx-6 mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm text-yellow-800">
                Terdapat <strong>{{ $summary['diajukan'] }}</strong> pengajuan penghapusan yang menunggu persetujuan Anda.
            </p>
        </div>
        @endif

        {{-- Table --}}
        <div class="overflow-x-auto mt-4">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">No Surat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Aset</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Nilai Buku</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Alasan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Pengaju</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                        @if(!in_array(auth()->user()->role, ['super_admin']))
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200" id="tableBody">
                    @forelse ($penghapusans as $index => $penghapusan)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                            {{ $penghapusans->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-mono font-medium text-neutral-900">{{ $penghapusan->no_surat_penghapusan }}</div>
                            <div class="text-neutral-500 text-xs">{{ \Carbon\Carbon::parse($penghapusan->tanggal_pengajuan)->format('d/m/Y') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-neutral-900">{{ $penghapusan->asset->nama_asset ?? '-' }}</div>
                            <div class="text-neutral-500 text-xs">{{ $penghapusan->asset->kode_asset ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900">
                            Rp {{ number_format($penghapusan->nilai_buku, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $alasanColor = [
                                    'kerusakan_permanen'  => 'bg-red-100 text-red-800',
                                    'teknologi_tertinggal'=> 'bg-yellow-100 text-yellow-800',
                                    'tidak_layak_pakai'   => 'bg-orange-100 text-orange-800',
                                    'kehilangan'          => 'bg-pink-100 text-pink-800',
                                    'penggantian'         => 'bg-blue-100 text-blue-800',
                                    'restrukturisasi'     => 'bg-purple-100 text-purple-800',
                                ];
                            @endphp
                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full inline-flex items-center {{ $alasanColor[$penghapusan->alasan_penghapusan] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $penghapusan->alasan_penghapusan)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                            {{ $penghapusan->pengaju->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColor = [
                                    'diajukan'  => 'bg-yellow-100 text-yellow-800',
                                    'disetujui' => 'bg-green-100 text-green-800',
                                    'ditolak'   => 'bg-red-100 text-red-800',
                                ];
                                $statusLabel = [
                                    'diajukan'  => 'Menunggu Approval',
                                    'disetujui' => 'Disetujui',
                                    'ditolak'   => 'Ditolak',
                                ];
                            @endphp
                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full inline-flex items-center {{ $statusColor[$penghapusan->status_penghapusan] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabel[$penghapusan->status_penghapusan] ?? ucfirst($penghapusan->status_penghapusan) }}
                            </span>
                        </td>
                        @if(!in_array(auth()->user()->role, ['super_admin']))
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">

                                {{-- Approve & Reject: hanya admin_sekolah, status diajukan --}}
                                @if(auth()->user()->role === 'admin_sekolah' && $penghapusan->status_penghapusan === 'diajukan')
                                <form action="{{ route('penghapusan.approve', $penghapusan->penghapusanID) }}"
                                      method="POST" class="inline"
                                      onsubmit="return confirm('Setujui pengajuan penghapusan ini? Status aset akan berubah menjadi DIHAPUS.')">
                                    @csrf
                                    <button type="submit" title="Setujui"
                                            class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200 transition duration-150 text-xs font-medium">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Setujui
                                    </button>
                                </form>
                                <button type="button" title="Tolak"
                                        onclick="showRejectModal({{ $penghapusan->penghapusanID }})"
                                        class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition duration-150 text-xs font-medium">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Tolak
                                </button>
                                @endif

                                {{-- Detail --}}
                                <a href="{{ route('penghapusan.show', $penghapusan->penghapusanID) }}"
                                   title="Detail" class="text-purple-600 hover:text-purple-900 transition duration-150">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                {{--
                                    Hapus data pengajuan:
                                    - Hanya petugas yang mengajukan sendiri
                                    - Hanya jika status masih 'diajukan' atau 'ditolak'
                                    - Tidak berlaku jika sudah 'disetujui' (asset sudah berubah jadi dihapus)
                                --}}
                                @if(
                                    auth()->user()->role === 'petugas'
                                    && $penghapusan->diajukan_oleh === auth()->id()
                                    && in_array($penghapusan->status_penghapusan, ['diajukan', 'ditolak'])
                                )
                                <form action="{{ route('penghapusan.destroy', $penghapusan->penghapusanID) }}"
                                      method="POST" class="inline"
                                      onsubmit="return confirm('Hapus data pengajuan penghapusan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus" class="text-red-400 hover:text-red-700 transition duration-150">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif

                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-neutral-900">Belum ada data penghapusan</h3>
                            <p class="mt-1 text-sm text-neutral-500">
                                @if(auth()->user()->role === 'petugas')
                                    Mulai dengan mengajukan penghapusan aset baru.
                                @else
                                    Belum ada pengajuan penghapusan dari petugas.
                                @endif
                            </p>
                            @if(auth()->user()->role === 'petugas')
                            <div class="mt-6">
                                <a href="{{ route('penghapusan.create') }}"
                                   class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Ajukan Penghapusan Baru
                                </a>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($penghapusans->hasPages())
        <div class="px-6 py-4 border-t border-neutral-200 bg-gray-50">
            {{ $penghapusans->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Modal Reject --}}
<div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="hideRejectModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="rejectForm" method="POST">
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
                            <p class="text-sm text-gray-500 mt-1 mb-3">Berikan alasan penolakan (minimal 10 karakter). Petugas dapat mengajukan ulang setelah perbaikan.</p>
                            <textarea name="alasan_penolakan" id="alasan_penolakan" rows="4" required minlength="10"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                      placeholder="Masukkan alasan penolakan..."></textarea>
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
document.addEventListener('DOMContentLoaded', function () {
    const searchInput   = document.getElementById('search');
    const statusFilter  = document.getElementById('statusFilter');
    const alasanFilter  = document.getElementById('alasanFilter');

    function applyFilters() {
        const searchTerm   = searchInput.value.toLowerCase();
        const statusValue  = statusFilter.value.toLowerCase();
        const alasanValue  = alasanFilter.value.toLowerCase();

        document.querySelectorAll('#tableBody tr').forEach(row => {
            if (row.querySelector('td[colspan]')) return;
            const text        = row.textContent.toLowerCase();
            const alasanCell  = row.querySelector('td:nth-child(5) span');
            const statusCell  = row.querySelector('td:nth-child(7) span');

            const matchSearch = !searchTerm  || text.includes(searchTerm);
            const matchStatus = !statusValue || (statusCell && statusCell.textContent.toLowerCase().includes(statusValue));
            const matchAlasan = !alasanValue || (alasanCell && alasanCell.textContent.toLowerCase().includes(alasanValue));

            row.style.display = (matchSearch && matchStatus && matchAlasan) ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', applyFilters);
    statusFilter.addEventListener('change', applyFilters);
    alasanFilter.addEventListener('change', applyFilters);

    const filterInstansi = document.getElementById('filter_instansi');
    if (filterInstansi) {
        filterInstansi.addEventListener('change', function() {
            const url = new URL(window.location.href);
            if (this.value) {
                url.searchParams.set('instansi', this.value);
            } else {
                url.searchParams.delete('instansi');
            }
            window.location.href = url.toString();
        });
    }
});

function showRejectModal(id) {
    const baseUrl = "{{ url('penghapusan') }}";
    document.getElementById('rejectForm').action = `${baseUrl}/${id}/reject`;
    document.getElementById('alasan_penolakan').value = '';
    document.getElementById('rejectModal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
@endpush