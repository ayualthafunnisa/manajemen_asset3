@extends('layouts.app')

@section('title', 'Data Kerusakan Aset - Jobie')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-neutral-900">Data Kerusakan Aset</h1>
        <p class="mt-1 text-neutral-600">Kelola laporan dan proses perbaikan aset</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('kerusakan.create') }}"
           class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Laporan Baru
        </a>
    </div>
</div>
@endsection

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
                    <input type="text" id="search" placeholder="Cari kode laporan, aset, atau lokasi..."
                           class="pl-10 pr-4 py-2 w-full border border-neutral-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <select id="statusFilter" class="border border-neutral-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500">
                        <option value="">Semua Status</option>
                        <option value="dilaporkan">Dilaporkan</option>
                        <option value="diproses">Diproses</option>
                        <option value="selesai">Selesai</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                    <select id="jenisFilter" class="border border-neutral-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500">
                        <option value="">Semua Jenis</option>
                        <option value="ringan">Ringan</option>
                        <option value="sedang">Sedang</option>
                        <option value="berat">Berat</option>
                        <option value="total">Total</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-6 bg-neutral-50 border-b border-neutral-200">
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <p class="text-sm text-neutral-600">Total Laporan</p>
                <p class="text-2xl font-bold text-neutral-900">{{ $summary['total'] }}</p>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-yellow-400">
                <p class="text-sm text-neutral-600">Dilaporkan</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $summary['dilaporkan'] }}</p>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-blue-400">
                <p class="text-sm text-neutral-600">Diproses</p>
                <p class="text-2xl font-bold text-blue-600">{{ $summary['diproses'] }}</p>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-green-400">
                <p class="text-sm text-neutral-600">Selesai</p>
                <p class="text-2xl font-bold text-green-600">{{ $summary['selesai'] }}</p>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">QR</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Kode Laporan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Aset</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Prioritas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200" id="tableBody">
                    @forelse ($kerusakans as $index => $kerusakan)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                            {{ $kerusakans->firstItem() + $index }}
                        </td>

                        {{-- Kolom QR Code — gaya asset index --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="relative inline-block qr-container">
                                <button type="button"
                                        class="qr-trigger focus:outline-none group"
                                        data-kode="{{ $kerusakan->kode_laporan }}">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-purple-50 transition overflow-hidden">
                                        @if($kerusakan->qrCode)
                                            <div class="qr-small-wrapper">
                                                {!! $kerusakan->qrCode !!}
                                            </div>
                                        @else
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                            </svg>
                                        @endif
                                    </div>
                                </button>

                                {{-- Popup QR besar --}}
                                <div class="qr-popup hidden fixed z-50">
                                    <div class="bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden relative">
                                        <button type="button" class="close-popup absolute top-3 right-3 text-gray-400 hover:text-gray-600 z-10 bg-gray-50 hover:bg-gray-100 rounded-full p-1.5 transition-all duration-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                        <div class="p-6 pt-8 flex flex-col items-center">
                                            <div class="qr-large-wrapper">
                                                @if($kerusakan->qrCode)
                                                    {!! QrCode::size(180)->format('svg')->generate($kerusakan->kode_laporan) !!}
                                                @endif
                                            </div>
                                            <div class="mt-4 text-center">
                                                <p class="text-xs text-gray-500 mb-1">Kode Laporan</p>
                                                <p class="text-sm font-mono font-semibold text-purple-700 bg-purple-50 px-3 py-1 rounded-full inline-block">
                                                    {{ $kerusakan->kode_laporan }}
                                                </p>
                                                <p class="text-xs text-gray-500 mt-2">{{ $kerusakan->asset->nama_asset ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="text-sm font-mono font-medium text-neutral-900">{{ $kerusakan->kode_laporan }}</div>
                            <div class="text-neutral-500 text-xs">{{ $kerusakan->tanggal_laporan->format('d/m/Y') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-neutral-900">{{ $kerusakan->asset->nama_asset ?? '-' }}</div>
                            <div class="text-neutral-500 text-xs">{{ $kerusakan->asset->kode_asset ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                            {{ $kerusakan->lokasi->NamaLokasi ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $jenisColor = [
                                    'ringan' => 'bg-green-100 text-green-800',
                                    'sedang' => 'bg-yellow-100 text-yellow-800',
                                    'berat'  => 'bg-orange-100 text-orange-800',
                                    'total'  => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full inline-flex items-center {{ $jenisColor[$kerusakan->jenis_kerusakan] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($kerusakan->jenis_kerusakan) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $prioritasColor = [
                                    'rendah' => 'bg-gray-100 text-gray-800',
                                    'sedang' => 'bg-blue-100 text-blue-800',
                                    'tinggi' => 'bg-orange-100 text-orange-800',
                                    'kritis' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full inline-flex items-center {{ $prioritasColor[$kerusakan->prioritas] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($kerusakan->prioritas) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColor = [
                                    'dilaporkan' => 'bg-yellow-100 text-yellow-800',
                                    'diproses'   => 'bg-blue-100 text-blue-800',
                                    'selesai'    => 'bg-green-100 text-green-800',
                                    'ditolak'    => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full inline-flex items-center {{ $statusColor[$kerusakan->status_perbaikan] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($kerusakan->status_perbaikan) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">

                                {{-- Proses: admin, dari dilaporkan --}}
                                @if(auth()->user()->role == 'admin_sekolah' && $kerusakan->status_perbaikan == 'dilaporkan')
                                <form action="{{ route('kerusakan.updateStatus', $kerusakan->kerusakanID) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status_perbaikan" value="diproses">
                                    <button type="submit" title="Proses Perbaikan"
                                            class="text-blue-600 hover:text-blue-900 transition duration-150">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif

                                {{-- Selesai: admin, dari diproses --}}
                                @if(auth()->user()->role == 'admin_sekolah' && $kerusakan->status_perbaikan == 'diproses')
                                <button type="button" title="Tandai Selesai"
                                        onclick="showSelesaiModal({{ $kerusakan->kerusakanID }})"
                                        class="text-green-600 hover:text-green-900 transition duration-150">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                                @endif

                                {{-- Detail --}}
                                <a href="{{ route('kerusakan.show', $kerusakan->kerusakanID) }}"
                                   title="Detail" class="text-purple-600 hover:text-purple-900 transition duration-150">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                {{-- Hapus: hanya status dilaporkan --}}
                                @if($kerusakan->status_perbaikan == 'dilaporkan')
                                <form action="{{ route('kerusakan.destroy', $kerusakan->kerusakanID) }}"
                                      method="POST" class="inline"
                                      onsubmit="return confirm('Hapus laporan kerusakan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus"
                                            class="text-red-400 hover:text-red-700 transition duration-150">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 012-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-neutral-900">Belum ada laporan kerusakan</h3>
                            <p class="mt-1 text-sm text-neutral-500">Mulai dengan membuat laporan kerusakan aset baru.</p>
                            <div class="mt-6">
                                <a href="{{ route('kerusakan.create') }}"
                                   class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Buat Laporan Baru
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($kerusakans->hasPages())
        <div class="px-6 py-4 border-t border-neutral-200 bg-gray-50">
            {{ $kerusakans->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Modal Tandai Selesai --}}
<div id="selesaiModal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="hideSelesaiModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
            <form id="selesaiForm" method="POST">
                @csrf
                <input type="hidden" name="status_perbaikan" value="selesai">
                <div class="bg-white px-6 pt-5 pb-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-green-100">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="ml-4 w-full">
                            <h3 class="text-lg font-medium text-gray-900">Tandai Perbaikan Selesai</h3>
                            <p class="text-sm text-gray-500 mt-1 mb-4">Isi informasi penyelesaian perbaikan (opsional).</p>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Biaya Aktual (Rp)</label>
                                    <input type="number" name="biaya_aktual" min="0" step="1000"
                                           placeholder="Masukkan biaya aktual perbaikan"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Perbaikan</label>
                                    <textarea name="catatan_perbaikan" rows="3"
                                              placeholder="Deskripsi perbaikan yang telah dilakukan..."
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3 flex flex-row-reverse gap-2">
                    <button type="submit"
                            class="inline-flex justify-center rounded-md border border-transparent px-4 py-2 bg-green-600 text-sm font-medium text-white hover:bg-green-700">
                        Tandai Selesai
                    </button>
                    <button type="button" onclick="hideSelesaiModal()"
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

    // ── QR Code Popup Handler (sama persis dengan asset index) ──
    const qrTriggers = document.querySelectorAll('.qr-trigger');
    let activePopup = null;

    qrTriggers.forEach(trigger => {
        trigger.addEventListener('click', function (e) {
            e.stopPropagation();
            const popup = this.closest('.qr-container').querySelector('.qr-popup');

            if (activePopup && activePopup !== popup) {
                activePopup.classList.add('hidden');
            }

            popup.classList.toggle('hidden');

            if (!popup.classList.contains('hidden')) {
                activePopup = popup;
                const rect         = this.getBoundingClientRect();
                const popupContent = popup.querySelector('.bg-white');
                const popupRect    = popupContent.getBoundingClientRect();

                let top  = rect.bottom + window.scrollY + 5;
                let left = rect.left + window.scrollX;

                if (left + popupRect.width > window.innerWidth) {
                    left = window.innerWidth - popupRect.width - 10;
                }
                if (top + popupRect.height > window.innerHeight + window.scrollY) {
                    top = rect.top + window.scrollY - popupRect.height - 5;
                }

                popup.style.top  = top  + 'px';
                popup.style.left = left + 'px';
            } else {
                activePopup = null;
            }
        });
    });

    document.querySelectorAll('.close-popup').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            this.closest('.qr-popup').classList.add('hidden');
            activePopup = null;
        });
    });

    document.addEventListener('click', function (e) {
        if (activePopup && !activePopup.contains(e.target) && !e.target.closest('.qr-trigger')) {
            activePopup.classList.add('hidden');
            activePopup = null;
        }
    });

    // ── Filter & Search ──────────────────────────────────────
    const searchInput  = document.getElementById('search');
    const statusFilter = document.getElementById('statusFilter');
    const jenisFilter  = document.getElementById('jenisFilter');
    let searchTimeout;

    function applyFilters() {
        const searchTerm  = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value.toLowerCase();
        const jenisValue  = jenisFilter.value.toLowerCase();

        document.querySelectorAll('#tableBody tr').forEach(row => {
            if (row.querySelector('td[colspan]')) return;

            const text       = row.textContent.toLowerCase();
            // Jenis = kolom ke-6 (index 5), Status = kolom ke-8 (index 7)
            const jenisCell  = row.querySelector('td:nth-child(6) span');
            const statusCell = row.querySelector('td:nth-child(8) span');

            const matchSearch = !searchTerm  || text.includes(searchTerm);
            const matchStatus = !statusValue || (statusCell && statusCell.textContent.toLowerCase().includes(statusValue));
            const matchJenis  = !jenisValue  || (jenisCell  && jenisCell.textContent.toLowerCase().includes(jenisValue));

            row.style.display = (matchSearch && matchStatus && matchJenis) ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', applyFilters);
    statusFilter.addEventListener('change', applyFilters);
    jenisFilter.addEventListener('change', applyFilters);
});

// ── Modal Selesai ────────────────────────────────────────────
function showSelesaiModal(id) {
    const baseUrl = "{{ url('kerusakan') }}";
    document.getElementById('selesaiForm').action = `${baseUrl}/${id}/update-status`;
    document.getElementById('selesaiModal').classList.remove('hidden');
}

function hideSelesaiModal() {
    document.getElementById('selesaiModal').classList.add('hidden');
}
</script>
@endpush

@push('styles')
<style>
    /* QR Code Small */
    .qr-small-wrapper {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .qr-small-wrapper svg { width: 100%; height: 100%; display: block; }

    /* QR Code Large */
    .qr-large-wrapper {
        width: 200px;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        padding: 10px;
    }
    .qr-large-wrapper svg { width: 100%; height: 100%; display: block; border-radius: 8px; }

    /* QR Popup */
    .qr-popup { z-index: 9999; animation: fadeIn 0.2s ease-out; }

    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to   { opacity: 1; transform: scale(1); }
    }

    .qr-popup .bg-white {
        box-shadow: 0 20px 25px -5px rgba(0,0,0,.15), 0 10px 10px -5px rgba(0,0,0,.05);
        min-width: 260px;
    }

    /* Close Button */
    .close-popup {
        width: 28px; height: 28px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all .2s ease;
    }
    .close-popup:hover { background-color: #f3f4f6; transform: scale(1.05); }

    /* Table rows */
    #tableBody tr { transition: background-color .2s ease; }

    @media (max-width: 768px) {
        .overflow-x-auto { -webkit-overflow-scrolling: touch; }
    }
</style>
@endpush