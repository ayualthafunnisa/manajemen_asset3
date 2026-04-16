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
        $query = Kerusakan::with(['asset', 'lokasi', 'perbaikan'])
            ->where('status_perbaikan', '!=', 'selesai')
            ->where('status_perbaikan', '!=', 'tidak_bisa_diperbaiki');
        
        // Filter search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_laporan', 'like', "%{$search}%")
                  ->orWhereHas('asset', function($asset) use ($search) {
                      $asset->where('nama_asset', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter prioritas
        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }
        
        // Filter jenis kerusakan
        if ($request->filled('jenis')) {
            $query->where('jenis_kerusakan', $request->jenis);
        }
        
        // PRIORITAS SORTING - Yang paling parah di atas
        // Urutan prioritas: total (paling parah) > berat > sedang > ringan
        // Urutan level prioritas: kritis > tinggi > sedang > rendah
        $query->orderByRaw("
            CASE 
                WHEN jenis_kerusakan = 'total' THEN 1
                WHEN jenis_kerusakan = 'berat' THEN 2
                WHEN jenis_kerusakan = 'sedang' THEN 3
                WHEN jenis_kerusakan = 'ringan' THEN 4
                ELSE 5
            END ASC
        ");
        
        // Kemudian urutkan berdasarkan prioritas (kritis paling atas)
        $query->orderByRaw("
            CASE 
                WHEN prioritas = 'kritis' THEN 1
                WHEN prioritas = 'tinggi' THEN 2
                WHEN prioritas = 'sedang' THEN 3
                WHEN prioritas = 'rendah' THEN 4
                ELSE 5
            END ASC
        ");
        
        // Terakhir urutkan berdasarkan tanggal laporan (terbaru)
        $query->orderBy('tanggal_laporan', 'desc');
        
        $keluhanList = $query->paginate(10);
        
        // Summary untuk cards
        $summary = [
            'total' => Kerusakan::where('status_perbaikan', '!=', 'selesai')
                                ->where('status_perbaikan', '!=', 'tidak_bisa_diperbaiki')
                                ->count(),
            'dilaporkan' => Kerusakan::where('status_perbaikan', 'dilaporkan')->count(),
            'diproses' => Kerusakan::where('status_perbaikan', 'diproses')->count(),
            'kritis' => Kerusakan::where('prioritas', 'kritis')
                                 ->where('status_perbaikan', '!=', 'selesai')
                                 ->count(),
            'total_parah' => Kerusakan::whereIn('jenis_kerusakan', ['total', 'berat'])
                                      ->where('status_perbaikan', '!=', 'selesai')
                                      ->count(), // Tambahan untuk kerusakan parah
        ];
        
        // Cek apakah ada kerusakan kritis atau total yang belum ditangani
        $kerusakanKritis = Kerusakan::where('prioritas', 'kritis')
            ->where('status_perbaikan', 'dilaporkan')
            ->first();
            
        if ($kerusakanKritis && !session()->has('kritis_notified')) {
            session()->flash('kritis_warning', true);
            session()->flash('kritis_data', $kerusakanKritis);
            session()->put('kritis_notified', true);
        }
        
        return view('keluhan.index', compact('keluhanList', 'summary'));
    }
    
    // Method untuk mendapatkan notifikasi real-time via AJAX
    public function getPrioritasNotifikasi()
    {
        $kerusakanKritis = Kerusakan::with(['asset', 'lokasi'])
            ->where('prioritas', 'kritis')
            ->where('status_perbaikan', 'dilaporkan')
            ->orderBy('tanggal_laporan', 'desc')
            ->get();
            
        $kerusakanTotal = Kerusakan::with(['asset', 'lokasi'])
            ->where('jenis_kerusakan', 'total')
            ->where('status_perbaikan', 'dilaporkan')
            ->orderBy('tanggal_laporan', 'desc')
            ->get();
            
        $kerusakanBerat = Kerusakan::with(['asset', 'lokasi'])
            ->where('jenis_kerusakan', 'berat')
            ->where('prioritas', 'tinggi')
            ->where('status_perbaikan', 'dilaporkan')
            ->orderBy('tanggal_laporan', 'desc')
            ->get();
            
        return response()->json([
            'kritis' => $kerusakanKritis,
            'total' => $kerusakanTotal,
            'berat' => $kerusakanBerat,
            'total_count' => $kerusakanKritis->count() + $kerusakanTotal->count() + $kerusakanBerat->count()
        ]);
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
        
        // Cek apakah sudah ada perbaikan
        if ($kerusakan->perbaikan) {
            return redirect()->route('keluhan.show', $kerusakanId)
                ->with('error', 'Keluhan ini sudah memiliki laporan perbaikan.');
        }
        
        $validated = $request->validate([
            'kode_perbaikan'      => 'required|unique:perbaikans',
            'mulai_perbaikan'     => 'required|date',
            'estimasi_selesai'    => 'required|date|after_or_equal:mulai_perbaikan', // VALIDASI BARU
            'komponen_diganti'    => 'nullable|string|max:255',
            'biaya_aktual'        => 'nullable|numeric|min:0',
            'status'              => 'required|in:dalam_perbaikan,selesai,tidak_bisa_diperbaiki',
            'selesai_perbaikan'   => 'nullable|date|after_or_equal:mulai_perbaikan',
            'tindakan_perbaikan'  => 'required|string',
            'catatan_perbaikan'   => 'nullable|string',
            'foto_sesudah'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'alasan_tidak_bisa'   => 'required_if:status,tidak_bisa_diperbaiki|nullable|string',
        ]);
        
        // Handle file upload
        $fotoPath = null;
        if ($request->hasFile('foto_sesudah')) {
            $fotoPath = $request->file('foto_sesudah')->store('perbaikan/foto_sesudah', 'public');
        }
        
        // Update status kerusakan jika perbaikan dimulai
        $kerusakan->update([
            'status_perbaikan' => 'diproses'
        ]);
        
        $perbaikan = Perbaikan::create([
            'kerusakanID'          => $kerusakanId,
            'InstansiID'           => $kerusakan->InstansiID,
            'kode_perbaikan'       => $validated['kode_perbaikan'],
            'mulai_perbaikan'      => $validated['mulai_perbaikan'],
            'estimasi_selesai'     => $validated['estimasi_selesai'], // DATA BARU
            'komponen_diganti'     => $validated['komponen_diganti'] ?? null,
            'biaya_aktual'         => $validated['biaya_aktual'] ?? null,
            'status'               => $validated['status'],
            'selesai_perbaikan'    => $validated['selesai_perbaikan'] ?? null,
            'tindakan_perbaikan'   => $validated['tindakan_perbaikan'],
            'catatan_perbaikan'    => $validated['catatan_perbaikan'] ?? null,
            'foto_sesudah'         => $fotoPath,
            'alasan_tidak_bisa'    => $validated['alasan_tidak_bisa'] ?? null,
            'teknisi_id'           => Auth::id(),
            'ditugaskan_oleh'      => Auth::id(),
        ]);
        
        // Kirim notifikasi ke admin sekolah bahwa perbaikan telah dimulai dengan estimasi
        $admins = \App\Models\User::where('InstansiID', $kerusakan->InstansiID)
            ->where('role', 'admin_sekolah')
            ->get();
        
        foreach ($admins as $admin) {
            NotificationHelper::send(
                $admin->id,
                'perbaikan_dimulai',
                'Perbaikan Dimulai: ' . ($kerusakan->asset->nama_asset ?? 'Asset'),
                'Estimasi selesai: ' . date('d/m/Y', strtotime($validated['estimasi_selesai'])) . ' - ' . $validated['tindakan_perbaikan'],
                [
                    'perbaikan_id' => $perbaikan->perbaikanID,
                    'kerusakan_id' => $kerusakanId,
                    'estimasi_selesai' => $validated['estimasi_selesai'],
                ]
            );
        }
        
        return redirect()->route('keluhan.show', $kerusakanId)
            ->with('success', 'Laporan perbaikan berhasil dibuat. Estimasi selesai: ' . date('d/m/Y', strtotime($validated['estimasi_selesai'])));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // UPDATE STATUS — update status perbaikan via form kecil
    // ──────────────────────────────────────────────────────────────────────────

    public function updateStatus(Request $request, $id)
    {
        $perbaikan = Perbaikan::findOrFail($id);
        
        // Cek apakah teknisi yang sedang login adalah teknisi yang ditugaskan
        if ($perbaikan->teknisi_id != Auth::id() && Auth::user()->role != 'super_admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengubah status perbaikan ini.');
        }
        
        $validated = $request->validate([
            'status'            => 'required|in:dalam_perbaikan,selesai,tidak_bisa_diperbaiki',
            'selesai_perbaikan' => 'required_if:status,selesai|nullable|date|after_or_equal:mulai_perbaikan',
            'alasan_tidak_bisa' => 'required_if:status,tidak_bisa_diperbaiki|nullable|string',
            'estimasi_selesai'  => 'nullable|date|after_or_equal:mulai_perbaikan', // BISA UPDATE ESTIMASI
        ]);
        
        $updateData = [
            'status' => $validated['status'],
        ];
        
        // Jika update estimasi selesai
        if ($request->has('estimasi_selesai') && $request->estimasi_selesai) {
            $updateData['estimasi_selesai'] = $validated['estimasi_selesai'];
        }
        
        if ($validated['status'] == 'selesai') {
            $updateData['selesai_perbaikan'] = $validated['selesai_perbaikan'] ?? now();
            
            // Update status kerusakan
            $perbaikan->kerusakan->update([
                'status_perbaikan' => 'selesai'
            ]);
            
            // Kirim notifikasi bahwa perbaikan selesai
            $pelapor = $perbaikan->kerusakan->pelapor;
            if ($pelapor) {
                NotificationHelper::send(
                    $pelapor->id,
                    'perbaikan_selesai',
                    'Perbaikan Selesai: ' . ($perbaikan->kerusakan->asset->nama_asset ?? 'Asset'),
                    'Perbaikan telah selesai dilakukan.',
                    ['perbaikan_id' => $perbaikan->perbaikanID]
                );
            }
            
        } elseif ($validated['status'] == 'tidak_bisa_diperbaiki') {
            $updateData['alasan_tidak_bisa'] = $validated['alasan_tidak_bisa'];
            
            // Update status kerusakan
            $perbaikan->kerusakan->update([
                'status_perbaikan' => 'tidak_bisa_diperbaiki'
            ]);
            
        } elseif ($validated['status'] == 'dalam_perbaikan') {
            // Jika ada update estimasi, kirim notifikasi
            if ($request->has('estimasi_selesai') && $request->estimasi_selesai) {
                $admins = \App\Models\User::where('InstansiID', $perbaikan->InstansiID)
                    ->where('role', 'admin_sekolah')
                    ->get();
                
                foreach ($admins as $admin) {
                    NotificationHelper::send(
                        $admin->id,
                        'estimasi_diperbarui',
                        'Estimasi Perbaikan Diperbarui: ' . ($perbaikan->kerusakan->asset->nama_asset ?? 'Asset'),
                        'Estimasi baru: ' . date('d/m/Y', strtotime($validated['estimasi_selesai'])),
                        ['perbaikan_id' => $perbaikan->perbaikanID]
                    );
                }
            }
        }
        
        $perbaikan->update($updateData);
        
        $message = 'Status perbaikan berhasil diperbarui';
        if ($request->has('estimasi_selesai') && $request->estimasi_selesai) {
            $message .= ' dengan estimasi selesai: ' . date('d/m/Y', strtotime($validated['estimasi_selesai']));
        }
        
        return redirect()->back()->with('success', $message);
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