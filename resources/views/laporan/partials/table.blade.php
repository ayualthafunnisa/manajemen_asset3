@php
    $items = $data['items'] ?? collect();
    $jenis = $jenisLaporan ?? $data['jenis'] ?? 'asset';
    $startDate = isset($data['start_date']) ? $data['start_date'] : null;
    $endDate = isset($data['end_date']) ? $data['end_date'] : null;
@endphp

<div class="bg-white rounded-lg">
    <div class="mb-4 p-3 bg-gray-50 rounded-lg flex justify-between items-center">
        <div>
            <span class="text-sm text-gray-600">Total Data: </span>
            <span class="font-semibold text-gray-900">{{ $data['filtered'] ?? 0 }}</span>
            <span class="text-sm text-gray-500"> dari {{ $data['total'] ?? 0 }}</span>
        </div>
        @if($startDate && $endDate)
        <div class="text-sm text-gray-500">
            Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - 
            {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
        </div>
        @endif
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                @if($jenis == 'asset')
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Asset</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Asset</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lokasi</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Merk</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Serial Number</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kondisi</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Dibuat</th>
                </tr>
                @elseif($jenis == 'kerusakan')
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asset</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Asset</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Laporan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lokasi</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelapor</th>
                </tr>
                @elseif($jenis == 'penghapusan')
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asset</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Asset</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Penghapusan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alasan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Disetujui Oleh</th>
                </tr>
                @elseif($jenis == 'penyusutan')
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asset</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Asset</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai Awal</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai Akhir</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penyusutan/Tahun</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Metode</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Mulai</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Masa Manfaat</th>
                </tr>
                @endif
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($items as $index => $item)
                <tr class="hover:bg-gray-50">
                    @if($jenis == 'asset')
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-4 py-3 text-sm font-mono text-gray-900">{{ $item->kode_asset ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->nama_asset ?? '-' }}</td>
                    {{-- PERBAIKAN: Gunakan snake_case untuk nama kolom --}}
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item->kategori->nama_kategori ?? $item->kategori->NamaKategori ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item->lokasi->nama_lokasi ?? $item->lokasi->NamaLokasi ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item->merk ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm font-mono text-gray-600">{{ $item->serial_number ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex px-2 py-1 text-xs rounded-full
                            @if($item->kondisi == 'baik' || $item->kondisi == 'Baik') bg-green-100 text-green-800
                            @elseif($item->kondisi == 'rusak_ringan' || $item->kondisi == 'Rusak Ringan') bg-yellow-100 text-yellow-800
                            @elseif($item->kondisi == 'rusak_berat' || $item->kondisi == 'Rusak Berat') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $item->kondisi ?? '-')) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex px-2 py-1 text-xs rounded-full
                            @if($item->status_asset == 'aktif' || $item->status_asset == 'Aktif') bg-green-100 text-green-800
                            @elseif($item->status_asset == 'dipinjam' || $item->status_asset == 'Dipinjam') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $item->status_asset ?? '-')) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">
                        {{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}
                    </td>
                    
                    @elseif($jenis == 'kerusakan')
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->asset->nama_asset ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm font-mono text-gray-600">{{ $item->asset->kode_asset ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ Str::limit($item->deskripsi_kerusakan ?? $item->deskripsi ?? '-', 50) }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ isset($item->tanggal_laporan) ? \Carbon\Carbon::parse($item->tanggal_laporan)->format('d/m/Y') : '-' }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex px-2 py-1 text-xs rounded-full
                            @if(($item->status_perbaikan ?? $item->status) == 'selesai' || ($item->status_perbaikan ?? $item->status) == 'Selesai') bg-green-100 text-green-800
                            @elseif(($item->status_perbaikan ?? $item->status) == 'proses' || ($item->status_perbaikan ?? $item->status) == 'Proses') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($item->status_perbaikan ?? $item->status ?? '-') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item->lokasi_kerusakan ?? $item->lokasi ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item->user->name ?? $item->pelapor ?? '-' }}</td>
                    
                    @elseif($jenis == 'penghapusan')
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->asset->nama_asset ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm font-mono text-gray-600">{{ $item->asset->kode_asset ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ isset($item->tanggal_penghapusan) ? \Carbon\Carbon::parse($item->tanggal_penghapusan)->format('d/m/Y') : '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ Str::limit($item->alasan_penghapusan ?? $item->alasan ?? '-', 50) }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex px-2 py-1 text-xs rounded-full
                            @if(($item->status_penghapusan ?? $item->status) == 'disetujui' || ($item->status_penghapusan ?? $item->status) == 'Disetujui') bg-green-100 text-green-800
                            @elseif(($item->status_penghapusan ?? $item->status) == 'pending' || ($item->status_penghapusan ?? $item->status) == 'Pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($item->status_penghapusan ?? $item->status ?? '-') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item->approved_by ?? $item->disetujui_oleh ?? '-' }}</td>
                    
                    @elseif($jenis == 'penyusutan')
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->asset->nama_asset ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm font-mono text-gray-600">{{ $item->asset->kode_asset ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">Rp {{ number_format($item->nilai_awal ?? 0, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">Rp {{ number_format($item->nilai_akhir ?? 0, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item->penyusutan_per_tahun ?? $item->persentase ?? 0 }}%</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item->metode_penyusutan ?? $item->metode ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ isset($item->tanggal_mulai) ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') : '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item->masa_manfaat ?? '-' }} tahun</td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-4 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p>Tidak ada data untuk periode yang dipilih</p>
                            <p class="text-sm text-gray-400 mt-1">
                                @if(isset($data['start_date']) && isset($data['end_date']))
                                    Periode: {{ \Carbon\Carbon::parse($data['start_date'])->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($data['end_date'])->format('d/m/Y') }}
                                @endif
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>