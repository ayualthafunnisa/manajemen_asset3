<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Instansi;
use App\Models\Kategori;
use App\Models\LokasiAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assets = Asset::with(['instansi','kategori','lokasi'])
            ->latest()
            ->paginate(10);

        return view('assets.index', compact('assets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $instansis = Instansi::all();
        $kategoris = Kategori::all();
        $lokasis   = LokasiAsset::all();

        return view('assets.create', compact('instansis','kategoris','lokasis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'InstansiID' => 'required|exists:instansis,InstansiID',
            'KategoriID' => 'required|exists:kategori_assets,KategoriID',
            'LokasiID'   => 'required|exists:lokasi_assets,LokasiID',

            'kode_asset' => 'required|string|max:50|unique:assets,kode_asset',
            'nama_asset' => 'required|string|max:255',

            'merk' => 'nullable|string|max:100',
            'tipe_model' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'nomor_registrasi' => 'nullable|string|max:100',

            'sumber_perolehan' => 'nullable|string|max:100',
            'tanggal_perolehan' => 'nullable|date',
            'tanggal_penggunaan' => 'nullable|date',

            'nilai_perolehan' => 'nullable|numeric|min:0',
            'nilai_residu' => 'nullable|numeric|min:0',
            'umur_ekonomis' => 'nullable|integer|min:0',

            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'status_asset' => 'required|in:aktif,dipinjam,diperbaiki,dihapus',

            'warna' => 'nullable|string|max:50',
            'bahan' => 'nullable|string|max:100',
            'dimensi' => 'nullable|string|max:100',
            'berat' => 'nullable|string|max:50',

            'jumlah' => 'required|integer|min:1',
            'satuan' => 'nullable|string|max:50',

            'spesifikasi' => 'nullable|string',
            'vendor' => 'nullable|string|max:150',
            'no_faktur' => 'nullable|string|max:100',
            'tanggal_faktur' => 'nullable|date',

            'gambar_asset' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'dokumen_pendukung' => 'nullable|file|mimes:pdf,doc,docx|max:4096',

            'keterangan' => 'nullable|string',
        ]);

        try {

            // Upload gambar
            if ($request->hasFile('gambar_asset')) {
                $validated['gambar_asset'] = $request
                    ->file('gambar_asset')
                    ->store('assets/gambar', 'public');
            }

            // Upload dokumen
            if ($request->hasFile('dokumen_pendukung')) {
                $validated['dokumen_pendukung'] = $request
                    ->file('dokumen_pendukung')
                    ->store('assets/dokumen', 'public');
            }

            Asset::create($validated);

            return redirect()->route('asset.index')
                ->with('success', 'Asset berhasil ditambahkan.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menambahkan asset: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $asset = Asset::with(['instansi', 'kategori', 'lokasi'])->findOrFail($id);
        return view('assets.show', compact('asset'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $asset = Asset::findOrFail($id);
        $instansis = Instansi::all();
        $kategoris = Kategori::all();
        $lokasis   = LokasiAsset::all();

        return view('assets.edit', compact('asset','instansis','kategoris','lokasis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $asset = Asset::findOrFail($id);

        $validated = $request->validate([
            'InstansiID' => 'required|exists:instansis,InstansiID',
            'KategoriID' => 'required|exists:kategori_assets,KategoriID',
            'LokasiID'   => 'required|exists:lokasi_assets,LokasiID',

            'kode_asset' => 'required|string|max:50|unique:assets,kode_asset,' . $id . ',assetID',
            'nama_asset' => 'required|string|max:255',

            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'status_asset' => 'required|in:aktif,dipinjam,diperbaiki,dihapus',

            'jumlah' => 'required|integer|min:1',

            'gambar_asset' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'dokumen_pendukung' => 'nullable|file|mimes:pdf,doc,docx|max:4096',
        ]);

        try {

            // Update gambar
            if ($request->hasFile('gambar_asset')) {
                if ($asset->gambar_asset) {
                    Storage::disk('public')->delete($asset->gambar_asset);
                }

                $validated['gambar_asset'] = $request
                    ->file('gambar_asset')
                    ->store('assets/gambar', 'public');
            }

            // Update dokumen
            if ($request->hasFile('dokumen_pendukung')) {
                if ($asset->dokumen_pendukung) {
                    Storage::disk('public')->delete($asset->dokumen_pendukung);
                }

                $validated['dokumen_pendukung'] = $request
                    ->file('dokumen_pendukung')
                    ->store('assets/dokumen', 'public');
            }

            $asset->update($validated);

            return redirect()->route('asset.index')
                ->with('success', 'Asset berhasil diperbarui.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal memperbarui asset: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $asset = Asset::findOrFail($id);

        try {

            if ($asset->gambar_asset) {
                Storage::disk('public')->delete($asset->gambar_asset);
            }

            if ($asset->dokumen_pendukung) {
                Storage::disk('public')->delete($asset->dokumen_pendukung);
            }

            $asset->delete();

            return redirect()->route('asset.index')
                ->with('success', 'Asset berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->route('asset.index')
                ->with('error', 'Gagal menghapus asset: ' . $e->getMessage());
        }
    }
}
