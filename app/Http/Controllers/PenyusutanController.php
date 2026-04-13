<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Penyusutan;
use App\Models\Asset;
use Illuminate\Http\Request;

class PenyusutanController extends Controller
{
    // ---------------------------------------------------------------
    // INDEX
    // ---------------------------------------------------------------

    public function index()
    {
        $penyusutans = Penyusutan::with(['asset', 'instansi'])
            ->latest()
            ->paginate(10);

        return view('penyusutans.index', compact('penyusutans'));
    }

    // ---------------------------------------------------------------
    // CREATE
    // ---------------------------------------------------------------

    public function create()
    {
        // Hanya tampilkan aset milik instansi ini (global scope di Asset model)
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

        // FIX: nilai_awal aktual = nilai_akhir penyusutan terakhir, bukan nilai_perolehan mentah
        $last = Penyusutan::where('assetID', $assetID)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->first();

        $nilai_awal   = $last ? (float) $last->nilai_akhir : (float) $asset->nilai_perolehan;
        $nilai_residu = (float) ($asset->nilai_residu ?? 0);
        $umur         = (int)   $asset->umur_ekonomis;

        // Sudah fully depreciated?
        $fully_depreciated = $nilai_awal <= $nilai_residu;

        // Akumulasi sampai saat ini
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
        // --- Validasi input ---
        $request->validate([
            'assetID' => 'required|exists:assets,assetID',
            'tahun'   => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'bulan'   => 'nullable|integer|min:1|max:12',
            'metode'  => 'required|in:garis_lurus,saldo_menurun',

            // FIX: hanya wajib untuk saldo_menurun
            'persentase_penyusutan' => 'required_if:metode,saldo_menurun|nullable|numeric|min:1|max:100',

            'catatan' => 'nullable|string|max:1000',
        ]);

        $asset = Asset::findOrFail($request->assetID);

        // --- Guard: umur ekonomis valid ---
        if ($asset->umur_ekonomis <= 0) {
            return back()->withInput()->with('error', 'Umur ekonomis aset tidak valid.');
        }

        // --- Guard: cek duplikat periode (unique per aset+tahun+bulan) ---
        $duplikat = Penyusutan::where('assetID', $asset->assetID)
            ->where('tahun', $request->tahun)
            ->where('bulan', $request->bulan) // null == null handled by DB unique constraint
            ->exists();

        if ($duplikat) {
            return back()->withInput()->with('error',
                'Penyusutan untuk aset ini pada periode ' .
                $request->tahun .
                ($request->bulan ? '/' . $request->bulan : '') .
                ' sudah pernah dihitung.'
            );
        }

        // --- Tentukan nilai_awal: nilai_akhir periode terakhir ATAU nilai_perolehan ---
        $lastPenyusutan = Penyusutan::where('assetID', $asset->assetID)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->first();

        $nilai_awal = $lastPenyusutan
            ? (float) $lastPenyusutan->nilai_akhir
            : (float) $asset->nilai_perolehan;

        $nilai_residu = (float) ($asset->nilai_residu ?? 0);

        // --- Guard: sudah fully depreciated ---
        if ($nilai_awal <= $nilai_residu) {
            return back()->withInput()->with('error',
                'Aset ini sudah mencapai nilai residu (fully depreciated). Tidak perlu dihitung ulang.'
            );
        }

        // --- Guard: total akumulasi sudah maks ---
        $total_sudah_susut  = (float) Penyusutan::where('assetID', $asset->assetID)
            ->where('InstansiID', Auth::user()->InstansiID)
            ->sum('nilai_penyusutan');

        $max_bisa_susut = (float) $asset->nilai_perolehan - $nilai_residu;

        if ($total_sudah_susut >= $max_bisa_susut) {
            return back()->withInput()->with('error',
                'Total penyusutan aset ini sudah mencapai batas maksimum (nilai perolehan dikurangi nilai residu).'
            );
        }

        // --- Hitung nilai penyusutan ---
        if ($request->metode === 'garis_lurus') {
            /*
             * Garis lurus: beban per periode selalu sama,
             * dihitung dari nilai_perolehan (bukan nilai_awal saat ini).
             * Ini BENAR secara akuntansi — beban penyusutan tidak berubah tiap tahun.
             */
            $nilai_penyusutan = ($asset->nilai_perolehan - $nilai_residu) / $asset->umur_ekonomis;
        } else {
            /*
             * Saldo menurun: tarif % × nilai_awal (nilai buku saat ini).
             * Dasar pengali mengecil setiap periode sehingga beban menurun.
             */
            $nilai_penyusutan = $nilai_awal * ((float) $request->persentase_penyusutan / 100);
        }

        // --- Pastikan nilai_akhir tidak jatuh di bawah nilai_residu ---
        if (($nilai_awal - $nilai_penyusutan) < $nilai_residu) {
            $nilai_penyusutan = $nilai_awal - $nilai_residu;
        }

        $nilai_akhir = $nilai_awal - $nilai_penyusutan;

        // --- Hitung akumulasi baru (eksplisit filter instansi) ---
        $akumulasi_baru = $total_sudah_susut + $nilai_penyusutan;

        // --- Simpan ---
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
            // FIX: simpan null untuk garis_lurus agar tidak menyimpan angka 0 yang menyesatkan
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

        // Global scope melindungi otomatis
        $penyusutan = Penyusutan::findOrFail($id);
        $penyusutan->delete();

        return redirect()->route('penyusutan.index')
            ->with('success', 'Data penyusutan berhasil dihapus.');
    }
}