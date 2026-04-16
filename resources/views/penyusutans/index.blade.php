@extends('layouts.app')

@section('title', 'Data Penyusutan Aset - Jobie')

@if(!in_array(auth()->user()->role, ['super_admin']))
@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-neutral-900">Data Penyusutan Aset</h1>
        <p class="mt-1 text-neutral-600">Kelola perhitungan penyusutan aset sekolah</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('penyusutan.create') }}"
           class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Hitung Penyusutan Baru
        </a>
    </div>
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
                    <input type="text" id="search" placeholder="Cari aset, instansi, atau periode..."
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
                    <select id="metodeFilter" class="border border-neutral-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500">
                        <option value="">Semua Metode</option>
                        <option value="garis_lurus">Garis Lurus</option>
                        <option value="saldo_menurun">Saldo Menurun</option>
                    </select>
                    <select id="tahunFilter" class="border border-neutral-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500">
                        <option value="">Semua Tahun</option>
                        @for($i = date('Y'); $i >= date('Y')-5; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-6 bg-neutral-50 border-b border-neutral-200">
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <p class="text-sm text-neutral-600">Total Nilai Awal</p>
                <p class="text-xl font-bold text-neutral-900">Rp {{ number_format($penyusutans->sum('nilai_awal'), 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-orange-400">
                <p class="text-sm text-neutral-600">Total Penyusutan</p>
                <p class="text-xl font-bold text-orange-600">Rp {{ number_format($penyusutans->sum('nilai_penyusutan'), 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-green-400">
                <p class="text-sm text-neutral-600">Total Nilai Akhir</p>
                <p class="text-xl font-bold text-green-600">Rp {{ number_format($penyusutans->sum('nilai_akhir'), 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-blue-400">
                <p class="text-sm text-neutral-600">Total Akumulasi</p>
                <p class="text-xl font-bold text-blue-600">Rp {{ number_format($penyusutans->sum('akumulasi_penyusutan'), 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Instansi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Aset</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Nilai Awal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Penyusutan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Nilai Akhir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Metode</th>
                        @if(!in_array(auth()->user()->role, ['super_admin']))
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200" id="tableBody">
                    @forelse ($penyusutans as $index => $penyusutan)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                            {{ $penyusutans->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-neutral-900">{{ $penyusutan->instansi->NamaSekolah ?? '-' }}</div>
                            <div class="text-neutral-500 text-xs">{{ $penyusutan->instansi->KodeInstansi ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-neutral-900">{{ $penyusutan->asset->nama_asset ?? '-' }}</div>
                            <div class="text-neutral-500 text-xs font-mono">{{ $penyusutan->asset->kode_asset ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-neutral-900">{{ $penyusutan->tahun }}</div>
                            <div class="text-neutral-500 text-xs">
                                {{ $penyusutan->bulan
                                    ? \Carbon\Carbon::create()->month($penyusutan->bulan)->format('F')
                                    : 'Tahunan' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900">
                            Rp {{ number_format($penyusutan->nilai_awal, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-orange-600 font-medium">
                            Rp {{ number_format($penyusutan->nilai_penyusutan, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">
                            Rp {{ number_format($penyusutan->nilai_akhir, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full inline-flex items-center
                                {{ $penyusutan->metode == 'garis_lurus'
                                    ? 'bg-blue-100 text-blue-800'
                                    : 'bg-purple-100 text-purple-800' }}">
                                {{ $penyusutan->metode == 'garis_lurus' ? 'Garis Lurus' : 'Saldo Menurun' }}
                            </span>

                            {{-- FIX: tampilkan tarif yang relevan --}}
                            <div class="text-xs text-neutral-400 mt-0.5">
                                @if($penyusutan->metode == 'garis_lurus' && $penyusutan->asset)
                                    {{ round(100 / $penyusutan->asset->umur_ekonomis, 2) }}%/thn
                                @elseif($penyusutan->metode == 'saldo_menurun' && $penyusutan->persentase_penyusutan !== null)
                                    {{ $penyusutan->persentase_penyusutan }}%/thn
                                @endif
                            </div>
                        </td>
                        @if(!in_array(auth()->user()->role, ['super_admin']))
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('penyusutan.show', $penyusutan->penyusutanID) }}"
                                   title="Detail"
                                   class="text-purple-600 hover:text-purple-900 transition duration-150">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                @can('delete-penyusutan')
                                <form action="{{ route('penyusutan.destroy', $penyusutan->penyusutanID) }}"
                                      method="POST" class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data penyusutan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus"
                                            class="text-red-400 hover:text-red-700 transition duration-150">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-neutral-900">Belum ada data penyusutan</h3>
                            <p class="mt-1 text-sm text-neutral-500">Mulai dengan menghitung penyusutan aset baru.</p>
                            <div class="mt-6">
                                <a href="{{ route('penyusutan.create') }}"
                                   class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Hitung Penyusutan Baru
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($penyusutans->hasPages())
        <div class="px-6 py-4 border-t border-neutral-200 bg-gray-50">
            {{ $penyusutans->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput  = document.getElementById('search');
    const metodeFilter = document.getElementById('metodeFilter');
    const tahunFilter  = document.getElementById('tahunFilter');

    function applyFilters() {
        const searchTerm  = searchInput.value.toLowerCase();
        const metodeValue = metodeFilter.value;
        const tahunValue  = tahunFilter.value;

        document.querySelectorAll('#tableBody tr').forEach(row => {
            if (row.querySelector('td[colspan]')) return;

            const text       = row.textContent.toLowerCase();
            const metodeCell = row.querySelector('td:nth-child(8) span');
            const tahunCell  = row.querySelector('td:nth-child(4) .font-medium');

            const matchSearch = !searchTerm  || text.includes(searchTerm);
            const matchMetode = !metodeValue || (metodeCell && metodeCell.textContent.toLowerCase().includes(metodeValue.replace('_', ' ')));
            const matchTahun  = !tahunValue  || (tahunCell  && tahunCell.textContent.trim() === tahunValue);

            row.style.display = (matchSearch && matchMetode && matchTahun) ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', applyFilters);
    metodeFilter.addEventListener('change', applyFilters);
    tahunFilter.addEventListener('change', applyFilters);

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
</script>
@endpush