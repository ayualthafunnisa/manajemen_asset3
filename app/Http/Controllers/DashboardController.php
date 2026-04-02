<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Instansi;
use App\Models\User;
use App\Models\Asset;
use App\Models\Kategori;
use App\Models\LokasiAsset;
use App\Models\Penghapusan;
use App\Models\Kerusakan;
use App\Models\Perbaikan;

class DashboardController extends Controller
{
    public function superAdminDashboard()
    {
        $totalInstansi   = Instansi::count();
        $instansiBaru    = Instansi::whereMonth('created_at', now()->month)->count();
        $totalUser       = User::count();
        $userAktif       = User::where('status', 'active')->count();
        $totalAsset      = Asset::count();
        $pendingApproval = Penghapusan::where('status_penghapusan', 'diajukan')->count();
        $instansis       = Instansi::withCount(['user', 'assets'])->latest()->take(6)->get();
        $aktivitas       = collect();

        return view('dashboard.superadmin', compact(
            'totalInstansi', 'instansiBaru',
            'totalUser', 'userAktif',
            'totalAsset', 'pendingApproval',
            'instansis', 'aktivitas'
        ));
    }

    public function adminDashboard()
    {
        $license = Auth::user()->license;

        if (!$license || !$license->isValid()) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Lisensi Anda sudah expired.']);
        }
        $instansiId = Auth::user()->InstansiID;

        $license = Auth::user()->license;
        $sisaHari = $license ? now()->diffInDays($license->expired_date, false) : 0;

        $totalKategori      = Kategori::where('InstansiID', $instansiId)->count();
        $totalLokasi        = LokasiAsset::where('InstansiID', $instansiId)->count();
        $totalAsset         = Asset::where('InstansiID', $instansiId)->count();
        $assetBaru          = Asset::where('InstansiID', $instansiId)
                                   ->whereMonth('created_at', now()->month)->count();
        $assetRusak         = Kerusakan::whereHas('asset', fn($q) => $q->where('InstansiID', $instansiId))
                                       ->whereNotIn('status_perbaikan', ['selesai', 'ditolak'])->count();
        $penghapusanPending = Penghapusan::whereHas('asset', fn($q) => $q->where('InstansiID', $instansiId))
                                         ->where('status_penghapusan', 'diajukan')->count();
        $penghapusanList    = Penghapusan::whereHas('asset', fn($q) => $q->where('InstansiID', $instansiId))
                                         ->where('status_penghapusan', 'diajukan')
                                         ->with('asset')->latest()->take(5)->get();
        $kerusakanTerbaru   = Kerusakan::whereHas('asset', fn($q) => $q->where('InstansiID', $instansiId))
                                       ->with('asset.lokasi')->latest()->take(5)->get();

        return view('dashboard.admin', compact(
            'totalAsset', 'assetBaru', 'assetRusak',
            'penghapusanPending', 'totalKategori', 'totalLokasi',
            'penghapusanList', 'kerusakanTerbaru', 'license', 'sisaHari'
        ));
    }

    // ── Sebelumnya: stafDashboard (role staf_asset — tidak ada di enum)
    //    Diperbaiki: role enum adalah 'petugas'
    public function petugasDashboard()
    {
        $instansiId = Auth::user()->InstansiID;
        $userId     = Auth::id();

        $totalAsset         = Asset::where('InstansiID', $instansiId)->count();
        $totalKerusakan     = Kerusakan::where('dilaporkan_oleh', $userId)->count();
        $kerusakanAktif     = Kerusakan::where('dilaporkan_oleh', $userId)
                                       ->whereNotIn('status_perbaikan', ['selesai', 'ditolak'])->count();
        $totalPenghapusan   = Penghapusan::where('diajukan_oleh', $userId)->count();
        $penghapusanPending = Penghapusan::where('diajukan_oleh', $userId)
                                         ->where('status_penghapusan', 'diajukan')->count();
        $kerusakanSaya      = Kerusakan::where('dilaporkan_oleh', $userId)
                                       ->with('asset')->latest()->take(5)->get();
        $assetTerbaru       = Asset::where('InstansiID', $instansiId)
                                   ->with(['kategori', 'lokasi'])->latest()->take(5)->get();

        return view('dashboard.staf', compact(
            'totalAsset', 'totalKerusakan', 'kerusakanAktif',
            'totalPenghapusan', 'penghapusanPending',
            'assetTerbaru', 'kerusakanSaya'
        ));
    }

    // Alias agar route lama (dashboard.staf) tetap jalan selama transisi
    public function stafDashboard()
    {
        return $this->petugasDashboard();
    }

    public function teknisiDashboard()
    {
        $userId     = Auth::id();
        $instansiId = Auth::user()->InstansiID;

        // Keluhan aktif yang perlu ditangani teknisi
        $keluhanAktif = Kerusakan::whereIn('status_perbaikan', ['dilaporkan', 'diproses'])
                                  ->when($instansiId, fn($q) => $q->where('InstansiID', $instansiId))
                                  ->count();

        $keluhanKritis = Kerusakan::whereIn('status_perbaikan', ['dilaporkan', 'diproses'])
                                   ->where('prioritas', 'kritis')
                                   ->when($instansiId, fn($q) => $q->where('InstansiID', $instansiId))
                                   ->count();

        // Statistik perbaikan milik teknisi ini
        $totalPerbaikan   = Perbaikan::where('teknisi_id', $userId)->count();
        $perbaikanSelesai = Perbaikan::where('teknisi_id', $userId)->where('status', 'selesai')->count();
        $totalBiaya       = Perbaikan::where('teknisi_id', $userId)->where('status', 'selesai')->sum('biaya_aktual');

        // Keluhan terbaru (belum ditangani / sedang diproses)
        $keluhanTerbaru = Kerusakan::with(['asset', 'lokasi', 'pelapor'])
                                    ->whereIn('status_perbaikan', ['dilaporkan', 'diproses'])
                                    ->when($instansiId, fn($q) => $q->where('InstansiID', $instansiId))
                                    ->orderByRaw("FIELD(prioritas,'kritis','tinggi','sedang','rendah')")
                                    ->orderByDesc('created_at')
                                    ->take(5)->get();

        // Riwayat perbaikan terbaru milik teknisi ini
        $riwayatTerbaru = Perbaikan::with(['kerusakan.asset', 'kerusakan.lokasi'])
                                    ->where('teknisi_id', $userId)
                                    ->whereIn('status', ['selesai', 'tidak_bisa_diperbaiki'])
                                    ->orderByDesc('selesai_perbaikan')
                                    ->take(5)->get();

        return view('dashboard.teknisi', compact(
            'keluhanAktif', 'keluhanKritis',
            'totalPerbaikan', 'perbaikanSelesai', 'totalBiaya',
            'keluhanTerbaru', 'riwayatTerbaru'
        ));
    }
}