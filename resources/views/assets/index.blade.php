{{-- resources/views/assets/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Manajemen Asset')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Asset</h1>
        <p class="mt-1 text-sm text-gray-600">Kelola data asset dan inventaris</p>
    </div>
    <div class="flex items-center gap-2 flex-wrap">
        <button id="btnGeneratePdf"
                onclick="generateBarcodePdf()"
                class="hidden items-center px-3 py-2 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700 transition">
            <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>PDF (<span id="btnPdfCount">0</span>)</span>
        </button>
        <a href="{{ route('asset.create') }}"
           class="inline-flex items-center px-3 py-2 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700 transition">
            <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Asset
        </a>
    </div>
</div>
@endsection

@section('content')

<form id="formBarcodePdf" action="{{ route('asset.barcode-pdf') }}" method="POST" target="_blank" style="display:none">
    @csrf
</form>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

    {{-- Search & Filter --}}
    <div class="p-4 border-b border-gray-200 bg-gray-50 space-y-3">
        <div class="relative">
            <input type="text" id="search"
                   placeholder="Cari kode, nama, serial number..."
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            <svg class="absolute left-3 top-3 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
            <select id="filter_kategori" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->KategoriID }}">{{ $kategori->NamaKategori }}</option>
                @endforeach
            </select>
            <select id="filter_kondisi" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                <option value="">Semua Kondisi</option>
                <option value="baik">Baik</option>
                <option value="rusak_ringan">Rusak Ringan</option>
                <option value="rusak_berat">Rusak Berat</option>
                <option value="tidak_berfungsi">Tidak Berfungsi</option>
                <option value="hilang">Hilang</option>
            </select>
            <select id="filter_status" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                <option value="">Semua Status</option>
                <option value="aktif">Aktif</option>
                <option value="non_aktif">Non Aktif</option>
                <option value="dipinjam">Dipinjam</option>
                <option value="diperbaiki">Diperbaiki</option>
            </select>
        </div>
        <div id="selectionBar" class="hidden items-center justify-between bg-purple-50 border border-purple-200 rounded-lg px-3 py-2 gap-2">
            <span class="text-sm text-purple-700 font-medium"><strong id="selectedCount">0</strong> dipilih</span>
            <div class="flex items-center gap-2">
                <button onclick="selectAllVisible()" class="text-xs px-2.5 py-1 border border-purple-400 text-purple-600 rounded hover:bg-purple-100 transition whitespace-nowrap">Pilih Semua</button>
                <button onclick="clearSelection()" class="text-xs px-2.5 py-1 border border-gray-300 text-gray-500 rounded hover:bg-gray-100 transition">Batal</button>
            </div>
        </div>
    </div>

    {{-- DESKTOP TABLE --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 w-10"><input type="checkbox" id="checkAll" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 cursor-pointer"></th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-14">QR</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Asset</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Merk / S/N</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="asset-table-body">
                @forelse($assets as $asset)
                @php
                    $kondisiClass = match($asset->kondisi) {
                        'baik'            => 'bg-green-100 text-green-800',
                        'rusak_ringan'    => 'bg-yellow-100 text-yellow-800',
                        'rusak_berat'     => 'bg-red-100 text-red-800',
                        'tidak_berfungsi' => 'bg-orange-100 text-orange-800',
                        default           => 'bg-gray-100 text-gray-800',
                    };
                    $statusClass = match($asset->status_asset) {
                        'aktif'      => 'bg-green-100 text-green-800',
                        'non_aktif'  => 'bg-gray-100 text-gray-800',
                        'dipinjam'   => 'bg-blue-100 text-blue-800',
                        'diperbaiki' => 'bg-yellow-100 text-yellow-800',
                        'tersimpan'  => 'bg-indigo-100 text-indigo-800',
                        'dihapus'    => 'bg-red-100 text-red-800',
                        default      => 'bg-gray-100 text-gray-800',
                    };
                @endphp
                <tr class="hover:bg-gray-50 transition asset-row"
                    data-id="{{ $asset->assetID }}"
                    data-kondisi="{{ $asset->kondisi }}"
                    data-status="{{ $asset->status_asset }}"
                    data-kategori="{{ $asset->KategoriID }}">
                    <td class="px-4 py-3">
                        <input type="checkbox" class="asset-checkbox rounded border-gray-300 text-purple-600 focus:ring-purple-500 cursor-pointer" value="{{ $asset->assetID }}" data-name="{{ $asset->nama_asset }}">
                    </td>
                    <td class="px-4 py-3">
                        <div class="relative inline-block qr-container">
                            <button type="button" class="qr-trigger focus:outline-none group" data-asset-id="{{ $asset->assetID }}" data-kode="{{ $asset->kode_asset }}">
                                <div class="w-9 h-9 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-purple-50 transition overflow-hidden">
                                    @if(isset($asset->qrCode) && $asset->qrCode)
                                        <div class="qr-small-wrapper">{!! $asset->qrCode !!}</div>
                                    @else
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                    @endif
                                </div>
                            </button>
                            <div class="qr-popup hidden fixed z-50">
                                <div class="bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden relative">
                                    <button type="button" class="close-popup absolute top-3 right-3 text-gray-400 hover:text-gray-600 z-10 bg-gray-50 hover:bg-gray-100 rounded-full p-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                    <div class="p-6 pt-8 flex flex-col items-center">
                                        <div class="qr-large-wrapper">@if(isset($asset->qrCode) && $asset->qrCode){!! $asset->qrCode !!}@endif</div>
                                        <div class="mt-3 text-center">
                                            <p class="text-xs text-gray-500 mb-1">Kode Asset</p>
                                            <p class="text-sm font-mono font-semibold text-purple-700 bg-purple-50 px-3 py-1 rounded-full">{{ $asset->kode_asset }}</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $asset->nama_asset }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap"><span class="text-xs font-mono font-medium text-gray-900">{{ $asset->kode_asset }}</span></td>
                    <td class="px-4 py-3"><span class="text-sm text-gray-900">{{ $asset->nama_asset }}</span></td>
                    <td class="px-4 py-3 whitespace-nowrap"><span class="text-sm text-gray-600">{{ $asset->kategori->NamaKategori ?? '-' }}</span></td>
                    <td class="px-4 py-3 whitespace-nowrap"><span class="text-sm text-gray-600">{{ $asset->lokasi->NamaLokasi ?? '-' }}</span></td>
                    <td class="px-4 py-3">
                        <div class="text-sm text-gray-700">{{ $asset->merk ?? '-' }}</div>
                        <div class="text-xs font-mono text-gray-400 mt-0.5">{{ $asset->serial_number ?? '-' }}</div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $kondisiClass }}">{{ ucfirst(str_replace('_', ' ', $asset->kondisi)) }}</span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $asset->status_asset)) }}</span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('asset.show', $asset->assetID) }}" class="text-blue-600 hover:text-blue-900 transition" title="Detail">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('asset.edit', $asset->assetID) }}" class="text-purple-600 hover:text-purple-900 transition" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <button type="button" onclick="confirmDelete({{ $asset->assetID }})" class="text-red-500 hover:text-red-800 transition" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-6 py-12 text-center text-gray-500">
                        <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        <p class="text-sm">Tidak ada data asset</p>
                        <a href="{{ route('asset.create') }}" class="mt-1 inline-flex text-sm text-purple-600 hover:text-purple-500">Tambah asset baru</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MOBILE CARD LIST --}}
    <div class="md:hidden divide-y divide-gray-100" id="asset-card-list">
        @forelse($assets as $asset)
        @php
            $kondisiClass = match($asset->kondisi) {
                'baik'            => 'bg-green-100 text-green-800',
                'rusak_ringan'    => 'bg-yellow-100 text-yellow-800',
                'rusak_berat'     => 'bg-red-100 text-red-800',
                'tidak_berfungsi' => 'bg-orange-100 text-orange-800',
                default           => 'bg-gray-100 text-gray-800',
            };
            $statusClass = match($asset->status_asset) {
                'aktif'      => 'bg-green-100 text-green-800',
                'non_aktif'  => 'bg-gray-100 text-gray-800',
                'dipinjam'   => 'bg-blue-100 text-blue-800',
                'diperbaiki' => 'bg-yellow-100 text-yellow-800',
                'tersimpan'  => 'bg-indigo-100 text-indigo-800',
                'dihapus'    => 'bg-red-100 text-red-800',
                default      => 'bg-gray-100 text-gray-800',
            };
        @endphp
        <div class="asset-row p-4"
             data-id="{{ $asset->assetID }}"
             data-kondisi="{{ $asset->kondisi }}"
             data-status="{{ $asset->status_asset }}"
             data-kategori="{{ $asset->KategoriID }}">
            <div class="flex items-start gap-3">
                <input type="checkbox"
                       class="asset-checkbox mt-1 rounded border-gray-300 text-purple-600 focus:ring-purple-500 cursor-pointer flex-shrink-0"
                       value="{{ $asset->assetID }}"
                       data-name="{{ $asset->nama_asset }}">
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $asset->nama_asset }}</p>
                            <p class="text-xs font-mono text-purple-600 mt-0.5">{{ $asset->kode_asset }}</p>
                        </div>
                        <span class="flex-shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                            {{ ucfirst(str_replace('_', ' ', $asset->status_asset)) }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-xs text-gray-600">
                        <div><span class="text-gray-400">Kategori:</span> {{ $asset->kategori->NamaKategori ?? '-' }}</div>
                        <div><span class="text-gray-400">Lokasi:</span> {{ $asset->lokasi->NamaLokasi ?? '-' }}</div>
                        <div><span class="text-gray-400">Merk:</span> {{ $asset->merk ?? '-' }}</div>
                        <div>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium {{ $kondisiClass }}">
                                {{ ucfirst(str_replace('_', ' ', $asset->kondisi)) }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-3 flex gap-2">
                        <a href="{{ route('asset.show', $asset->assetID) }}" class="flex-1 text-center text-xs px-3 py-1.5 rounded-lg border border-blue-200 text-blue-600 hover:bg-blue-50 transition font-medium">Detail</a>
                        <a href="{{ route('asset.edit', $asset->assetID) }}" class="flex-1 text-center text-xs px-3 py-1.5 rounded-lg border border-purple-200 text-purple-600 hover:bg-purple-50 transition font-medium">Edit</a>
                        <button type="button" onclick="confirmDelete({{ $asset->assetID }})" class="flex-1 text-center text-xs px-3 py-1.5 rounded-lg border border-red-200 text-red-500 hover:bg-red-50 transition font-medium">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="p-8 text-center text-gray-500">
            <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
            <p class="text-sm mb-1">Tidak ada data asset</p>
            <a href="{{ route('asset.create') }}" class="text-sm text-purple-600">Tambah asset baru</a>
        </div>
        @endforelse
    </div>

    @if($assets->hasPages())
    <div class="px-4 py-4 border-t border-gray-200 bg-gray-50">{{ $assets->links() }}</div>
    @endif
</div>

{{-- Delete Modal --}}
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-sm w-full">
        <div class="p-6">
            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Konfirmasi Hapus</h3>
            <p class="text-sm text-gray-600 text-center mb-6">Apakah Anda yakin ingin menghapus asset ini? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition text-sm">Batal</button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // QR Popup
    let activePopup = null;
    document.querySelectorAll('.qr-trigger').forEach(trigger => {
        trigger.addEventListener('click', function (e) {
            e.stopPropagation();
            const popup = this.closest('.qr-container').querySelector('.qr-popup');
            if (activePopup && activePopup !== popup) activePopup.classList.add('hidden');
            popup.classList.toggle('hidden');
            if (!popup.classList.contains('hidden')) {
                activePopup = popup;
                const rect = this.getBoundingClientRect();
                popup.style.top  = (rect.bottom + window.scrollY + 5) + 'px';
                popup.style.left = Math.min(rect.left + window.scrollX, window.innerWidth - 280) + 'px';
            } else { activePopup = null; }
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
            activePopup.classList.add('hidden'); activePopup = null;
        }
    });

    // Filter
    const searchInput    = document.getElementById('search');
    const filterKategori = document.getElementById('filter_kategori');
    const filterKondisi  = document.getElementById('filter_kondisi');
    const filterStatus   = document.getElementById('filter_status');
    let searchTimeout;

    function applyFilters() {
        const s = searchInput.value.toLowerCase();
        const k = filterKondisi.value;
        const st = filterStatus.value;
        document.querySelectorAll('.asset-row').forEach(row => {
            const match = (!s  || row.textContent.toLowerCase().includes(s))
                       && (!k  || row.dataset.kondisi  === k)
                       && (!st || row.dataset.status   === st);
            row.style.display = match ? '' : 'none';
        });
        updateCheckAllState();
    }
    searchInput.addEventListener('keyup', () => { clearTimeout(searchTimeout); searchTimeout = setTimeout(applyFilters, 300); });
    filterKategori.addEventListener('change', applyFilters);
    filterKondisi.addEventListener('change', applyFilters);
    filterStatus.addEventListener('change', applyFilters);

    // Checkboxes
    const checkAll = document.getElementById('checkAll');
    if (checkAll) {
        checkAll.addEventListener('change', function () {
            getVisibleCheckboxes().forEach(cb => { cb.checked = this.checked; toggleRowHighlight(cb); });
            updateSelectionUI();
        });
    }
    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('asset-checkbox')) {
            toggleRowHighlight(e.target); updateCheckAllState(); updateSelectionUI();
        }
    });
});

function toggleRowHighlight(cb) {
    const row = cb.closest('.asset-row') || cb.closest('tr');
    if (!row) return;
    row.classList.toggle('bg-purple-50', cb.checked);
}
function getVisibleCheckboxes() {
    return Array.from(document.querySelectorAll('.asset-checkbox')).filter(cb => {
        const row = cb.closest('.asset-row') || cb.closest('tr');
        return row && row.style.display !== 'none';
    });
}
function getCheckedIds() { return Array.from(document.querySelectorAll('.asset-checkbox:checked')).map(cb => cb.value); }
function updateCheckAllState() {
    const checkAll = document.getElementById('checkAll');
    if (!checkAll) return;
    const visible = getVisibleCheckboxes(); const checked = visible.filter(cb => cb.checked);
    checkAll.indeterminate = checked.length > 0 && checked.length < visible.length;
    checkAll.checked = visible.length > 0 && checked.length === visible.length;
}
function updateSelectionUI() {
    const count = getCheckedIds().length;
    const btnPdf = document.getElementById('btnGeneratePdf');
    const btnCount = document.getElementById('btnPdfCount');
    const selBar = document.getElementById('selectionBar');
    const selCount = document.getElementById('selectedCount');
    if (count > 0) {
        btnPdf.classList.remove('hidden'); btnPdf.classList.add('inline-flex');
        selBar.classList.remove('hidden'); selBar.classList.add('flex');
        if (btnCount) btnCount.textContent = count;
        if (selCount) selCount.textContent = count;
    } else {
        btnPdf.classList.add('hidden'); btnPdf.classList.remove('inline-flex');
        selBar.classList.add('hidden'); selBar.classList.remove('flex');
    }
}
function selectAllVisible() { getVisibleCheckboxes().forEach(cb => { cb.checked = true; toggleRowHighlight(cb); }); updateCheckAllState(); updateSelectionUI(); }
function clearSelection() {
    document.querySelectorAll('.asset-checkbox').forEach(cb => { cb.checked = false; toggleRowHighlight(cb); });
    const ca = document.getElementById('checkAll');
    if (ca) { ca.checked = false; ca.indeterminate = false; }
    updateSelectionUI();
}
function generateBarcodePdf() {
    const ids = getCheckedIds();
    if (!ids.length) { alert('Pilih minimal 1 asset.'); return; }
    const form = document.getElementById('formBarcodePdf');
    form.querySelectorAll('input[name="asset_ids[]"]').forEach(el => el.remove());
    ids.forEach(id => { const i = document.createElement('input'); i.type='hidden'; i.name='asset_ids[]'; i.value=id; form.appendChild(i); });
    form.submit();
}
function confirmDelete(id) { document.getElementById('deleteForm').action=`/asset/${id}`; document.getElementById('deleteModal').classList.remove('hidden'); }
function closeDeleteModal() { document.getElementById('deleteModal').classList.add('hidden'); }
document.getElementById('deleteModal')?.addEventListener('click', function(e) { if(e.target===this) closeDeleteModal(); });
</script>
@endpush

@push('styles')
<style>
    .qr-small-wrapper { width: 28px; height: 28px; display:flex; align-items:center; justify-content:center; }
    .qr-small-wrapper svg { width:100%; height:100%; display:block; }
    .qr-large-wrapper { width:180px; height:180px; display:flex; align-items:center; justify-content:center; background:white; padding:8px; }
    .qr-large-wrapper svg { width:100%; height:100%; display:block; border-radius:6px; }
    .qr-popup { z-index:9999; animation:fadeInPop .15s ease-out; }
    .qr-popup .bg-white { min-width:240px; box-shadow:0 20px 40px rgba(0,0,0,.15); }
    @keyframes fadeInPop { from{opacity:0;transform:scale(.95)} to{opacity:1;transform:scale(1)} }
    .close-popup { width:26px; height:26px; display:flex; align-items:center; justify-content:center; cursor:pointer; }
    .asset-row { transition:background-color .15s ease; }
</style>
@endpush