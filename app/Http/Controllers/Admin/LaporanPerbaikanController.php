<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perbaikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanPerbaikanController extends Controller
{
    public function index(Request $request)
    {
        $instansiId = Auth::user()->InstansiID;

        $query = Perbaikan::with(['kerusakan.asset', 'kerusakan.lokasi', 'teknisi'])
            ->where('InstansiID', $instansiId)
            ->whereIn('status', ['selesai', 'tidak_bisa_diperbaiki']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_perbaikan', 'LIKE', "%{$search}%")
                  ->orWhereHas('teknisi', fn($u) => $u->where('name', 'LIKE', "%{$search}%"))
                  ->orWhereHas('kerusakan.asset', fn($a) => $a->where('nama_asset', 'LIKE', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('selesai_perbaikan', $request->bulan);
        }

        $laporanList = $query->orderByDesc('selesai_perbaikan')->paginate(12);

        $summary = [
            'total'      => Perbaikan::where('InstansiID', $instansiId)->whereIn('status', ['selesai', 'tidak_bisa_diperbaiki'])->count(),
            'selesai'    => Perbaikan::where('InstansiID', $instansiId)->where('status', 'selesai')->count(),
            'tidak_bisa' => Perbaikan::where('InstansiID', $instansiId)->where('status', 'tidak_bisa_diperbaiki')->count(),
        ];

        return view('admin.laporan_perbaikan.index', compact('laporanList', 'summary'));
    }

    // Tandai sudah dibaca + redirect ke PDF
    public function lihat($id)
    {
        $perbaikan = Perbaikan::where('InstansiID', Auth::user()->InstansiID)
            ->findOrFail($id);

        // Tandai sudah dibaca jika belum
        if (!$perbaikan->dibaca_admin) {
            $perbaikan->update(['dibaca_admin' => true]);
        }

        return redirect()->route('riwayat.pdf', $id);
    }
}