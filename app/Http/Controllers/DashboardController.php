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
use App\Models\License;
use App\Models\Notification;



class DashboardController extends Controller
{
    public function superAdminDashboard()
    {
        $totalInstansi = Instansi::count();
        $instansiBaru = Instansi::whereMonth('created_at', now()->month)->count();
        $totalUser = User::count();
        $userAktif = User::where('status', 'active')->count();
        $totalAsset = Asset::count();
        
        // Pending approvals (licenses and penghapusan)
        $pendingApproval = Penghapusan::where('status_penghapusan', 'pending')->count();
        $pendingLicenses = License::where('approval_status', 'pending')->count();
        
        // Notifikasi untuk Super Admin
        $recentNotifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        $unreadNotifications = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();
        
        // Tambahkan link untuk redirect notifikasi
        foreach ($recentNotifications as $notif) {
            if ($notif->type == 'registration_pending') {
                $data = json_decode($notif->data, true);
                $notif->link = route('admin.approvals.show', $data['license_id'] ?? 0);
            } else {
                $notif->link = '#';
            }
            $notif->icon = $notif->type == 'registration_pending' ? '📝' : '🔔';
        }
        
        return view('dashboard.superadmin', compact(
            'totalInstansi', 'instansiBaru', 'totalUser', 'userAktif', 
            'totalAsset', 'pendingApproval', 'pendingLicenses',
            'recentNotifications', 'unreadNotifications'
        ));
    }

    public function adminDashboard()
    {
        $user      = Auth::user();
        $instansiId = $user->InstansiID;
 
        // ─── Data lisensi ────────────────────────────────────────────
        // Ini yang dibutuhkan oleh view dashboard (blok status lisensi)
        $license  = $user->license;
        $sisaHari = $license ? $license->daysRemaining() : 0;
 
        // ─── Statistik aset ──────────────────────────────────────────
        $totalAsset = Asset::where('InstansiID', $instansiId)->count();
 
        $assetBaru  = Asset::where('InstansiID', $instansiId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
 
        $assetRusak = Asset::where('InstansiID', $instansiId)
            ->whereIn('kondisi', [
                'rusak_ringan',
                'rusak_berat',
                'tidak_berfungsi'
            ])
            ->count();
 
        $totalKategori = Kategori::where('InstansiID', $instansiId)->count();
        $totalLokasi   = LokasiAsset::where('InstansiID', $instansiId)->count();
 
        // ─── Pengajuan penghapusan ───────────────────────────────────
        $penghapusanPending = Penghapusan::whereHas('asset', fn($q) => $q->where('InstansiID', $instansiId))
            ->where('status_penghapusan', 'diajukan')
            ->count();

        $penghapusanList = Penghapusan::with('asset')
            ->whereHas('asset', fn($q) => $q->where('InstansiID', $instansiId))
            ->where('status_penghapusan', 'diajukan')
            ->latest()
            ->take(5)
            ->get();
 
        // ─── Kerusakan terbaru ───────────────────────────────────────
        $kerusakanTerbaru = Kerusakan::with(['asset.lokasi'])
            ->whereHas('asset', fn($q) => $q->where('InstansiID', $instansiId))
            ->latest()
            ->take(5)
            ->get();
 
        return view('dashboard.admin', compact(
            'license',
            'sisaHari',
            'totalAsset',
            'assetBaru',
            'assetRusak',
            'totalKategori',
            'totalLokasi',
            'penghapusanPending',
            'penghapusanList',
            'kerusakanTerbaru',
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