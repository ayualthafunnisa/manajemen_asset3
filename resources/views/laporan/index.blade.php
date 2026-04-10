@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Laporan Asset</h1>
            <p class="mt-1 text-sm text-gray-500">Generate laporan asset, kerusakan, penghapusan, dan penyusutan</p>
        </div>

        {{-- Alert --}}
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-300 text-red-700 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-300 text-green-700 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Tab Jenis Laporan --}}
        <div class="mb-5">
            <p class="text-sm font-medium text-gray-700 mb-2">Jenis Laporan</p>
            <div class="flex flex-wrap gap-2" id="tabWrapper">
                @php
                    $tabs = [
                        ['value' => 'asset',       'label' => 'Data Asset',   'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', 'desc' => 'Seluruh data asset'],
                        ['value' => 'kerusakan',   'label' => 'Kerusakan',    'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'desc' => 'Laporan kerusakan asset'],
                        ['value' => 'penghapusan', 'label' => 'Penghapusan',  'icon' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16', 'desc' => 'Asset yang dihapus'],
                        ['value' => 'penyusutan',  'label' => 'Penyusutan',   'icon' => 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6', 'desc' => 'Nilai penyusutan asset'],
                    ];
                @endphp

                @foreach($tabs as $tab)
                <button type="button"
                    data-value="{{ $tab['value'] }}"
                    onclick="selectTab('{{ $tab['value'] }}')"
                    class="tab-btn flex items-center gap-2 px-4 py-2.5 rounded-lg border text-sm font-medium transition-all duration-150
                        {{ $tab['value'] === 'asset' ? 'bg-purple-600 text-white border-purple-600 shadow-sm' : 'bg-white text-gray-600 border-gray-300 hover:border-purple-400 hover:text-purple-600' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}"/>
                    </svg>
                    <span>{{ $tab['label'] }}</span>
                </button>
                @endforeach
            </div>
            <input type="hidden" id="jenis_laporan" value="asset">
        </div>

        {{-- Filter Periode & Aksi --}}
        <div class="flex flex-wrap gap-2 items-center border-t pt-4">
            <div class="relative flex-1 min-w-[220px]">
                <input type="text" id="date_range"
                    class="w-full pl-3 pr-10 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 cursor-pointer bg-white"
                    placeholder="Pilih rentang tanggal"
                    readonly>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>

            <button type="button" onclick="filterLaporan()"
                class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                </svg>
                Filter
            </button>

            <button type="button" onclick="resetFilter()"
                class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition border border-gray-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Reset
            </button>

            <div class="flex gap-2 ml-auto">
                <button type="button" onclick="exportLaporan('pdf')"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    PDF
                </button>
                <button type="button" onclick="exportLaporan('excel')"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Excel
                </button>
            </div>
        </div>

        {{-- Tabel Preview --}}
        <div class="mt-5" id="tableContainer">
            <div id="emptyState" class="flex flex-col items-center py-12 text-gray-400">
                <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm">Pilih rentang tanggal dan klik <strong class="text-gray-500">Filter</strong> untuk melihat data</p>
            </div>
            <div id="tableResult"></div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script>
$(document).ready(function() {
    $('#date_range').daterangepicker({
        locale: {
            format: 'DD-MM-YYYY',
            separator: ' - ',
            applyLabel: 'Pilih',
            cancelLabel: 'Batal',
            daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
            monthNames: ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'],
            firstDay: 1
        },
        autoUpdateInput: false,
        singleDatePicker: false,
        showDropdowns: true,
        autoApply: false,
        linkedCalendars: false,
        opens: 'left',
    }, function(start, end) {
        $('#date_range').val(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
    });

    // Blok input manual
    $('#date_range').on('keydown keypress paste', function(e) { e.preventDefault(); return false; });
});

function selectTab(value) {
    // Update hidden input
    document.getElementById('jenis_laporan').value = value;

    // Update tampilan tab
    document.querySelectorAll('.tab-btn').forEach(btn => {
        if (btn.dataset.value === value) {
            btn.className = btn.className
                .replace('bg-white text-gray-600 border-gray-300 hover:border-purple-400 hover:text-purple-600', '')
                + ' bg-purple-600 text-white border-purple-600 shadow-sm';
        } else {
            btn.className = btn.className
                .replace('bg-purple-600 text-white border-purple-600 shadow-sm', '')
                + ' bg-white text-gray-600 border-gray-300 hover:border-purple-400 hover:text-purple-600';
        }
    });

    // Kalau sudah ada data, otomatis filter ulang
    const dateRange = document.getElementById('date_range').value;
    if (dateRange) {
        filterLaporan();
    }
}

function filterLaporan() {
    const dateRange = document.getElementById('date_range').value;
    if (!dateRange) {
        alert('Pilih rentang tanggal terlebih dahulu!');
        return;
    }

    const jenisLaporan = document.getElementById('jenis_laporan').value;
    const tableResult = document.getElementById('tableResult');
    const emptyState = document.getElementById('emptyState');

    emptyState.classList.add('hidden');
    tableResult.innerHTML = `
        <div class="flex flex-col items-center py-12 text-gray-400">
            <div class="animate-spin rounded-full h-8 w-8 border-2 border-purple-200 border-t-purple-600 mb-3"></div>
            <p class="text-sm">Memuat data...</p>
        </div>`;

    fetch('{{ route("laporan.preview") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            date_range: dateRange,
            jenis_laporan: jenisLaporan
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.error) {
            tableResult.innerHTML = `<p class="py-8 text-center text-red-500 text-sm">${data.error}</p>`;
            return;
        }
        tableResult.innerHTML = data.html || '<p class="py-8 text-center text-gray-400 text-sm">Tidak ada data untuk periode ini.</p>';
    })
    .catch(() => {
        tableResult.innerHTML = '<p class="py-8 text-center text-red-500 text-sm">Terjadi kesalahan saat memuat data.</p>';
    });
}

function resetFilter() {
    document.getElementById('date_range').value = '';
    document.getElementById('jenis_laporan').value = 'asset';
    selectTab('asset');
    document.getElementById('tableResult').innerHTML = '';
    document.getElementById('emptyState').classList.remove('hidden');
}

function exportLaporan(type) {
    const dateRange = document.getElementById('date_range').value;
    if (!dateRange) {
        alert('Pilih rentang tanggal terlebih dahulu!');
        return;
    }
    const jenisLaporan = document.getElementById('jenis_laporan').value;
    const params = new URLSearchParams({ date_range: dateRange, jenis_laporan: jenisLaporan });
    const url = type === 'pdf' ? '{{ route("laporan.export-pdf") }}' : '{{ route("laporan.export-excel") }}';
    window.location.href = url + '?' + params.toString();
}
</script>
@endpush
@endsection