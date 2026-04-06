@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Laporan Asset</h1>
            <p class="mt-1 text-sm text-gray-600">Generate laporan asset, kerusakan, penghapusan, dan penyusutan</p>
        </div>

        {{-- Alert error atau sukses --}}
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form Filter --}}
        <form id="laporanForm" class="space-y-4">
            @csrf
            
            {{-- Jenis Laporan --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Laporan</label>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input type="radio" name="jenis_laporan" value="asset" class="mr-3" checked>
                        <div>
                            <span class="font-medium">Data Asset</span>
                            <p class="text-xs text-gray-500">Seluruh data asset</p>
                        </div>
                    </label>
                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input type="radio" name="jenis_laporan" value="kerusakan" class="mr-3">
                        <div>
                            <span class="font-medium">Kerusakan</span>
                            <p class="text-xs text-gray-500">Laporan kerusakan asset</p>
                        </div>
                    </label>
                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input type="radio" name="jenis_laporan" value="penghapusan" class="mr-3">
                        <div>
                            <span class="font-medium">Penghapusan</span>
                            <p class="text-xs text-gray-500">Asset yang dihapus</p>
                        </div>
                    </label>
                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input type="radio" name="jenis_laporan" value="penyusutan" class="mr-3">
                        <div>
                            <span class="font-medium">Penyusutan</span>
                            <p class="text-xs text-gray-500">Nilai penyusutan asset</p>
                        </div>
                    </label>
                </div>
            </div>
            
            {{-- Filter Periode --}}
            <div class="border-t pt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter Periode</label>
                <div class="flex flex-wrap gap-2">
                    <div class="flex-1 relative">
                        <input type="text" id="date_range" name="date_range" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 bg-white cursor-pointer"
                               placeholder="Pilih rentang tanggal" 
                               value=""
                               readonly
                               style="background-color: white; cursor: pointer;">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <button type="button" onclick="filterLaporan()" 
                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                        Filter
                    </button>
                    <button type="button" onclick="resetFilter()" 
                            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                        Reset
                    </button>
                    <button type="button" onclick="exportLaporan('pdf')" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        PDF
                    </button>
                    <button type="button" onclick="exportLaporan('excel')" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Excel
                    </button>
                </div>
            </div>
        </form>
        
        {{-- Tabel Preview --}}
        <div class="mt-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr id="tableHeader">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Asset</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Asset</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lokasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kondisi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="mt-2">Pilih rentang tanggal dan klik Filter untuk melihat data</p>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot id="tableFooter" class="bg-gray-50 font-bold hidden">
                        <tr>
                            <td colspan="7" class="px-6 py-3 text-right text-sm">Total Data:</td>
                            <td class="px-6 py-3 text-left text-sm" id="totalData">0</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
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
    let dateRangeInput = $('#date_range');
    let pickerInstance = null;
    
    // Initialize date range picker
    dateRangeInput.daterangepicker({
        locale: {
            format: 'DD-MM-YYYY',
            separator: ' - ',
            applyLabel: 'Pilih',
            cancelLabel: 'Batal',
            fromLabel: 'Dari',
            toLabel: 'Sampai',
            customRangeLabel: 'Custom',
            weekLabel: 'W',
            daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
            monthNames: [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ],
            firstDay: 1
        },
        autoUpdateInput: false,
        singleDatePicker: false,
        showDropdowns: true,
        autoApply: false,
        linkedCalendars: false,
        alwaysShowCalendars: true,
        opens: 'center',
        minYear: parseInt(moment().format('YYYY')) - 5,
        maxYear: parseInt(moment().format('YYYY')) + 1
    }, function(start, end) {
        dateRangeInput.val(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
    });
    
    // Mencegah input manual
    dateRangeInput.on('keydown', function(e) {
        e.preventDefault();
        return false;
    });
    
    dateRangeInput.on('keypress', function(e) {
        e.preventDefault();
        return false;
    });
    
    dateRangeInput.on('paste', function(e) {
        e.preventDefault();
        return false;
    });
    
    // Radio button change handler
    $('input[name="jenis_laporan"]').on('change', function() {
        updateTableHeader($(this).val());
    });
});

function updateTableHeader(jenis) {
    const thead = document.getElementById('tableHeader');
    if (!thead) return;
    
    let headers = [];
    switch(jenis) {
        case 'asset':
            headers = ['No', 'Kode Asset', 'Nama Asset', 'Kategori', 'Lokasi', 'Status', 'Kondisi', 'Tanggal'];
            break;
        case 'kerusakan':
            headers = ['No', 'Asset', 'Kode Asset', 'Deskripsi', 'Tanggal Laporan', 'Status', 'Lokasi', 'Pelapor'];
            break;
        case 'penghapusan':
            headers = ['No', 'Asset', 'Kode Asset', 'Tanggal Penghapusan', 'Alasan', 'Status', 'Disetujui', '-'];
            break;
        case 'penyusutan':
            headers = ['No', 'Asset', 'Kode Asset', 'Nilai Awal', 'Nilai Akhir', 'Penyusutan/Tahun', 'Metode', 'Masa Manfaat'];
            break;
        default:
            headers = ['No', 'Kode Asset', 'Nama Asset', 'Kategori', 'Lokasi', 'Status', 'Kondisi', 'Tanggal'];
    }
    
    thead.innerHTML = '<tr>' + headers.map(h => 
        `<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">${h}</th>`
    ).join('') + '</tr>';
}

function filterLaporan() {
    const dateRange = document.getElementById('date_range').value;
    
    if (!dateRange) {
        alert('Pilih rentang tanggal terlebih dahulu!');
        return;
    }
    
    const form = document.getElementById('laporanForm');
    const formData = new FormData(form);
    const jenisLaporan = document.querySelector('input[name="jenis_laporan"]:checked').value;
    
    // Show loading
    const tableBody = document.getElementById('tableBody');
    tableBody.innerHTML = '<tr><td colspan="8" class="px-6 py-8 text-center"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600"></div><p class="mt-2">Memuat data...</p></td></tr>';
    
    // Update header berdasarkan jenis laporan
    updateTableHeader(jenisLaporan);
    
    fetch('{{ route("laporan.preview") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            date_range: dateRange,
            jenis_laporan: jenisLaporan,
            status: formData.get('status') || '',
            kondisi: formData.get('kondisi') || ''
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            tableBody.innerHTML = `<td><td colspan="8" class="px-6 py-8 text-center text-red-600">${data.error}</td></tr>`;
            return;
        }
        if (data.html) {
            tableBody.innerHTML = data.html;
        } else {
            tableBody.innerHTML = '<tr><td colspan="8" class="px-6 py-8 text-center text-gray-500">Tidak ada data</td></tr>';
        }
        document.getElementById('tableFooter').classList.remove('hidden');
        document.getElementById('totalData').innerText = data.filtered || 0;
    })
    .catch(error => {
        console.error('Error:', error);
        tableBody.innerHTML = '<tr><td colspan="8" class="px-6 py-8 text-center text-red-600">Terjadi kesalahan saat memuat data</td></tr>';
    });
}

function resetFilter() {
    // Reset date range
    const dateRangeInput = document.getElementById('date_range');
    dateRangeInput.value = '';
    
    // Reset form
    const form = document.getElementById('laporanForm');
    form.reset();
    
    // Set radio button asset ke checked
    const assetRadio = document.querySelector('input[name="jenis_laporan"][value="asset"]');
    if (assetRadio) {
        assetRadio.checked = true;
    }
    
    // Reset status dan kondisi selects (kosongkan)
    const statusSelect = document.querySelector('select[name="status"]');
    const kondisiSelect = document.querySelector('select[name="kondisi"]');
    if (statusSelect) statusSelect.value = '';
    if (kondisiSelect) kondisiSelect.value = '';
    
    // Update header ke asset
    updateTableHeader('asset');
    
    // Clear table
    const tableBody = document.getElementById('tableBody');
    tableBody.innerHTML = '<tr><td colspan="8" class="px-6 py-8 text-center text-gray-500">Pilih rentang tanggal dan klik Filter untuk melihat数据</td></tr>';
    
    // Sembunyikan footer
    const tableFooter = document.getElementById('tableFooter');
    if (tableFooter) {
        tableFooter.classList.add('hidden');
    }
    
    // Reset total data
    const totalData = document.getElementById('totalData');
    if (totalData) {
        totalData.innerText = '0';
    }
    
    console.log('Reset completed');
}

function exportLaporan(type) {
    const dateRange = document.getElementById('date_range').value;
    
    if (!dateRange) {
        alert('Pilih rentang tanggal terlebih dahulu!');
        return;
    }
    
    const form = document.getElementById('laporanForm');
    const formData = new FormData(form);
    const jenisLaporan = document.querySelector('input[name="jenis_laporan"]:checked').value;
    
    const params = new URLSearchParams();
    params.append('date_range', dateRange);
    params.append('jenis_laporan', jenisLaporan);
    params.append('status', formData.get('status') || '');
    params.append('kondisi', formData.get('kondisi') || '');
    
    let url = type === 'pdf' ? '{{ route("laporan.export-pdf") }}' : '{{ route("laporan.export-excel") }}';
    window.location.href = url + '?' + params.toString();
}
</script>
@endpush
@endsection