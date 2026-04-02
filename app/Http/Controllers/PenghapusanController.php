<?php

namespace App\Http\Controllers;

use App\Models\Penghapusan;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PenghapusanController extends Controller
{
    public function index()
    {
        // Staf hanya melihat pengajuan yang dia buat sendiri.
        $query = Penghapusan::with(['asset', 'pengaju', 'penyetuju']);

        if (Auth::user()->role === 'staf_asset') {
            $query->where('diajukan_oleh', Auth::id());
        }

        $penghapusans = $query->latest()->paginate(10);

        // Summary card
        $summaryQuery = Penghapusan::query();
        if (Auth::user()->role === 'staf_asset') {
            $summaryQuery->where('diajukan_oleh', Auth::id());
        }

        $summary = [
            'total'     => $summaryQuery->count(),
            'diajukan'  => (clone $summaryQuery)->where('status_penghapusan', 'diajukan')->count(),
            'disetujui' => (clone $summaryQuery)->where('status_penghapusan', 'disetujui')->count(),
            'ditolak'   => (clone $summaryQuery)->where('status_penghapusan', 'ditolak')->count(),
        ];

        return view('penghapusans.index', compact('penghapusans', 'summary'));
    }

    public function create()
    {
        // Asset sudah difilter global scope by InstansiID
        $assets = Asset::whereIn('status_asset', ['aktif', 'rusak'])->get();

        return view('penghapusans.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'assetID'               => 'required|exists:assets,assetID',
            'no_surat_penghapusan'  => 'required|unique:penghapusans',
            'tanggal_pengajuan'     => 'required|date',
            'jenis_penghapusan'     => 'required|in:rusak_total,usang,hilang,dijual,hibah,musnah',
            'alasan_penghapusan'    => 'required|in:kerusakan_permanen,teknologi_tertinggal,tidak_layak_pakai,kehilangan,penggantian,restrukturisasi',
            'nilai_buku'            => 'required|numeric|min:1',
            'harga_jual'            => 'nullable|numeric|min:0',
            'deskripsi_penghapusan' => 'required|min:10',
            'dokumen_pendukung'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'keterangan'            => 'nullable|string',
        ]);

        // Global scope sudah filter — double-check asset milik instansi ini
        $asset = Asset::findOrFail($request->assetID);

        $kerugianKeuntungan = null;
        if ($request->filled('harga_jual')) {
            $kerugianKeuntungan = $request->harga_jual - $request->nilai_buku;
        }

        $dokumenPath = null;
        if ($request->hasFile('dokumen_pendukung')) {
            $dokumenPath = $request->file('dokumen_pendukung')
                ->store('penghapusan-dokumen', 'public');
        }

        Penghapusan::create([
            'assetID'               => $request->assetID,
            'InstansiID'            => Auth::user()->InstansiID, // inject otomatis
            'no_surat_penghapusan'  => $request->no_surat_penghapusan,
            'tanggal_pengajuan'     => $request->tanggal_pengajuan,
            'jenis_penghapusan'     => $request->jenis_penghapusan,
            'alasan_penghapusan'    => $request->alasan_penghapusan,
            'nilai_buku'            => $request->nilai_buku,
            'harga_jual'            => $request->harga_jual,
            'kerugian_keuntungan'   => $kerugianKeuntungan,
            'deskripsi_penghapusan' => $request->deskripsi_penghapusan,
            'dokumen_pendukung'     => $dokumenPath,
            'keterangan'            => $request->keterangan,
            'diajukan_oleh'         => Auth::id(),
            'status_penghapusan'    => 'diajukan',
        ]);

        return redirect()->route('penghapusan.index')
            ->with('success', 'Pengajuan penghapusan berhasil dikirim dan menunggu persetujuan admin.');
    }

    public function show(string $id)
    {
        $penghapusan = Penghapusan::with(['asset', 'pengaju', 'penyetuju', 'instansi']) ->findOrFail($id);

        // Staf hanya bisa lihat pengajuan miliknya sendiri
        if (Auth::user()->role === 'staf_asset' && $penghapusan->diajukan_oleh !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        return view('penghapusans.show', compact('penghapusan'));
    }

    // -------------------------------------------------------------------------
    // EDIT — hanya pengajuan berstatus 'diajukan' atau 'ditolak'
    // -------------------------------------------------------------------------
    public function edit(string $id)
    {
        $penghapusan = Penghapusan::findOrFail($id);

        if (Auth::user()->role === 'staf_asset' && $penghapusan->diajukan_oleh !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        if ($penghapusan->status_penghapusan === 'disetujui') {
            return redirect()->route('penghapusan.show', $id)
                ->with('error', 'Pengajuan yang sudah disetujui tidak dapat diedit.');
        }

        $assets = Asset::whereIn('status_asset', ['aktif', 'rusak'])->get();

        return view('penghapusans.edit', compact('penghapusan', 'assets'));
    }

    public function update(Request $request, string $id)
    {
        $penghapusan = Penghapusan::findOrFail($id);

        if (Auth::user()->role === 'staf_asset' && $penghapusan->diajukan_oleh !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        if ($penghapusan->status_penghapusan === 'disetujui') {
            return redirect()->route('penghapusan.show', $id)
                ->with('error', 'Pengajuan yang sudah disetujui tidak dapat diedit.');
        }

        $request->validate([
            'assetID'               => 'required|exists:assets,assetID',
            'no_surat_penghapusan'  => 'required|unique:penghapusans,no_surat_penghapusan,' . $id . ',penghapusanID',
            'tanggal_pengajuan'     => 'required|date',
            'jenis_penghapusan'     => 'required|in:rusak_total,usang,hilang,dijual,hibah,musnah',
            'alasan_penghapusan'    => 'required|in:kerusakan_permanen,teknologi_tertinggal,tidak_layak_pakai,kehilangan,penggantian,restrukturisasi',
            'nilai_buku'            => 'required|numeric|min:1',
            'harga_jual'            => 'nullable|numeric|min:0',
            'deskripsi_penghapusan' => 'required|min:10',
            'dokumen_pendukung'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'keterangan'            => 'nullable|string',
        ]);

        $kerugianKeuntungan = null;
        if ($request->filled('harga_jual')) {
            $kerugianKeuntungan = $request->harga_jual - $request->nilai_buku;
        }

        $updateData = [
            'assetID'               => $request->assetID,
            'no_surat_penghapusan'  => $request->no_surat_penghapusan,
            'tanggal_pengajuan'     => $request->tanggal_pengajuan,
            'jenis_penghapusan'     => $request->jenis_penghapusan,
            'alasan_penghapusan'    => $request->alasan_penghapusan,
            'nilai_buku'            => $request->nilai_buku,
            'harga_jual'            => $request->harga_jual,
            'kerugian_keuntungan'   => $kerugianKeuntungan,
            'deskripsi_penghapusan' => $request->deskripsi_penghapusan,
            'keterangan'            => $request->keterangan,
            'status_penghapusan'    => 'diajukan', // reset ke diajukan jika sebelumnya ditolak
        ];

        if ($request->hasFile('dokumen_pendukung')) {
            if ($penghapusan->dokumen_pendukung) {
                Storage::disk('public')->delete($penghapusan->dokumen_pendukung);
            }
            $updateData['dokumen_pendukung'] = $request->file('dokumen_pendukung')
                ->store('penghapusan-dokumen', 'public');
        }

        $penghapusan->update($updateData);

        return redirect()->route('penghapusan.index')
            ->with('success', 'Pengajuan penghapusan berhasil diperbarui.');
    }

    // APPROVE — hanya admin_sekolah
    public function approve(string $id)
    {
        if (Auth::user()->role !== 'admin_sekolah') {
            abort(403, 'Hanya admin sekolah yang dapat menyetujui penghapusan.');
        }

        try {
            DB::beginTransaction();

            // Global scope proteksi otomatis
            $penghapusan = Penghapusan::findOrFail($id);

            if ($penghapusan->status_penghapusan !== 'diajukan') {
                return back()->with('error', 'Status penghapusan tidak valid untuk disetujui.');
            }

            $penghapusan->update([
                'status_penghapusan'  => 'disetujui',
                'disetujui_oleh'      => Auth::id(),
                'tanggal_persetujuan' => now(),
                'tanggal_penghapusan' => now(),
            ]);

            $penghapusan->asset->update(['status_asset' => 'dihapus']);

            DB::commit();

            return redirect()->route('penghapusan.index')
                ->with('success', 'Penghapusan aset berhasil disetujui. Status aset telah diubah menjadi "dihapus".');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // REJECT — hanya admin_sekolah
    public function reject(Request $request, string $id)
    {
        if (Auth::user()->role !== 'admin_sekolah') {
            abort(403, 'Hanya admin sekolah yang dapat menolak penghapusan.');
        }

        $request->validate([
            'alasan_penolakan' => 'required|min:10',
        ]);

        try {
            DB::beginTransaction();

            // Global scope proteksi otomatis
            $penghapusan = Penghapusan::findOrFail($id);

            if ($penghapusan->status_penghapusan !== 'diajukan') {
                return back()->with('error', 'Status penghapusan tidak valid untuk ditolak.');
            }

            $penghapusan->update([
                'status_penghapusan'  => 'ditolak',
                'disetujui_oleh'      => Auth::id(),
                'tanggal_persetujuan' => now(),
                'alasan_penolakan'    => $request->alasan_penolakan,
            ]);

            DB::commit();

            return redirect()->route('penghapusan.index')
                ->with('success', 'Pengajuan penghapusan berhasil ditolak.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hanya bisa hapus jika status 'diajukan' atau 'ditolak'
     */
    public function destroy($id)
    {
        try {
            $query = Penghapusan::where('penghapusanID', $id)
                                ->where('InstansiID', Auth::user()->InstansiID);

            if (Auth::user()->role === 'staf_asset') {
                $query->where('diajukan_oleh', Auth::id());
            }

            $penghapusan = $query->firstOrFail();

            if (!in_array($penghapusan->status_penghapusan, ['diajukan', 'ditolak'])) {
                return back()->with('error', 'Hanya data dengan status Diajukan atau Ditolak yang dapat dihapus.');
            }

            if ($penghapusan->dokumen_pendukung) {
                Storage::disk('public')->delete($penghapusan->dokumen_pendukung);
            }

            $penghapusan->delete();

            return redirect()->route('penghapusan.index')
                ->with('success', 'Data penghapusan berhasil dihapus.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}