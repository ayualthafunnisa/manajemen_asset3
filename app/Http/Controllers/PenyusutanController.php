<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Penyusutan;
use App\Models\Asset;
use App\Models\Instansi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;


class PenyusutanController extends Controller
{
    // ---------------------------------------------------------------
    // INDEX
    // ---------------------------------------------------------------

    public function index(Request $request)
    {
        $query = Penyusutan::with(['asset', 'instansi']);

        // SUPER_ADMIN bisa lihat semua
        if (auth()->user()->role !== 'super_admin') {
            $query->where('InstansiID', Auth::user()->InstansiID);
        }

        // Filter instansi untuk super_admin
        if (auth()->user()->role === 'super_admin' && $request->filled('instansi')) {
            $query->where('InstansiID', $request->instansi);
        }

        // Filter metode
        if ($request->filled('metode')) {
            $query->where('metode', $request->metode);
        }

        // Filter tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('asset', function($a) use ($search) {
                    $a->where('nama_asset', 'LIKE', "%{$search}%")
                      ->orWhere('kode_asset', 'LIKE', "%{$search}%");
                })->orWhereHas('instansi', function($i) use ($search) {
                    $i->where('NamaSekolah', 'LIKE', "%{$search}%");
                });
            });
        }

        $penyusutans = $query->latest()->paginate(10);

        $instansis = Instansi::all(); // untuk filter super_admin

        if (auth()->user()->role === 'super_admin') {
            return view('penyusutans.index', compact('penyusutans', 'instansis'));
        }

        return view('penyusutans.index', compact('penyusutans'));
    }

    // ---------------------------------------------------------------
    // CREATE
    // ---------------------------------------------------------------

    public function create()
    {
        $assets = Asset::all();
        return view('penyusutans.create', compact('assets'));
    }

    // ---------------------------------------------------------------
    // API: ambil nilai_awal aktual untuk preview JS
    // Route: GET /penyusutan/nilai-awal/{assetID}
    // ---------------------------------------------------------------

    public function getNilaiAwal(int $assetID)
    {
        $asset = Asset::findOrFail($assetID);

        // Filter per InstansiID agar tidak ambil data instansi lain
        $last = Penyusutan::where('assetID', $assetID)
            ->where('InstansiID', Auth::user()->InstansiID)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->first();

        $nilai_awal   = $last ? (float) $last->nilai_akhir : (float) $asset->nilai_perolehan;
        $nilai_residu = (float) ($asset->nilai_residu ?? 0);
        $umur         = (int)   $asset->umur_ekonomis;

        $fully_depreciated = $nilai_awal <= $nilai_residu;

        $akumulasi = (float) Penyusutan::where('assetID', $assetID)
            ->where('InstansiID', Auth::user()->InstansiID)
            ->sum('nilai_penyusutan');

        return response()->json([
            'nilai_awal'        => $nilai_awal,
            'nilai_residu'      => $nilai_residu,
            'nilai_perolehan'   => (float) $asset->nilai_perolehan,
            'umur_ekonomis'     => $umur,
            'akumulasi'         => $akumulasi,
            'fully_depreciated' => $fully_depreciated,
        ]);
    }

    // ---------------------------------------------------------------
    // STORE
    // ---------------------------------------------------------------

    public function store(Request $request)
    {
        $request->validate([
            'assetID' => 'required|exists:assets,assetID',
            'tahun'   => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'bulan'   => 'nullable|integer|min:1|max:12',
            'metode'  => 'required|in:garis_lurus,saldo_menurun',
            'persentase_penyusutan' => 'required_if:metode,saldo_menurun|nullable|numeric|min:1|max:100',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $asset = Asset::findOrFail($request->assetID);

        if ($asset->umur_ekonomis <= 0) {
            return back()->withInput()->with('error', 'Umur ekonomis aset tidak valid.');
        }

        // Guard duplikat: gunakan whereNull untuk bulan null
        $duplikatQuery = Penyusutan::where('assetID', $asset->assetID)
            ->where('tahun', $request->tahun);

        if ($request->filled('bulan')) {
            $duplikatQuery->where('bulan', $request->bulan);
        } else {
            $duplikatQuery->whereNull('bulan');
        }

        if ($duplikatQuery->exists()) {
            return back()->withInput()->with('error',
                'Penyusutan untuk aset ini pada periode ' .
                $request->tahun .
                ($request->bulan ? '/' . $request->bulan : '') .
                ' sudah pernah dihitung.'
            );
        }

        // Filter lastPenyusutan per InstansiID
        $lastPenyusutan = Penyusutan::where('assetID', $asset->assetID)
            ->where('InstansiID', Auth::user()->InstansiID)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->first();

        $nilai_awal   = $lastPenyusutan
            ? (float) $lastPenyusutan->nilai_akhir
            : (float) $asset->nilai_perolehan;

        $nilai_residu = (float) ($asset->nilai_residu ?? 0);

        if ($nilai_awal <= $nilai_residu) {
            return back()->withInput()->with('error',
                'Aset ini sudah mencapai nilai residu (fully depreciated). Tidak perlu dihitung ulang.'
            );
        }

        $total_sudah_susut = (float) Penyusutan::where('assetID', $asset->assetID)
            ->where('InstansiID', Auth::user()->InstansiID)
            ->sum('nilai_penyusutan');

        $max_bisa_susut = (float) $asset->nilai_perolehan - $nilai_residu;

        if ($total_sudah_susut >= $max_bisa_susut) {
            return back()->withInput()->with('error',
                'Total penyusutan aset ini sudah mencapai batas maksimum (nilai perolehan dikurangi nilai residu).'
            );
        }

        if ($request->metode === 'garis_lurus') {
            // Garis lurus: beban per periode selalu sama, dihitung dari nilai_perolehan
            $nilai_penyusutan = ((float) $asset->nilai_perolehan - $nilai_residu) / $asset->umur_ekonomis;
        } else {
            // Saldo menurun: dasar pengali adalah nilai_awal (nilai buku saat ini)
            $nilai_penyusutan = $nilai_awal * ((float) $request->persentase_penyusutan / 100);
        }

        // Pastikan nilai_akhir tidak jatuh di bawah nilai_residu
        if (($nilai_awal - $nilai_penyusutan) < $nilai_residu) {
            $nilai_penyusutan = $nilai_awal - $nilai_residu;
        }

        $nilai_akhir    = $nilai_awal - $nilai_penyusutan;
        $akumulasi_baru = $total_sudah_susut + $nilai_penyusutan;

        Penyusutan::create([
            'assetID'               => $asset->assetID,
            'InstansiID'            => Auth::user()->InstansiID,
            'tahun'                 => $request->tahun,
            'bulan'                 => $request->bulan,
            'nilai_awal'            => $nilai_awal,
            'nilai_penyusutan'      => $nilai_penyusutan,
            'nilai_akhir'           => $nilai_akhir,
            'akumulasi_penyusutan'  => $akumulasi_baru,
            'metode'                => $request->metode,
            'persentase_penyusutan' => $request->metode === 'saldo_menurun'
                                        ? $request->persentase_penyusutan
                                        : null,
            'catatan'               => $request->catatan,
            'created_by'            => Auth::id(),
            'updated_by'            => Auth::id(),
        ]);

        return redirect()->route('penyusutan.index')
            ->with('success', 'Data penyusutan berhasil ditambahkan.');
    }

    public function generatePDF($id)
    {
        try {
            // Ambil data
            $penyusutan = Penyusutan::with(['asset', 'instansi', 'creator', 'updater'])->findOrFail($id);
            
            // Log untuk debugging
            Log::info('Generating PDF for penyusutan ID: ' . $id);
            
            // Pastikan tidak ada output sebelum PDF
            if (ob_get_length()) {
                ob_end_clean();
            }
            
            // Generate PDF dengan opsi yang tepat
            $pdf = Pdf::loadView('penyusutans.pdf', compact('penyusutan'));
            $pdf->setPaper('a4', 'landscape');
            
            // Set opsi tambahan untuk mencegah error
            $pdf->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isPhpEnabled' => true,
                'debugCss' => false,
                'debugLayout' => false,
                'debugLayoutLines' => false,
                'debugLayoutBlocks' => false,
                'debugLayoutInline' => false,
                'debugCss' => false,
            ]);
            
            $filename = 'Laporan_Penyusutan_' . ($penyusutan->asset->kode_asset ?? 'Aset') . '_' . date('Ymd_His') . '.pdf';
            
            // Return PDF untuk download
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            Log::error('PDF Generation Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            if (request()->ajax()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            
            return redirect()->back()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
        }
    }

    // ---------------------------------------------------------------
    // SHOW
    // ---------------------------------------------------------------

    public function show(string $id)
    {
        $penyusutan = Penyusutan::with(['asset', 'instansi', 'creator', 'updater'])
            ->findOrFail($id);

        return view('penyusutans.show', compact('penyusutan'));
    }

    // ---------------------------------------------------------------
    // DESTROY
    // ---------------------------------------------------------------

    public function destroy(string $id)
    {
        if (Auth::user()->role !== 'admin_sekolah' && Auth::user()->role !== 'super_admin') {
            abort(403, 'Hanya admin sekolah yang dapat menghapus data penyusutan.');
        }

        $penyusutan = Penyusutan::findOrFail($id);
        $penyusutan->delete();

        return redirect()->route('penyusutan.index')
            ->with('success', 'Data penyusutan berhasil dihapus.');
    }
    
    

}