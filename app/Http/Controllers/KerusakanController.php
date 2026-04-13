<?php

namespace App\Http\Controllers;

use App\Models\Kerusakan;
use App\Models\Asset;
use App\Models\LokasiAsset;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;

class KerusakanController extends Controller
{
    /**
     * Mapping jenis kerusakan → tingkat kerusakan (%)
     * Dipakai di store() dan update() agar konsisten.
     */
    private const TINGKAT_MAP = [
        'ringan' => 25,
        'sedang' => 50,
        'berat'  => 75,
        'total'  => 100,
    ];

    // ──────────────────────────────────────────────────────────────────────────
    // INDEX
    // ──────────────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Kerusakan::with(['asset', 'lokasi', 'pelapor'])
        ->whereIn('status_perbaikan', ['dilaporkan', 'diproses']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_laporan', 'LIKE', "%{$search}%")
                  ->orWhereHas('asset', function ($a) use ($search) {
                      $a->where('nama_asset', 'LIKE', "%{$search}%")
                        ->orWhere('kode_asset', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status_perbaikan', $request->status);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis_kerusakan', $request->jenis);
        }

        $kerusakans = $query->orderByDesc('created_at')->paginate(15);

        // Summary (global scope sudah aktif, data terisolasi per instansi)
        $base    = Kerusakan::query();
        $summary = [
            'total'      => (clone $base)->count(),
            'dilaporkan' => (clone $base)->where('status_perbaikan', 'dilaporkan')->count(),
            'diproses'   => (clone $base)->where('status_perbaikan', 'diproses')->count(),
            'selesai'    => (clone $base)->where('status_perbaikan', 'selesai')->count(),
        ];

        // QR Code kecil untuk setiap baris tabel
        foreach ($kerusakans as $kerusakan) {
            $kerusakan->qrCode = QrCode::size(80)
                ->format('svg')
                ->errorCorrection('M')
                ->generate($kerusakan->kode_laporan);
        }

        return view('kerusakans.index', compact('kerusakans', 'summary'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // CREATE
    // ──────────────────────────────────────────────────────────────────────────

    public function create()
    {
        $assetSedangDiproses = Kerusakan::whereIn('status_perbaikan', ['dilaporkan', 'diproses'])
            ->pluck('assetID')
            ->toArray();

        $assets = Asset::where('status_asset', 'aktif')
            ->whereNotIn('assetID', $assetSedangDiproses)
            ->get();
        $lokasis = LokasiAsset::all();

        $tahun    = date('Y');
        $lastKode = Kerusakan::withoutGlobalScopes()
            ->whereYear('created_at', $tahun)
            ->orderByDesc('kerusakanID')
            ->value('kode_laporan');

        $urutan      = $lastKode ? ((int) substr($lastKode, -3) + 1) : 1;
        $kodeLaporan = 'KR-' . $tahun . '-' . str_pad($urutan, 3, '0', STR_PAD_LEFT);

        return view('kerusakans.create', compact('assets', 'lokasis', 'kodeLaporan'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // STORE
    // ──────────────────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $request->validate([
            'assetID'             => 'required|exists:assets,assetID',
            'LokasiID'            => 'required|exists:lokasi_assets,LokasiID',
            'kode_laporan'        => 'required|unique:kerusakans',
            'tanggal_laporan'     => 'required|date',
            'tanggal_kerusakan'   => 'required|date|before_or_equal:tanggal_laporan',
            'jenis_kerusakan'     => 'required|in:ringan,sedang,berat,total',
            'deskripsi_kerusakan' => 'required|min:10',
            'estimasi_biaya'      => 'nullable|numeric|min:0',
            'foto_kerusakan'      => 'required|image|max:2048',
        ]);

        $tingkat = self::TINGKAT_MAP[$request->jenis_kerusakan];

        // Prioritas ditetapkan otomatis dari tingkat kerusakan
        $prioritas = match (true) {
            $tingkat >= 100 => 'kritis',
            $tingkat >= 75  => 'tinggi',
            $tingkat >= 50  => 'sedang',
            default         => 'rendah',
        };

        $fotoPath = $request->file('foto_kerusakan')->store('kerusakan', 'public');

        Kerusakan::create([
            'assetID'             => $request->assetID,
            'InstansiID'          => Auth::user()->InstansiID,
            'LokasiID'            => $request->LokasiID,
            'kode_laporan'        => $request->kode_laporan,
            'tanggal_laporan'     => $request->tanggal_laporan,
            'tanggal_kerusakan'   => $request->tanggal_kerusakan,
            'jenis_kerusakan'     => $request->jenis_kerusakan,
            'tingkat_kerusakan'   => $tingkat,
            'foto_kerusakan'      => $fotoPath,
            'deskripsi_kerusakan' => $request->deskripsi_kerusakan,
            'prioritas'           => $prioritas,
            'estimasi_biaya'      => $request->estimasi_biaya,
            'status_perbaikan'    => 'dilaporkan',
            'dilaporkan_oleh'     => Auth::id(),
        ]);

        return redirect()->route('kerusakan.index')
            ->with('success', 'Laporan kerusakan berhasil dibuat dan menunggu tindak lanjut admin.');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // SHOW
    // ──────────────────────────────────────────────────────────────────────────

    public function show($id)
    {
        $kerusakan = Kerusakan::with([
            'asset',
            'asset.kategori',
            'lokasi',
            'pelapor',
        ])->findOrFail($id);

        $kerusakan->qrCode = QrCode::size(150)
            ->format('svg')
            ->errorCorrection('M')
            ->generate($kerusakan->kode_laporan);

        return view('kerusakans.show', compact('kerusakan'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // EDIT
    // ──────────────────────────────────────────────────────────────────────────

    public function edit(string $id)
    {
        $kerusakan = Kerusakan::findOrFail($id);

        if (Auth::user()->role === 'staf_asset' && $kerusakan->dilaporkan_oleh !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke laporan ini.');
        }

        if ($kerusakan->status_perbaikan !== 'dilaporkan') {
            return redirect()->route('kerusakan.show', $id)
                ->with('error', 'Laporan yang sudah diproses tidak dapat diedit.');
        }

        $assets  = Asset::where('status_asset', 'aktif')->get();
        $lokasis = LokasiAsset::all();

        return view('kerusakans.edit', compact('kerusakan', 'assets', 'lokasis'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // UPDATE
    // ──────────────────────────────────────────────────────────────────────────

    public function update(Request $request, string $id)
    {
        $kerusakan = Kerusakan::findOrFail($id);

        if (Auth::user()->role === 'staf_asset' && $kerusakan->dilaporkan_oleh !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke laporan ini.');
        }

        if ($kerusakan->status_perbaikan !== 'dilaporkan') {
            return redirect()->route('kerusakan.show', $id)
                ->with('error', 'Laporan yang sudah diproses tidak dapat diedit.');
        }

        $request->validate([
            'assetID'             => 'required|exists:assets,assetID',
            'LokasiID'            => 'required|exists:lokasi_assets,LokasiID',
            'tanggal_laporan'     => 'required|date',
            'tanggal_kerusakan'   => 'required|date|before_or_equal:tanggal_laporan',
            'jenis_kerusakan'     => 'required|in:ringan,sedang,berat,total',
            'deskripsi_kerusakan' => 'required|min:10',
            'estimasi_biaya'      => 'nullable|numeric|min:0',
            // Foto boleh tidak diganti saat update
            'foto_kerusakan'      => 'nullable|image|max:2048',
        ]);

        $tingkat = self::TINGKAT_MAP[$request->jenis_kerusakan];

        $prioritas = match (true) {
            $tingkat >= 100 => 'kritis',
            $tingkat >= 75  => 'tinggi',
            $tingkat >= 50  => 'sedang',
            default         => 'rendah',
        };

        $data = [
            'assetID'             => $request->assetID,
            'LokasiID'            => $request->LokasiID,
            'tanggal_laporan'     => $request->tanggal_laporan,
            'tanggal_kerusakan'   => $request->tanggal_kerusakan,
            'jenis_kerusakan'     => $request->jenis_kerusakan,
            'tingkat_kerusakan'   => $tingkat,
            'deskripsi_kerusakan' => $request->deskripsi_kerusakan,
            'prioritas'           => $prioritas,
            'estimasi_biaya'      => $request->estimasi_biaya,
        ];

        // Ganti foto hanya jika ada file baru yang diupload
        if ($request->hasFile('foto_kerusakan')) {
            $data['foto_kerusakan'] = $request->file('foto_kerusakan')->store('kerusakan', 'public');
        }

        $kerusakan->update($data);

        return redirect()->route('kerusakan.index')
            ->with('success', 'Laporan kerusakan berhasil diperbarui.');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // UPDATE STATUS — hanya admin_sekolah
    // Catatan: migration tidak memiliki kolom catatan/biaya/timestamp tambahan.
    // Perubahan status cukup diperbarui di kolom status_perbaikan.
    // ──────────────────────────────────────────────────────────────────────────

    public function updateStatus(Request $request, string $id)
    {
        if (Auth::user()->role !== 'admin_sekolah') {
            abort(403, 'Hanya admin sekolah yang dapat mengubah status perbaikan.');
        }

        $request->validate([
            'status_perbaikan' => 'required|in:diproses,selesai,ditolak',
        ]);

        $kerusakan = Kerusakan::findOrFail($id);

        $alurValid = [
            'dilaporkan' => ['diproses', 'ditolak'],
            'diproses'   => ['selesai', 'ditolak'],
        ];

        $statusSekarang = $kerusakan->status_perbaikan;
        $statusBaru     = $request->status_perbaikan;

        if (! isset($alurValid[$statusSekarang]) || ! in_array($statusBaru, $alurValid[$statusSekarang])) {
            return back()->with('error', "Tidak dapat mengubah status dari '{$statusSekarang}' ke '{$statusBaru}'.");
        }

        $kerusakan->update(['status_perbaikan' => $statusBaru]);

        $pesan = [
            'diproses' => 'Status diubah: aset sedang dalam proses perbaikan.',
            'selesai'  => 'Perbaikan selesai. Status laporan telah ditandai selesai.',
            'ditolak'  => 'Laporan kerusakan telah ditolak.',
        ];

        return back()->with('success', $pesan[$statusBaru]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // DESTROY
    // ──────────────────────────────────────────────────────────────────────────

    public function destroy(string $id)
    {
        $kerusakan = Kerusakan::findOrFail($id);

        if (Auth::user()->role === 'staf_asset' && $kerusakan->dilaporkan_oleh !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke laporan ini.');
        }

        if ($kerusakan->status_perbaikan !== 'dilaporkan') {
            return back()->with('error', 'Hanya laporan dengan status "Dilaporkan" yang dapat dihapus.');
        }

        // Hapus file foto dari storage
        if ($kerusakan->foto_kerusakan) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($kerusakan->foto_kerusakan);
        }

        $kerusakan->delete();

        return back()->with('success', 'Laporan kerusakan berhasil dihapus.');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // DOWNLOAD QR CODE
    // ──────────────────────────────────────────────────────────────────────────

    public function downloadQrCode($id)
    {
        $kerusakan = Kerusakan::findOrFail($id);

        $qrCode = QrCode::format('svg')
            ->size(400)
            ->margin(2)
            ->errorCorrection('M')
            ->generate($kerusakan->kode_laporan);

        return response($qrCode)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="QR-' . $kerusakan->kode_laporan . '.svg"');
    }
}