<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Instansi;
use App\Models\Kategori;
use App\Models\LokasiAsset;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index(Request $request)
    {
        $query = Asset::with(['kategori', 'instansi', 'lokasi']);
        
        // Filter berdasarkan role
        if (auth()->user()->role == 'admin_sekolah') {
            $query->where('InstansiID', auth()->user()->InstansiID);
        }
        
        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_asset', 'LIKE', "%{$search}%")
                  ->orWhere('nama_asset', 'LIKE', "%{$search}%")
                  ->orWhere('serial_number', 'LIKE', "%{$search}%");
            });
        }
        
        // Filter kondisi
        if ($request->has('kondisi') && $request->kondisi != '') {
            $query->where('kondisi', $request->kondisi);
        }
        
        // Filter status
        if ($request->has('status') && $request->status != '') {
            $query->where('status_asset', $request->status);
        }
        
        // Filter kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('KategoriID', $request->kategori);
        }
        
        $assets = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get data untuk filter
        $kategoris = Kategori::all();
        
        // Generate QR Code untuk setiap asset
        foreach ($assets as $asset) {
            $asset->qrCode = QrCode::size(80)
                ->format('svg')
                ->errorCorrection('M')
                ->generate($asset->kode_asset);
        }
        
        return view('assets.index', compact('assets', 'kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::all();
        $lokasis   = LokasiAsset::all();

        return view('assets.create', compact('kategoris','lokasis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'KategoriID'        => 'required|exists:kategori_assets,KategoriID',
            'LokasiID'          => 'required|exists:lokasi_assets,LokasiID',
            'kode_asset'        => 'required|string|max:50|unique:assets,kode_asset',
            'nama_asset'        => 'required|string|max:200',
            'merk'              => 'nullable|string|max:100',
            'serial_number'     => 'nullable|string|max:100|unique:assets,serial_number',
            'sumber_perolehan'  => 'required|in:pembelian,hibah,bantuan_pemerintah,produksi_sendiri,sewa',
            'tanggal_perolehan' => 'required|date',
            'nilai_perolehan'   => 'required|numeric|min:0',
            'nilai_residu'      => 'nullable|numeric|min:0',
            'umur_ekonomis'     => 'required|integer|min:1',
            'kondisi'           => 'required|in:baik,rusak_ringan,rusak_berat,tidak_berfungsi,hilang',
            'status_asset'      => 'required|in:aktif,non_aktif,dipinjam,diperbaiki,tersimpan,dihapus',
            'jumlah'            => 'required|integer|min:1',
            'satuan'            => 'nullable|string|max:20',
            'vendor'            => 'nullable|string|max:100',
            'gambar_asset'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'dokumen_pendukung' => 'nullable|file|mimes:pdf,doc,docx|max:4096',
            'keterangan'        => 'nullable|string',
        ]);

        // Inject InstansiID dari user login — tidak bisa dimanipulasi dari form
        $validated['InstansiID'] = Auth::user()->InstansiID;

        try {
            if ($request->hasFile('gambar_asset')) {
                $validated['gambar_asset'] = $request
                    ->file('gambar_asset')
                    ->store('assets/gambar', 'public');
            }

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
    public function show($id)
    {
        $asset = Asset::with(['kategori', 'instansi', 'lokasi'])->findOrFail($id);
        
        // Generate QR Code untuk detail (SVG)
        $asset->qrCode = QrCode::size(150)
            ->format('svg')
            ->errorCorrection('M')
            ->generate($asset->kode_asset);
        
        return view('assets.show', compact('asset'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $asset = Asset::findOrFail($id);
        $kategoris = Kategori::all();
        $lokasis   = LokasiAsset::all();

        return view('assets.edit', compact('asset','kategoris','lokasis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $asset = Asset::findOrFail($id);

        $validated = $request->validate([
            'KategoriID'        => 'required|exists:kategori_assets,KategoriID',
            'LokasiID'          => 'required|exists:lokasi_assets,LokasiID',
            'kode_asset'        => 'required|string|max:50|unique:assets,kode_asset,' . $id . ',assetID',
            'nama_asset'        => 'required|string|max:200',
            'merk'              => 'nullable|string|max:100',
            'serial_number'     => 'nullable|string|max:100|unique:assets,serial_number,' . $id . ',assetID',
            'sumber_perolehan'  => 'required|in:pembelian,hibah,bantuan_pemerintah,produksi_sendiri,sewa',
            'tanggal_perolehan' => 'required|date',
            'nilai_perolehan'   => 'required|numeric|min:0',
            'nilai_residu'      => 'nullable|numeric|min:0',
            'umur_ekonomis'     => 'required|integer|min:1',
            'kondisi'           => 'required|in:baik,rusak_ringan,rusak_berat,tidak_berfungsi,hilang',
            'status_asset'      => 'required|in:aktif,non_aktif,dipinjam,diperbaiki,tersimpan,dihapus',
            'jumlah'            => 'required|integer|min:1',
            'satuan'            => 'nullable|string|max:20',
            'vendor'            => 'nullable|string|max:100',
            'gambar_asset'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'dokumen_pendukung' => 'nullable|file|mimes:pdf,doc,docx|max:4096',
            'keterangan'        => 'nullable|string',
        ]);

        // Pastikan InstansiID tidak bisa diubah lewat form
        $validated['InstansiID'] = Auth::user()->InstansiID;

        try {
            if ($request->hasFile('gambar_asset')) {
                if ($asset->gambar_asset) {
                    Storage::disk('public')->delete($asset->gambar_asset);
                }
                $validated['gambar_asset'] = $request
                    ->file('gambar_asset')
                    ->store('assets/gambar', 'public');
            }

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

    public function downloadQrCode($id)
    {
        $asset = Asset::findOrFail($id);
        
        $qrCode = QrCode::format('svg')
            ->size(400)
            ->margin(2)
            ->errorCorrection('M')
            ->generate($asset->kode_asset);
        
        return response($qrCode)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="QR-' . $asset->kode_asset . '.svg"');
    }

    public function generateBarcodePdf(Request $request)
    {
        $request->validate([
            'asset_ids'   => 'required|array|min:1',
            'asset_ids.*' => 'exists:assets,assetID',
        ]);

        $assets = Asset::with(['kategori', 'lokasi'])
            ->whereIn('assetID', $request->asset_ids)
            ->get();

        // Generate QR code untuk setiap asset
        $assets->each(function ($asset) {
            $urlDetail    = route('asset.show', $asset->assetID);
            $urlKerusakan = route('kerusakan.create', ['kode' => $asset->kode_asset]);

            // Ganti png → svg, tidak butuh Imagick
            $asset->qr_detail_uri = 'data:image/svg+xml;base64,' .
                base64_encode(\QrCode::format('svg')->size(300)->margin(2)->generate($urlDetail));

            $asset->qr_kerusakan_uri = 'data:image/svg+xml;base64,' .
                base64_encode(\QrCode::format('svg')->size(300)->margin(2)->color(220, 38, 38)->generate($urlKerusakan));
        });

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('assets.barcode-pdf', compact('assets'))
            ->setPaper('a5', 'portrait');

        return $pdf->stream('barcode-asset-' . now()->format('Ymd-His') . '.pdf');
    }
    
    /**
     * Generate QR code SVG → convert ke PNG → return base64 string.
     * Hanya menggunakan GD (built-in PHP), tidak perlu Imagick.
     */
    private function svgQrToPngBase64(string $data, int $size = 120): string
    {
        // Step 1: Generate SVG string dari BaconQrCode via SimpleQrCode facade
        $svgString = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size($size)
            ->margin(1)
            ->errorCorrection('M')
            ->generate($data);
    
        // Step 2: Coba convert SVG → PNG pakai Imagick jika tersedia
        if (extension_loaded('imagick')) {
            try {
                $imagick = new \Imagick();
                $imagick->readImageBlob($svgString);
                $imagick->setImageFormat('png');
                $pngData = $imagick->getImageBlob();
                $imagick->clear();
                return base64_encode($pngData);
            } catch (\Exception $e) {
                // fall through ke metode berikutnya
            }
        }
    
        // Step 3: Fallback — gunakan GD untuk membuat QR PNG dari scratch
        // Pakai library endroid/qr-code atau simple-qrcode dengan renderer GD
        // Tapi karena simple-qrcode/bacon-qr-code tidak punya GD renderer bawaan,
        // kita pakai pendekatan: render SVG ke dalam GD via rsvg-convert jika ada
        
        // Step 4: Final fallback — pakai data URI SVG langsung (DomPDF support SVG sebagai <img>)
        // Encode SVG sebagai base64 untuk dipakai di <img src="data:image/svg+xml;base64,...">
        return 'SVG:' . base64_encode($svgString);
    }
}
