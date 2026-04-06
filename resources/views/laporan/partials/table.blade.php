{{-- resources/views/laporan/partials/table.blade.php --}}
<div class="bg-white rounded-lg">
    <div class="mb-4 p-3 bg-gray-50 rounded-lg flex justify-between items-center">
        <div>
            <span class="text-sm text-gray-600">Total Data: </span>
            <span class="font-semibold text-gray-900">{{ $data['filtered'] }}</span>
            <span class="text-sm text-gray-500"> dari {{ $data['total'] }}</span>
        </div>
        @if($request->tanggal_mulai && $request->tanggal_akhir)
        <div class="text-sm text-gray-500">
            Periode: {{ \Carbon\Carbon::parse($request->tanggal_mulai)->format('d/m/Y') }} - 
            {{ \Carbon\Carbon::parse($request->tanggal_akhir)->format('d/m/Y') }}
        </div>
        @endif
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                @if($data['jenis'] == 'asset')
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
                @elseif($data['jenis'] == 'kerusakan')
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
                @elseif($data['jenis'] == 'penghapusan')
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asset</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Asset</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Penghapusan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alasan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Disetujui Oleh</th>
                </tr>
                @elseif($data['jenis'] == 'penyusutan')
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
                @forelse($data['items'] as $index => $item)
                <tr class="hover:bg-gray-50">
                    @if($data['jenis'] == 'asset')
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-4 py-3 text-sm font-mono text-gray-900">{{ $item->kode_asset }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->nama_asset }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item->kategori->NamaKategori ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item->lokasi->NamaLokasi ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item->merk ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm font-mono text-gray-600">{{ $item->serial_number ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex px-2 py-1 text-xs rounded-full
                            @if($item->kondisi == 'baik') bg-green-100 text-green-800
                            @elseif($item->kondisi == 'rusak_ringan') bg-yellow-100 text-yellow-800
                            @elseif($item->kondisi == 'rusak_berat') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $item->kondisi)) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex px-2 py-1 text-xs rounded-full
                            @if($item->status_asset == 'aktif') bg-green-100 text-green-800
                            @elseif($item->status_asset == 'dipinjam') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $item->status_asset)) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $item->created_at->format('d/m/Y') }}</td>
                    
                    @elseif($data['jenis'] == 'kerusakan')
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->asset->nama_asset ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm font-mono text-gray-600">{{ $item->asset->kode_asset ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ Str::limit($item->deskripsi_kerusakan, 50) }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex px-2 py-1 text-xs rounded-full
                            @if($item->status_perbaikan == 'selesai') bg-green-100 text-green-800
                            @elseif($item->status_perbaikan == 'proses') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($item->status_perbaikan) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item->lokasi_kerusakan ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item->user->name ?? '-' }}</td>
                    
                    @elseif($data['jenis'] == 'penghapusan')
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->asset->nama_asset ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm font-mono text-gray-600">{{ $item->asset->kode_asset ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ \Carbon\Carbon::parse($item->tanggal_penghapusan)->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ Str::limit($item->alasan_penghapusan, 50) }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex px-2 py-1 text-xs rounded-full
                            @if($item->status_penghapusan == 'disetujui') bg-green-100 text-green-800
                            @elseif($item->status_penghapusan == 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($item->status_penghapusan) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item->approved_by ?? '-' }}</td>
                    
                    @elseif($data['jenis'] == 'penyusutan')
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->asset->nama_asset ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm font-mono text-gray-600">{{ $item->asset->kode_asset ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">Rp {{ number_format($item->nilai_awal, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">Rp {{ number_format($item->nilai_akhir, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item->penyusutan_per_tahun }}%</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item->metode_penyusutan }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item->masa_manfaat }} tahun</td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-4 py-8 text-center text-gray-500">
                        Tidak ada data untuk periode yang dipilih
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>