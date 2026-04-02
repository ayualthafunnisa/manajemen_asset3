<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Penyusutan;
use App\Models\Asset;
use App\Models\Instansi;
use Illuminate\Http\Request;

class PenyusutanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penyusutans = Penyusutan::with(['asset','instansi'])->latest()->paginate(10);
        return view('penyusutans.index', compact('penyusutans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $assets = Asset::all();
        return view('penyusutans.create', compact('assets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'assetID'               => 'required|exists:assets,assetID',
            'tahun'                 => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'bulan'                 => 'nullable|integer|min:1|max:12',
            'metode'                => 'required|in:garis_lurus,saldo_menurun',
            'persentase_penyusutan' => 'required|numeric|min:0|max:100',
            'catatan'               => 'nullable|string',
        ]);

        // Global scope sudah filter — double-check asset milik instansi ini
        $asset = Asset::findOrFail($request->assetID);

        if ($asset->umur_ekonomis <= 0) {
            return back()->with('error', 'Umur ekonomis asset tidak valid.');
        }

        $lastPenyusutan = Penyusutan::where('assetID', $asset->assetID)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->first();

        $nilai_awal = $lastPenyusutan
            ? $lastPenyusutan->nilai_akhir
            : $asset->nilai_perolehan;

        if ($nilai_awal <= $asset->nilai_residu) {
            return back()->with('error', 'Asset sudah mencapai nilai residu, tidak perlu dihitung ulang.');
        }

        // Hitung nilai penyusutan berdasarkan metode
        if ($request->metode === 'garis_lurus') {
            $nilai_penyusutan = ($asset->nilai_perolehan - $asset->nilai_residu) / $asset->umur_ekonomis;
        } else {
            // Saldo menurun
            $nilai_penyusutan = $nilai_awal * ($request->persentase_penyusutan / 100);
        }

        // Jangan sampai nilai akhir di bawah nilai residu
        if (($nilai_awal - $nilai_penyusutan) < $asset->nilai_residu) {
            $nilai_penyusutan = $nilai_awal - $asset->nilai_residu;
        }

        $nilai_akhir = $nilai_awal - $nilai_penyusutan;

        $akumulasi = Penyusutan::where('assetID', $asset->assetID)->sum('nilai_penyusutan')
            + $nilai_penyusutan;

        Penyusutan::create([
            'assetID'               => $asset->assetID,
            'InstansiID'            => Auth::user()->InstansiID, // inject otomatis
            'tahun'                 => $request->tahun,
            'bulan'                 => $request->bulan,
            'nilai_awal'            => $nilai_awal,
            'nilai_penyusutan'      => $nilai_penyusutan,
            'nilai_akhir'           => $nilai_akhir,
            'akumulasi_penyusutan'  => $akumulasi,
            'metode'                => $request->metode,
            'persentase_penyusutan' => $request->persentase_penyusutan,
            'catatan'               => $request->catatan,
            'created_by'            => Auth::id(),
            'updated_by'            => Auth::id(),
        ]);

        return redirect()->route('penyusutan.index')
            ->with('success', 'Data penyusutan berhasil ditambahkan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $penyusutan = Penyusutan::with([
            'asset',
            'instansi',
            'creator',
            'updater'
        ])->findOrFail($id);

        return view('penyusutans.show', compact('penyusutan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //$penyusutan = Penyusutan::findOrFail($id);
        //$assets = Asset::all();
        //$instansis = Instansi::all();

        //return view('penyusutans.edit', compact('penyusutan','assets','instansis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (Auth::user()->role !== 'admin_sekolah') {
            abort(403, 'Hanya admin sekolah yang dapat menghapus data penyusutan.');
        }

        // Global scope proteksi otomatis
        $penyusutan = Penyusutan::findOrFail($id);
        $penyusutan->delete();

        return redirect()->route('penyusutan.index')
            ->with('success', 'Data penyusutan berhasil dihapus.');
    }
}