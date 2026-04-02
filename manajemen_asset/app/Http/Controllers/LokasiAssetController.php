<?php

namespace App\Http\Controllers;

use App\Models\LokasiAsset;
use App\Models\Instansi;
use Illuminate\Http\Request;

class LokasiAssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lokasis = LokasiAsset::latest()->paginate(10);
        
        return view('lokasis.index', compact('lokasis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $instansis = Instansi::all();
        return view('lokasis.create', compact('instansis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'InstansiID' => 'required|exists:instansis,InstansiID',
            'KodeLokasi' => 'required|string|max:20|unique:lokasi_assets,KodeLokasi',
            'NamaLokasi' => 'required|string|max:100',
            'Gedung' => 'nullable|string|max:100',
            'Lantai' => 'nullable|string|max:10',
            'Ruangan' => 'nullable|string|max:50',
            'PenanggungJawab' => 'nullable|string|max:100',
            'TeleponPenanggungJawab' => 'nullable|string|max:20',
            'LuasRuangan' => 'nullable|numeric|min:0|max:99999999.99',
            'JenisLokasi' => 'required|in:ruangan,gudang,laboratorium,lapangan,lainnya',
            'Status' => 'required|in:active,maintenance,tidak_aktif,renovasi',
            'Keterangan' => 'nullable|string',
        ]);

        try {
            LokasiAsset::create($validated);
            
            return redirect()->route('lokasi.index')
                ->with('success', 'Lokasi asset berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menambahkan lokasi asset: ' . $e->getMessage());
        }
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
        $lokasi = LokasiAsset::findOrFail($id);
        $instansis = Instansi::all();
        return view('lokasis.edit', compact('lokasi','instansis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $lokasi = LokasiAsset::findOrFail($id);
        
        $validated = $request->validate([
            'InstansiID' => 'required|exists:instansis,InstansiID',
            'KodeLokasi' => 'required|string|max:20|unique:lokasi_assets,KodeLokasi,' . $id . ',LokasiID',
            'NamaLokasi' => 'required|string|max:100',
            'Gedung' => 'nullable|string|max:100',
            'Lantai' => 'nullable|string|max:10',
            'Ruangan' => 'nullable|string|max:50',
            'PenanggungJawab' => 'nullable|string|max:100',
            'TeleponPenanggungJawab' => 'nullable|string|max:20',
            'LuasRuangan' => 'nullable|numeric|min:0|max:99999999.99',
            'JenisLokasi' => 'required|in:ruangan,gudang,laboratorium,lapangan,lainnya',
            'Status' => 'required|in:active,maintenance,tidak_aktif,renovasi',
            'Keterangan' => 'nullable|string',
        ]);

        try {
            $lokasi->update($validated);
            
            return redirect()->route('lokasi.index')
                ->with('success', 'Lokasi asset berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal memperbarui lokasi asset: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lokasi = LokasiAsset::findOrFail($id);
        
        try {
            // Cek apakah lokasi memiliki relasi dengan asset
            if ($lokasi->assets && $lokasi->assets->count() > 0) {
                return redirect()->route('lokasi.index')
                    ->with('error', 'Tidak dapat menghapus lokasi yang masih memiliki asset terkait.');
            }
            
            $lokasi->delete();
            
            return redirect()->route('lokasi.index')
                ->with('success', 'Lokasi asset berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('lokasi.index')
                ->with('error', 'Gagal menghapus lokasi asset: ' . $e->getMessage());
        }
    }
}
