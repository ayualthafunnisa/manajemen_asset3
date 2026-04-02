@extends('layouts.app')

@section('title', 'Lokasi Asset - Jobie')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-neutral-900">Manajemen Lokasi Asset</h1>
        <p class="mt-1 text-neutral-600">Kelola data lokasi penyimpanan asset</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('lokasi.create') }}"
           class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Lokasi
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
                    <input type="text" id="search" placeholder="Cari lokasi..."
                           class="pl-10 pr-4 py-2 w-full border border-neutral-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <select id="jenisFilter" class="border border-neutral-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500">
                        <option value="">Semua Jenis</option>
                        <option value="ruangan">Ruangan</option>
                        <option value="gudang">Gudang</option>
                        <option value="laboratorium">Laboratorium</option>
                        <option value="lapangan">Lapangan</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                    <select id="statusFilter" class="border border-neutral-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500">
                        <option value="">Semua Status</option>
                        <option value="active">Active</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="tidak_aktif">Tidak Aktif</option>
                        <option value="renovasi">Renovasi</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Nama Lokasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Penanggung Jawab</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200" id="tableBody">
                    @forelse ($lokasis as $lokasi)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-mono font-medium text-neutral-900">{{ $lokasi->KodeLokasi }}</div>
                            <div class="text-neutral-500 text-xs">ID: {{ $lokasi->LokasiID }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-neutral-900">{{ $lokasi->NamaLokasi }}</div>
                            <div class="text-neutral-500 text-xs">{{ $lokasi->instansi->NamaSekolah ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($lokasi->PenanggungJawab)
                                <div class="text-sm font-medium text-neutral-900">{{ $lokasi->PenanggungJawab }}</div>
                                <div class="text-neutral-500 text-xs">{{ $lokasi->TeleponPenanggungJawab ?? '-' }}</div>
                            @else
                                <span class="text-neutral-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $jenisColor = [
                                    'ruangan'      => 'bg-blue-100 text-blue-800',
                                    'gudang'       => 'bg-yellow-100 text-yellow-800',
                                    'laboratorium' => 'bg-purple-100 text-purple-800',
                                    'lapangan'     => 'bg-green-100 text-green-800',
                                    'lainnya'      => 'bg-gray-100 text-gray-800',
                                ];
                            @endphp
                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full inline-flex items-center {{ $jenisColor[$lokasi->JenisLokasi] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($lokasi->JenisLokasi) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColor = [
                                    'active'      => 'bg-green-100 text-green-800',
                                    'maintenance' => 'bg-yellow-100 text-yellow-800',
                                    'renovasi'    => 'bg-blue-100 text-blue-800',
                                    'tidak_aktif' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full inline-flex items-center {{ $statusColor[$lokasi->Status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $lokasi->Status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('lokasi.edit', $lokasi->LokasiID) }}"
                                   title="Edit" class="text-purple-600 hover:text-purple-900 transition duration-150">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('lokasi.destroy', $lokasi->LokasiID) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus lokasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus" class="text-red-400 hover:text-red-700 transition duration-150">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-neutral-900">Tidak ada lokasi</h3>
                            <p class="mt-1 text-sm text-neutral-500">Mulai dengan menambahkan lokasi baru.</p>
                            <div class="mt-6">
                                <a href="{{ route('lokasi.create') }}"
                                   class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Tambah Lokasi
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($lokasis->hasPages())
        <div class="px-6 py-4 border-t border-neutral-200 bg-gray-50">
            {{ $lokasis->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput  = document.getElementById('search');
    const jenisFilter  = document.getElementById('jenisFilter');
    const statusFilter = document.getElementById('statusFilter');

    function applyFilters() {
        const searchTerm  = searchInput.value.toLowerCase();
        const jenisValue  = jenisFilter.value;
        const statusValue = statusFilter.value;

        document.querySelectorAll('#tableBody tr').forEach(row => {
            if (row.querySelector('td[colspan]')) return;
            const text       = row.textContent.toLowerCase();
            const jenisCell  = row.querySelector('td:nth-child(4) span');
            const statusCell = row.querySelector('td:nth-child(5) span');

            const matchSearch = !searchTerm  || text.includes(searchTerm);
            const matchJenis  = !jenisValue  || (jenisCell  && jenisCell.textContent.trim().toLowerCase() === jenisValue);
            const matchStatus = !statusValue || (statusCell && statusCell.textContent.trim().toLowerCase().replace(' ', '_') === statusValue);

            row.style.display = (matchSearch && matchJenis && matchStatus) ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', applyFilters);
    jenisFilter.addEventListener('change', applyFilters);
    statusFilter.addEventListener('change', applyFilters);
});
</script>
@endpush