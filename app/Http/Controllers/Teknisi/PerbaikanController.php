<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\Kerusakan;
use App\Models\Perbaikan;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PerbaikanController extends Controller
{
    // ──────────────────────────────────────────────────────────────────────────
    // INDEX — Daftar keluhan/kerusakan yang perlu ditangani teknisi
    // ──────────────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Kerusakan::with(['asset', 'lokasi', 'pelapor', 'perbaikan'])
            ->whereIn('status_perbaikan', ['dilaporkan', 'diproses']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_laporan', 'LIKE', "%{$search}%")
                  ->orWhereHas('asset', fn($a) => $a->where('nama_asset', 'LIKE', "%{$search}%"));
            });
        }

        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis_kerusakan', $request->jenis);
        }

        $keluhanList = $query->orderByRaw("FIELD(prioritas,'kritis','tinggi','sedang','rendah')")
                             ->orderByDesc('created_at')
                             ->paginate(12);

        $summary = [
            'total'      => Kerusakan::whereIn('status_perbaikan', ['dilaporkan', 'diproses'])->count(),
            'dilaporkan' => Kerusakan::where('status_perbaikan', 'dilaporkan')->count(),
            'diproses'   => Kerusakan::where('status_perbaikan', 'diproses')->count(),
            'kritis'     => Kerusakan::where('prioritas', 'kritis')
                                     ->whereIn('status_perbaikan', ['dilaporkan', 'diproses'])->count(),
        ];

        return view('keluhan.index', compact('keluhanList', 'summary'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // SHOW — Detail keluhan
    // ──────────────────────────────────────────────────────────────────────────

    public function show($id)
    {
        $kerusakan = Kerusakan::with([
            'asset', 'asset.kategori', 'lokasi', 'pelapor', 'perbaikan.teknisi',
        ])->findOrFail($id);

        return view('keluhan.show', compact('kerusakan'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // CREATE — Form laporan perbaikan
    // ──────────────────────────────────────────────────────────────────────────

    public function create($kerusakanId)
    {
        $kerusakan = Kerusakan::with(['asset', 'lokasi'])->findOrFail($kerusakanId);

        // Cegah duplikasi jika sudah ada perbaikan aktif
        $perbaikanAda = Perbaikan::where('kerusakanID', $kerusakanId)
                                  ->whereIn('status', ['menunggu', 'dalam_perbaikan'])
                                  ->exists();
        if ($perbaikanAda) {
            return redirect()->route('keluhan.show', $kerusakanId)
                ->with('error', 'Sudah ada laporan perbaikan aktif untuk keluhan ini.');
        }

        // Generate kode perbaikan
        $tahun       = date('Y');
        $lastKode    = Perbaikan::whereYear('created_at', $tahun)
                                 ->orderByDesc('perbaikanID')
                                 ->value('kode_perbaikan');
        $urutan      = $lastKode ? ((int) substr($lastKode, -3) + 1) : 1;
        $kodePerbaikan = 'PB-' . $tahun . '-' . str_pad($urutan, 3, '0', STR_PAD_LEFT);

        return view('keluhan.create', compact('kerusakan', 'kodePerbaikan'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // STORE — Simpan laporan perbaikan & ubah status kerusakan → diproses
    // ──────────────────────────────────────────────────────────────────────────

    public function store(Request $request, $kerusakanId)
    {
        $kerusakan = Kerusakan::findOrFail($kerusakanId);

        $request->validate([
            'kode_perbaikan'     => 'required|unique:perbaikans',
            'tindakan_perbaikan' => 'required|min:10',
            'catatan_perbaikan'  => 'nullable|string',
            'komponen_diganti'   => 'nullable|string|max:255',
            'biaya_aktual'       => 'nullable|numeric|min:0',
            'foto_sesudah'       => 'nullable|image|max:2048',
            'status'             => 'required|in:dalam_perbaikan,selesai,tidak_bisa_diperbaiki',
            'alasan_tidak_bisa'  => 'required_if:status,tidak_bisa_diperbaiki',
            'mulai_perbaikan'    => 'nullable|date',
            'selesai_perbaikan'  => 'nullable|date|after_or_equal:mulai_perbaikan',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_sesudah')) {
            $fotoPath = $request->file('foto_sesudah')->store('perbaikan', 'public');
        }

        $perbaikan = Perbaikan::create([
            'kerusakanID'        => $kerusakanId,
            'InstansiID'         => Auth::user()->InstansiID,
            'kode_perbaikan'     => $request->kode_perbaikan,
            'tindakan_perbaikan' => $request->tindakan_perbaikan,
            'catatan_perbaikan'  => $request->catatan_perbaikan,
            'komponen_diganti'   => $request->komponen_diganti,
            'biaya_aktual'       => $request->biaya_aktual,
            'foto_sesudah'       => $fotoPath,
            'status'             => $request->status,
            'alasan_tidak_bisa'  => $request->alasan_tidak_bisa,
            'mulai_perbaikan'    => $request->mulai_perbaikan,
            'selesai_perbaikan'  => $request->status === 'selesai' ? ($request->selesai_perbaikan ?? now()) : $request->selesai_perbaikan,
            'teknisi_id'         => Auth::id(),
            'ditugaskan_oleh'    => Auth::id(),
        ]);

        // Sinkronisasi status kerusakan
        $statusKerusakan = match ($request->status) {
            'selesai', 'tidak_bisa_diperbaiki' => 'selesai',
            default                             => 'diproses',
        };
        $kerusakan->update(['status_perbaikan' => $statusKerusakan]);

        // Kirim notifikasi ke admin sekolah jika perbaikan selesai atau tidak bisa diperbaiki
        if (in_array($request->status, ['selesai', 'tidak_bisa_diperbaiki'])) {
            $this->sendNotificationToAdmin($perbaikan);
        }

        $pesan = match ($request->status) {
            'selesai'                => 'Perbaikan selesai! Laporan berhasil disimpan ke riwayat.',
            'tidak_bisa_diperbaiki'  => 'Laporan disimpan. Aset ditandai tidak bisa diperbaiki.',
            default                  => 'Laporan perbaikan disimpan. Status keluhan diubah ke "Diproses".',
        };

        return redirect()->route('riwayat.index')->with('success', $pesan);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // UPDATE STATUS — update status perbaikan via form kecil
    // ──────────────────────────────────────────────────────────────────────────

    public function updateStatus(Request $request, $id)
    {
        $perbaikan = Perbaikan::findOrFail($id);

        // Hanya teknisi yang menangani yang boleh update
        if ($perbaikan->teknisi_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak mengubah perbaikan ini.');
        }

        $request->validate([
            'status'             => 'required|in:dalam_perbaikan,selesai,tidak_bisa_diperbaiki',
            'alasan_tidak_bisa'  => 'required_if:status,tidak_bisa_diperbaiki',
            'selesai_perbaikan'  => 'nullable|date',
        ]);

        $oldStatus = $perbaikan->status;
        $data = ['status' => $request->status];

        if ($request->status === 'selesai') {
            $data['selesai_perbaikan'] = $request->selesai_perbaikan ?? now();
        }
        if ($request->status === 'tidak_bisa_diperbaiki') {
            $data['alasan_tidak_bisa'] = $request->alasan_tidak_bisa;
        }

        $perbaikan->update($data);

        // Sinkron status kerusakan
        if (in_array($request->status, ['selesai', 'tidak_bisa_diperbaiki'])) {
            $perbaikan->kerusakan->update(['status_perbaikan' => 'selesai']);
            
            // Kirim notifikasi ke admin sekolah jika status berubah ke selesai/tidak_bisa
            if ($oldStatus !== $request->status) {
                $this->sendNotificationToAdmin($perbaikan);
            }
            
            return redirect()
                ->route('riwayat.index')
                ->with('success', 'Perbaikan selesai dan masuk ke riwayat.');
        }

        return redirect()
            ->route('keluhan.index')
            ->with('success', 'Status perbaikan diperbarui.');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // RIWAYAT INDEX — Perbaikan yang sudah selesai
    // ──────────────────────────────────────────────────────────────────────────

    public function riwayat(Request $request)
    {
        $query = Perbaikan::with(['kerusakan.asset', 'kerusakan.lokasi', 'teknisi'])
            ->where('teknisi_id', Auth::id())
            ->whereIn('status', ['selesai', 'tidak_bisa_diperbaiki']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_perbaikan', 'LIKE', "%{$search}%")
                  ->orWhereHas('kerusakan.asset', fn($a) => $a->where('nama_asset', 'LIKE', "%{$search}%"))
                  ->orWhereHas('kerusakan', fn($k) => $k->where('kode_laporan', 'LIKE', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('selesai_perbaikan', $request->bulan);
        }

        $riwayatList = $query->orderByDesc('selesai_perbaikan')->paginate(12);

        $summary = [
            'total'               => Perbaikan::where('teknisi_id', Auth::id())->count(),
            'selesai'             => Perbaikan::where('teknisi_id', Auth::id())->where('status', 'selesai')->count(),
            'tidak_bisa'          => Perbaikan::where('teknisi_id', Auth::id())->where('status', 'tidak_bisa_diperbaiki')->count(),
            'total_biaya'         => Perbaikan::where('teknisi_id', Auth::id())->where('status', 'selesai')->sum('biaya_aktual'),
        ];

        return view('riwayat.index', compact('riwayatList', 'summary'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // RIWAYAT SHOW — Detail riwayat perbaikan
    // ──────────────────────────────────────────────────────────────────────────

    public function riwayatShow($id)
    {
        $perbaikan = Perbaikan::with([
            'kerusakan.asset.kategori',
            'kerusakan.lokasi',
            'kerusakan.pelapor',
            'teknisi',
        ])->findOrFail($id);

        // Teknisi hanya bisa lihat miliknya sendiri
        if ($perbaikan->teknisi_id !== Auth::id() && Auth::user()->role !== 'admin_sekolah' && Auth::user()->role !== 'super_admin') {
            abort(403);
        }

        return view('riwayat.show', compact('perbaikan'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // CETAK PDF
    // ──────────────────────────────────────────────────────────────────────────

    public function cetakPdf($id)
    {
        $perbaikan = Perbaikan::with([
            'kerusakan.asset', 'kerusakan.lokasi', 'kerusakan.pelapor', 'teknisi',
        ])->findOrFail($id);

        $pdf = Pdf::loadView('riwayat.pdf', compact('perbaikan'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('Laporan-Perbaikan-' . $perbaikan->kode_perbaikan . '.pdf');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // PRIVATE METHOD — Send notification to admin sekolah
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Send notification to admin sekolah when repair is completed or cannot be repaired
     */
    private function sendNotificationToAdmin($perbaikan)
    {
        try {
            $kerusakan = $perbaikan->kerusakan;
            $assetName = $kerusakan->asset->nama_asset ?? 'Asset';
            $teknisiName = $perbaikan->teknisi->name ?? 'Teknisi';
            
            $isSelesai = $perbaikan->status === 'selesai';
            
            $title = $isSelesai 
                ? "✅ Perbaikan Selesai: {$assetName}"
                : "⚠️ Perbaikan Tidak Bisa: {$assetName}";
            
            $message = $isSelesai
                ? "Perbaikan oleh {$teknisiName} telah selesai. " . ($perbaikan->biaya_aktual ? "Biaya: Rp " . number_format($perbaikan->biaya_aktual, 0, ',', '.') : "")
                : "Perbaikan oleh {$teknisiName} tidak dapat diselesaikan. Alasan: " . substr($perbaikan->alasan_tidak_bisa, 0, 100);
            
            $data = [
                'perbaikan_id' => $perbaikan->perbaikanID,
                'kerusakan_id' => $kerusakan->kerusakanID,
                'kode_perbaikan' => $perbaikan->kode_perbaikan,
                'kode_laporan' => $kerusakan->kode_laporan,
                'asset_name' => $assetName,
                'status' => $perbaikan->status,
                'teknisi_name' => $teknisiName,
                'biaya_aktual' => $perbaikan->biaya_aktual,
                'alasan_tidak_bisa' => $perbaikan->alasan_tidak_bisa
            ];
            
            // Log untuk debugging
            \Log::info('Sending notification to admins for instansi: ' . $perbaikan->InstansiID);
            \Log::info('Notification title: ' . $title);
            
            // Kirim notifikasi ke semua admin sekolah
            $result = NotificationHelper::sendToAdmins(
                $perbaikan->InstansiID,
                $isSelesai ? 'perbaikan_selesai' : 'perbaikan_tidak_bisa',
                $title,
                $message,
                $data,
                $isSelesai ? '✅' : '⚠️'
            );
            
            \Log::info('Notification result: ' . ($result ? 'success' : 'failed'));
            
            return $result;
        } catch (\Exception $e) {
            \Log::error('Failed to send notification: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }
}