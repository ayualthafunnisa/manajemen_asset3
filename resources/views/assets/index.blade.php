{{-- resources/views/assets/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Manajemen Asset')

@section('header')
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Asset</h1>
        <p class="mt-1 text-sm text-gray-600">Kelola data asset dan inventaris</p>
    </div>
    <div class="flex items-center gap-2">
        {{-- Tombol Generate PDF Barcode (muncul jika ada yang dipilih) --}}
        <button id="btnGeneratePdf"
                onclick="generateBarcodePdf()"
                class="hidden items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span id="btnPdfLabel">Generate PDF Barcode (0)</span>
        </button>

        <a href="{{ route('asset.create') }}"
           class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Asset
        </a>
    </div>
</div>
@endsection

@section('content')

{{-- Hidden form untuk submit daftar asset_ids ke generate PDF --}}
<form id="formBarcodePdf" action="{{ route('asset.barcode-pdf') }}" method="POST" target="_blank" style="display:none">
    @csrf
</form>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    {{-- Search & Filter --}}
    <div class="p-4 border-b border-gray-200 bg-gray-50">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <input type="text" id="search"
                           placeholder="Search kode, nama, serial number..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="flex gap-2 flex-wrap">
                <select id="filter_kategori" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->KategoriID }}">{{ $kategori->NamaKategori }}</option>
                    @endforeach
                </select>
                <select id="filter_kondisi" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">Semua Kondisi</option>
                    <option value="baik">Baik</option>
                    <option value="rusak_ringan">Rusak Ringan</option>
                    <option value="rusak_berat">Rusak Berat</option>
                    <option value="tidak_berfungsi">Tidak Berfungsi</option>
                    <option value="hilang">Hilang</option>
                </select>
                <select id="filter_status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="non_aktif">Non Aktif</option>
                    <option value="dipinjam">Dipinjam</option>
                    <option value="diperbaiki">Diperbaiki</option>
                </select>
            </div>
        </div>

        {{-- Selection bar (muncul saat ada yang dipilih) --}}
        <div id="selectionBar"
             class="hidden mt-3 flex items-center justify-between bg-purple-50 border border-purple-200 rounded-lg px-4 py-2">
            <div class="flex items-center gap-3 text-sm text-purple-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span><strong id="selectedCount">0</strong> asset dipilih untuk generate barcode PDF</span>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="selectAllVisible()"
                        class="text-xs px-3 py-1 border border-purple-400 text-purple-600 rounded hover:bg-purple-100 transition">
                    Pilih Semua Terlihat
                </button>
                <button onclick="clearSelection()"
                        class="text-xs px-3 py-1 border border-gray-300 text-gray-500 rounded hover:bg-gray-100 transition">
                    Batal Pilih
                </button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    {{-- Checkbox "pilih semua" --}}
                    <th class="px-4 py-3 w-10">
                        <input type="checkbox" id="checkAll"
                               class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 cursor-pointer"
                               title="Pilih semua">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">QR Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Asset</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Asset</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Merk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Serial Number</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="asset-table-body">
                @forelse($assets as $asset)
                <tr class="hover:bg-gray-50 transition asset-row" data-id="{{ $asset->assetID }}">

                    {{-- Checkbox per baris --}}
                    <td class="px-4 py-4">
                        <input type="checkbox"
                               class="asset-checkbox rounded border-gray-300 text-purple-600 focus:ring-purple-500 cursor-pointer"
                               value="{{ $asset->assetID }}"
                               data-name="{{ $asset->nama_asset }}">
                    </td>

                    {{-- QR Code Column --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="relative inline-block qr-container">
                            <button type="button"
                                    class="qr-trigger focus:outline-none group"
                                    data-asset-id="{{ $asset->assetID }}"
                                    data-kode="{{ $asset->kode_asset }}">
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-purple-50 transition overflow-hidden">
                                    @if(isset($asset->qrCode) && $asset->qrCode)
                                        <div class="qr-small-wrapper">
                                            {!! $asset->qrCode !!}
                                        </div>
                                    @else
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                        </svg>
                                    @endif
                                </div>
                            </button>

                            {{-- Popup dengan QR besar --}}
                            <div class="qr-popup hidden fixed z-50">
                                <div class="bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden relative">
                                    <button type="button" class="close-popup absolute top-3 right-3 text-gray-400 hover:text-gray-600 z-10 bg-gray-50 hover:bg-gray-100 rounded-full p-1.5 transition-all duration-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                    <div class="p-6 pt-8 flex flex-col items-center">
                                        <div class="qr-large-wrapper">
                                            @if(isset($asset->qrCode) && $asset->qrCode)
                                                {!! $asset->qrCode !!}
                                            @endif
                                        </div>
                                        <div class="mt-4 text-center">
                                            <p class="text-xs text-gray-500 mb-1">Kode Asset</p>
                                            <p class="text-sm font-mono font-semibold text-purple-700 bg-purple-50 px-3 py-1 rounded-full inline-block">
                                                {{ $asset->kode_asset }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-2">{{ $asset->nama_asset }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-mono font-medium text-gray-900">{{ $asset->kode_asset }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-900">{{ $asset->nama_asset }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-600">{{ $asset->kategori->NamaKategori ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-600">{{ $asset->lokasi->NamaLokasi ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-600">{{ $asset->merk ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-mono text-gray-600">{{ $asset->serial_number ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($asset->kondisi == 'baik') bg-green-100 text-green-800
                            @elseif($asset->kondisi == 'rusak_ringan') bg-yellow-100 text-yellow-800
                            @elseif($asset->kondisi == 'rusak_berat') bg-red-100 text-red-800
                            @elseif($asset->kondisi == 'tidak_berfungsi') bg-orange-100 text-orange-800
                            @elseif($asset->kondisi == 'hilang') bg-gray-100 text-gray-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $asset->kondisi)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($asset->status_asset == 'aktif') bg-green-100 text-green-800
                            @elseif($asset->status_asset == 'non_aktif') bg-gray-100 text-gray-800
                            @elseif($asset->status_asset == 'dipinjam') bg-blue-100 text-blue-800
                            @elseif($asset->status_asset == 'diperbaiki') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $asset->status_asset)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('asset.show', $asset->assetID) }}"
                               class="text-blue-600 hover:text-blue-900 transition" title="Detail">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('asset.edit', $asset->assetID) }}"
                               class="text-purple-600 hover:text-purple-900 transition" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <button type="button"
                                    onclick="confirmDelete({{ $asset->assetID }})"
                                    class="text-red-600 hover:text-red-900 transition" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="px-6 py-12 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="mt-2">Tidak ada data asset</p>
                        <a href="{{ route('asset.create') }}" class="mt-2 inline-flex items-center text-sm text-purple-600 hover:text-purple-500">
                            Tambah asset baru
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($assets->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $assets->links() }}
    </div>
    @endif
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center justify-center mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Konfirmasi Hapus</h3>
            <p class="text-sm text-gray-600 text-center mb-6">Apakah Anda yakin ingin menghapus asset ini? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                    Batal
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── QR Code Popup Handler ────────────────────────────────
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
                const rect        = this.getBoundingClientRect();
                const popupContent = popup.querySelector('.bg-white');
                const popupRect   = popupContent.getBoundingClientRect();

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
    const searchInput    = document.getElementById('search');
    const filterKategori = document.getElementById('filter_kategori');
    const filterKondisi  = document.getElementById('filter_kondisi');
    const filterStatus   = document.getElementById('filter_status');
    let searchTimeout;

    function applyFilters() {
        const searchTerm   = searchInput.value.toLowerCase();
        const kondisiValue = filterKondisi.value;
        const statusValue  = filterStatus.value;

        document.querySelectorAll('tbody tr.asset-row').forEach(row => {
            const cells      = row.querySelectorAll('td');
            if (cells.length < 10) return;

            const rowText    = row.textContent.toLowerCase();
            const kondisiCell = cells[8]?.textContent.trim().toLowerCase() || '';
            const statusCell  = cells[9]?.textContent.trim().toLowerCase() || '';

            const matchesSearch  = !searchTerm   || rowText.includes(searchTerm);
            const matchesKondisi = !kondisiValue  || kondisiCell.includes(kondisiValue.replace('_', ' '));
            const matchesStatus  = !statusValue   || statusCell.includes(statusValue.replace('_', ' '));

            row.style.display = (matchesSearch && matchesKondisi && matchesStatus) ? '' : 'none';
        });

        updateCheckAllState();
    }

    searchInput.addEventListener('keyup', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(applyFilters, 300);
    });
    filterKategori.addEventListener('change', applyFilters);
    filterKondisi.addEventListener('change', applyFilters);
    filterStatus.addEventListener('change', applyFilters);

    // ── Checkbox: Pilih Semua ────────────────────────────────
    const checkAll = document.getElementById('checkAll');

    checkAll.addEventListener('change', function () {
        getVisibleCheckboxes().forEach(cb => {
            cb.checked = this.checked;
            toggleRowHighlight(cb);
        });
        updateSelectionUI();
    });

    document.getElementById('asset-table-body').addEventListener('change', function (e) {
        if (e.target.classList.contains('asset-checkbox')) {
            toggleRowHighlight(e.target);
            updateCheckAllState();
            updateSelectionUI();
        }
    });
});

// ── Helper Functions ─────────────────────────────────────────

function toggleRowHighlight(checkbox) {
    const row = checkbox.closest('tr');
    if (checkbox.checked) {
        row.classList.add('bg-purple-50');
        row.classList.remove('hover:bg-gray-50');
    } else {
        row.classList.remove('bg-purple-50');
        row.classList.add('hover:bg-gray-50');
    }
}

function getVisibleCheckboxes() {
    return Array.from(document.querySelectorAll('.asset-checkbox')).filter(cb => {
        return cb.closest('tr').style.display !== 'none';
    });
}

function getCheckedIds() {
    return Array.from(document.querySelectorAll('.asset-checkbox:checked')).map(cb => cb.value);
}

function updateCheckAllState() {
    const visible = getVisibleCheckboxes();
    const checked = visible.filter(cb => cb.checked);
    const checkAll = document.getElementById('checkAll');
    checkAll.indeterminate = checked.length > 0 && checked.length < visible.length;
    checkAll.checked = visible.length > 0 && checked.length === visible.length;
}

function updateSelectionUI() {
    const count        = getCheckedIds().length;
    const btnPdf       = document.getElementById('btnGeneratePdf');
    const btnLabel     = document.getElementById('btnPdfLabel');
    const selectionBar = document.getElementById('selectionBar');
    const selectedCount = document.getElementById('selectedCount');

    if (count > 0) {
        btnPdf.classList.remove('hidden');
        btnPdf.classList.add('inline-flex');
        selectionBar.classList.remove('hidden');
        selectionBar.classList.add('flex');
        btnLabel.textContent    = `Generate PDF Barcode (${count})`;
        selectedCount.textContent = count;
    } else {
        btnPdf.classList.add('hidden');
        btnPdf.classList.remove('inline-flex');
        selectionBar.classList.add('hidden');
        selectionBar.classList.remove('flex');
    }
}

function selectAllVisible() {
    getVisibleCheckboxes().forEach(cb => {
        cb.checked = true;
        toggleRowHighlight(cb);
    });
    updateCheckAllState();
    updateSelectionUI();
}

function clearSelection() {
    document.querySelectorAll('.asset-checkbox').forEach(cb => {
        cb.checked = false;
        toggleRowHighlight(cb);
    });
    const checkAll = document.getElementById('checkAll');
    checkAll.checked = false;
    checkAll.indeterminate = false;
    updateSelectionUI();
}

function generateBarcodePdf() {
    const ids = getCheckedIds();
    if (ids.length === 0) {
        alert('Pilih minimal 1 asset untuk generate barcode PDF.');
        return;
    }

    const form = document.getElementById('formBarcodePdf');
    form.querySelectorAll('input[name="asset_ids[]"]').forEach(el => el.remove());

    ids.forEach(id => {
        const input = document.createElement('input');
        input.type  = 'hidden';
        input.name  = 'asset_ids[]';
        input.value = id;
        form.appendChild(input);
    });

    form.submit();
}

// ── Delete Modal ─────────────────────────────────────────────

function confirmDelete(assetId) {
    const modal      = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    if (deleteForm) deleteForm.action = `/asset/${assetId}`;
    if (modal)      modal.classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal')?.classList.add('hidden');
}

document.getElementById('deleteModal')?.addEventListener('click', function (e) {
    if (e.target === this) closeDeleteModal();
});
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
    #asset-table-body tr { transition: background-color .2s ease; }

    @media (max-width: 768px) {
        .overflow-x-auto { -webkit-overflow-scrolling: touch; }
    }
</style>
@endpush