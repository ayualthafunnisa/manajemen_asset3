<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BarcodeController extends Controller
{
    /**
     * Tampilkan halaman barcode untuk satu asset (2 QR code).
     */
    public function show(string $id)
    {
        $asset = Asset::with(['instansi', 'kategori', 'lokasi'])->findOrFail($id);

        $urlDetail    = route('asset.show', $asset->assetID);
        $urlKerusakan = route('kerusakan.public.form', ['kode' => $asset->kode_asset]);

        // Generate QR sebagai base64 SVG (ringan, tajam di semua ukuran)
        $qrDetail    = base64_encode(QrCode::format('svg')->size(220)->margin(1)->generate($urlDetail));
        $qrKerusakan = base64_encode(QrCode::format('svg')->size(220)->margin(1)->color(220, 38, 38)->generate($urlKerusakan));

        return view('assets.barcode', compact('asset', 'urlDetail', 'urlKerusakan', 'qrDetail', 'qrKerusakan'));
    }

    /**
     * Download QR code sebagai PNG.
     * URL: /asset/{id}/barcode/download?type=detail|kerusakan
     */
    public function download(string $id, Request $request)
    {
        $asset = Asset::findOrFail($id);
        $type  = $request->query('type', 'detail');

        if ($type === 'kerusakan') {
            $url      = route('kerusakan.public.form', ['kode' => $asset->kode_asset]);
            $filename = 'lapor-kerusakan-' . $asset->kode_asset . '.png';
            $qr       = QrCode::format('png')->size(400)->margin(2)->color(220, 38, 38)->generate($url);
        } else {
            $url      = route('asset.show', $asset->assetID);
            $filename = 'detail-asset-' . $asset->kode_asset . '.png';
            $qr       = QrCode::format('png')->size(400)->margin(2)->generate($url);
        }

        return response($qr)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Halaman cetak label barcode (2 QR dalam 1 halaman, tanpa layout app).
     * URL: /asset/{id}/barcode/print
     */
    public function print(string $id)
    {
        $asset = Asset::with(['instansi', 'kategori', 'lokasi'])->findOrFail($id);

        // Catat waktu cetak terakhir
        $asset->update(['qr_last_printed_at' => now()]);

        $urlDetail    = route('asset.show', $asset->assetID);
        $urlKerusakan = route('kerusakan.public.form', ['kode' => $asset->kode_asset]);

        // PNG base64 untuk halaman print (lebih kompatibel saat print dari browser)
        $qrDetail    = base64_encode(QrCode::format('png')->size(300)->margin(2)->generate($urlDetail));
        $qrKerusakan = base64_encode(QrCode::format('png')->size(300)->margin(2)->color(220, 38, 38)->generate($urlKerusakan));

        return view('assets.barcode-print', compact('asset', 'urlDetail', 'urlKerusakan', 'qrDetail', 'qrKerusakan'));
    }

    /**
     * Endpoint AJAX — generate QR base64 untuk ditampilkan di modal (halaman index).
     * URL: GET /asset/{id}/barcode/inline
     */
    public function inline(string $id)
    {
        $asset = Asset::findOrFail($id);

        $urlDetail    = route('asset.show', $asset->assetID);
        $urlKerusakan = route('kerusakan.public.form', ['kode' => $asset->kode_asset]);

        $qrDetail    = base64_encode(QrCode::format('svg')->size(180)->margin(1)->generate($urlDetail));
        $qrKerusakan = base64_encode(QrCode::format('svg')->size(180)->margin(1)->color(220, 38, 38)->generate($urlKerusakan));

        return response()->json([
            'kode_asset'   => $asset->kode_asset,
            'nama_asset'   => $asset->nama_asset,
            'qr_detail'    => 'data:image/svg+xml;base64,' . $qrDetail,
            'qr_kerusakan' => 'data:image/svg+xml;base64,' . $qrKerusakan,
            'url_cetak'    => route('asset.barcode.print', $asset->assetID),
            'dl_detail'    => route('asset.barcode.download', ['id' => $asset->assetID, 'type' => 'detail']),
            'dl_kerusakan' => route('asset.barcode.download', ['id' => $asset->assetID, 'type' => 'kerusakan']),
        ]);
    }
}