<?php

namespace App\Http\Controllers;

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
        $instansis = Instansi::all();
        return view('penyusutans.create', compact('assets','instansis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'assetID' => 'required|exists:assets,assetID',
            'InstansiID' => 'required|exists:instansis,InstansiID',
            'tahun' => 'required|integer',
            'bulan' => 'nullable|integer|min:1|max:12',
            'metode' => 'required|in:garis_lurus,saldo_menurun',
            'persentase_penyusutan' => 'required|numeric|min:0',
            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date|after_or_equal:periode_awal',
        ]);

        $asset = Asset::findOrFail($request->assetID);

        // Ambil nilai awal dari asset
        $nilai_awal = $asset->nilai_perolehan;

        // Hitung penyusutan
        if ($request->metode == 'garis_lurus') {

            $nilai_penyusutan = ($nilai_awal - $asset->nilai_residu) 
                                / $asset->umur_ekonomis;

        } else { // saldo menurun

            $nilai_penyusutan = $nilai_awal * ($request->persentase_penyusutan / 100);
        }

        $nilai_akhir = $nilai_awal - $nilai_penyusutan;

        $akumulasi = Penyusutan::where('assetID', $request->assetID)
                        ->sum('nilai_penyusutan') + $nilai_penyusutan;

        Penyusutan::create([
            'assetID' => $request->assetID,
            'InstansiID' => $request->InstansiID,
            'tahun' => $request->tahun,
            'bulan' => $request->bulan,
            'nilai_awal' => $nilai_awal,
            'nilai_penyusutan' => $nilai_penyusutan,
            'nilai_akhir' => $nilai_akhir,
            'akumulasi_penyusutan' => $akumulasi,
            'metode' => $request->metode,
            'persentase_penyusutan' => $request->persentase_penyusutan,
            'periode_awal' => $request->periode_awal,
            'periode_akhir' => $request->periode_akhir,
            //'status' => 'draft',
            //'posted_to_akuntansi' => false,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('penyusutans.index')
            ->with('success', 'Data penyusutan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $penyusutan = Penyusutan::findOrFail($id);
        $penyusutan->delete();

        return redirect()
            ->route('penyusutans.index')
            ->with('success', 'Data penyusutan berhasil dihapus.');
    }
}
