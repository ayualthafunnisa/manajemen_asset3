@php
    $items = $data['items'] ?? collect();
    $jenis = $jenisLaporan ?? $data['jenis'] ?? 'asset';
    $startDate = $data['start_date'] ?? null;
    $endDate   = $data['end_date'] ?? null;
@endphp

<div class="bg-white rounded-lg">
    {{-- Summary bar --}}
    <div class="mb-4 px-4 py-3 bg-gray-50 rounded-lg border border-gray-100 flex flex-wrap justify-between items-center gap-2">
        <div class="flex items-center gap-4 text-sm">
            <span class="text-gray-500">
                Menampilkan <span class="font-semibold text-gray-800">{{ $data['filtered'] ?? 0 }}</span>
                dari <span class="font-semibold text-gray-800">{{ $data['total'] ?? 0 }}</span> data
            </span>
            @php
                $jenisLabel = [
                    'asset'       => 'Data Asset',
                    'kerusakan'   => 'Kerusakan Asset',
                    'penghapusan' => 'Penghapusan Asset',
                    'penyusutan'  => 'Penyusutan Asset',
                ][$jenis] ?? 'Laporan';
            @endphp
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                {{ $jenisLabel }}
            </span>
        </div>
        @if($startDate && $endDate)
        <div class="text-xs text-gray-500">
            Periode:
            <span class="font-medium text-gray-700">
                {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} —
                {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
            </span>
        </div>
        @endif
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200 text-sm">

            {{-- ======================== DATA ASSET ======================== --}}
            @if($jenis === 'asset')
            <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold tracking-wider">
                <tr>
                    <th class="px-4 py-3 text-left w-10">No</th>
                    <th class="px-4 py-3 text-left">Kode Asset</th>
                    <th class="px-4 py-3 text-left">Nama Asset</th>
                    <th class="px-4 py-3 text-left">Kategori</th>
                    <th class="px-4 py-3 text-left">Lokasi</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Kondisi</th>
                    <th class="px-4 py-3 text-left">Tanggal Masuk</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($items as $i => $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 text-gray-400">{{ $i + 1 }}</td>
                    <td class="px-4 py-3 font-mono text-xs text-gray-700">{{ $item->kode_asset ?? '-' }}</td>
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $item->nama_asset ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $item->kategori->NamaKategori ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $item->lokasi->NamaLokasi ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @php $st = strtolower($item->status_asset ?? ''); @endphp
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $st === 'aktif'    ? 'bg-green-100 text-green-700' :
                              ($st === 'rusak'    ? 'bg-red-100 text-red-700' :
                              ($st === 'perbaikan'? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600')) }}">
                            {{ $item->status_asset ?? '-' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $item->kondisi ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-500 text-xs">
                        {{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') : '-' }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-4 py-10 text-center text-gray-400 text-sm">Tidak ada data asset untuk periode ini.</td></tr>
                @endforelse
            </tbody>

            {{-- ======================== KERUSAKAN ======================== --}}
            @elseif($jenis === 'kerusakan')
            <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold tracking-wider">
                <tr>
                    <th class="px-4 py-3 text-left w-10">No</th>
                    <th class="px-4 py-3 text-left">Kode Laporan</th>
                    <th class="px-4 py-3 text-left">Nama Asset</th>
                    <th class="px-4 py-3 text-left">Jenis Kerusakan</th>
                    <th class="px-4 py-3 text-left">Tingkat</th>
                    <th class="px-4 py-3 text-left">Lokasi</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Tanggal Laporan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($items as $i => $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 text-gray-400">{{ $i + 1 }}</td>
                    <td class="px-4 py-3 font-mono text-xs text-gray-700">{{ $item->kode_laporan ?? '-' }}</td>
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $item->asset->nama_asset ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $item->jenis_kerusakan ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @php $tk = strtolower($item->tingkat_kerusakan ?? ''); @endphp
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $tk === 'kritis' ? 'bg-red-100 text-red-700' :
                              ($tk === 'berat'  ? 'bg-orange-100 text-orange-700' :
                              ($tk === 'sedang' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600')) }}">
                            {{ ucfirst($item->tingkat_kerusakan ?? '-') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $item->lokasi->NamaLokasi ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @php $sp = strtolower($item->status_perbaikan ?? ''); @endphp
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $sp === 'selesai'    ? 'bg-green-100 text-green-700' :
                              ($sp === 'diproses'   ? 'bg-blue-100 text-blue-700' :
                              ($sp === 'dilaporkan' ? 'bg-yellow-100 text-yellow-700' :
                              ($sp === 'ditolak'    ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600'))) }}">
                            {{ ucfirst($item->status_perbaikan ?? '-') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">
                        {{ $item->tanggal_laporan
                            ? \Carbon\Carbon::parse($item->tanggal_laporan)->format('d/m/Y')
                            : ($item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') : '-') }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-4 py-10 text-center text-gray-400 text-sm">Tidak ada data kerusakan untuk periode ini.</td></tr>
                @endforelse
            </tbody>

            {{-- ======================== PENGHAPUSAN ======================== --}}
            @elseif($jenis === 'penghapusan')
            <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold tracking-wider">
                <tr>
                    <th class="px-4 py-3 text-left w-10">No</th>
                    <th class="px-4 py-3 text-left">Kode Asset</th>
                    <th class="px-4 py-3 text-left">Nama Asset</th>
                    <th class="px-4 py-3 text-left">Kategori</th>
                    <th class="px-4 py-3 text-left">Alasan</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Diajukan Oleh</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($items as $i => $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 text-gray-400">{{ $i + 1 }}</td>
                    <td class="px-4 py-3 font-mono text-xs text-gray-700">{{ $item->asset->kode_asset ?? '-' }}</td>
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $item->asset->nama_asset ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $item->asset->kategori->nama_kategori ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600 max-w-xs">
                        <span class="line-clamp-2">{{ $item->alasan ?? $item->keterangan ?? '-' }}</span>
                    </td>
                    <td class="px-4 py-3">
                        @php $sp = strtolower($item->status ?? ''); @endphp
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $sp === 'disetujui' ? 'bg-green-100 text-green-700' :
                              ($sp === 'ditolak'   ? 'bg-red-100 text-red-700' :
                              ($sp === 'pending'   ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600')) }}">
                            {{ ucfirst($item->status ?? '-') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ optional($item->pengaju ?? $item->diajukanOleh)->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-500 text-xs">
                        {{ $item->tanggal_penghapusan
                            ? \Carbon\Carbon::parse($item->tanggal_penghapusan)->format('d/m/Y')
                            : ($item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') : '-') }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-4 py-10 text-center text-gray-400 text-sm">Tidak ada data penghapusan untuk periode ini.</td></tr>
                @endforelse
            </tbody>

            {{-- ======================== PENYUSUTAN ======================== --}}
            @elseif($jenis === 'penyusutan')
            <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold tracking-wider">
                <tr>
                    <th class="px-4 py-3 text-left w-10">No</th>
                    <th class="px-4 py-3 text-left">Kode Asset</th>
                    <th class="px-4 py-3 text-left">Nama Asset</th>
                    <th class="px-4 py-3 text-right">Nilai Awal (Rp)</th>
                    <th class="px-4 py-3 text-right">Nilai Buku (Rp)</th>
                    <th class="px-4 py-3 text-right">Penyusutan/Tahun (Rp)</th>
                    <th class="px-4 py-3 text-left">Metode</th>
                    <th class="px-4 py-3 text-left">Masa Manfaat</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($items as $i => $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 text-gray-400">{{ $i + 1 }}</td>
                    <td class="px-4 py-3 font-mono text-xs text-gray-700">{{ $item->asset->kode_asset ?? '-' }}</td>
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $item->asset->nama_asset ?? '-' }}</td>
                    <td class="px-4 py-3 text-right text-gray-700">
                        {{ $item->nilai_awal ? 'Rp ' . number_format($item->nilai_awal, 0, ',', '.') : '-' }}
                    </td>
                    <td class="px-4 py-3 text-right text-gray-700">
                        @php $nb = $item->nilai_buku ?? $item->nilai_akhir ?? null; @endphp
                        {{ $nb ? 'Rp ' . number_format($nb, 0, ',', '.') : '-' }}
                    </td>
                    <td class="px-4 py-3 text-right font-medium text-red-600">
                        @php $np = $item->penyusutan_per_tahun ?? $item->nilai_penyusutan ?? null; @endphp
                        {{ $np ? 'Rp ' . number_format($np, 0, ',', '.') : '-' }}
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $item->metode ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $item->masa_manfaat ? $item->masa_manfaat . ' Tahun' : '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-4 py-10 text-center text-gray-400 text-sm">Tidak ada data penyusutan untuk periode ini.</td></tr>
                @endforelse
            </tbody>
            @endif

        </table>
    </div>
</div>