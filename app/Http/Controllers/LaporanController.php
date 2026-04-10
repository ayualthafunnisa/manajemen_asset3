<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Kerusakan;
use App\Models\Penghapusan;
use App\Models\Penyusutan;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    public function preview(Request $request)
    {
        [$startDate, $endDate, $error] = $this->parseDateRange($request->input('date_range'));

        if ($error) {
            return response()->json(['error' => $error], 400);
        }

        $jenisLaporan = $request->input('jenis_laporan', 'asset');
        $data = $this->getLaporanData($jenisLaporan, $startDate, $endDate,
            $request->input('status'), $request->input('kondisi'));

        return response()->json([
            'html'       => view('laporan.partials.table', compact('data', 'jenisLaporan'))->render(),
            'total'      => $data['total'],
            'filtered'   => $data['filtered'],
            'start_date' => $startDate->format('d-m-Y'),
            'end_date'   => $endDate->format('d-m-Y'),
        ]);
    }

    public function exportPdf(Request $request)
    {
        [$startDate, $endDate, $error] = $this->parseDateRange($request->input('date_range'));

        if ($error) {
            return redirect()->route('laporan.index')->with('error', $error);
        }

        $jenisLaporan = $request->input('jenis_laporan', 'asset');
        $data = $this->getLaporanData($jenisLaporan, $startDate, $endDate,
            $request->input('status'), $request->input('kondisi'));

        $html = view('laporan.pdf', compact('data', 'jenisLaporan', 'startDate', 'endDate'))->render();
        $pdf  = Pdf::loadHTML($html)->setPaper('a4', 'landscape');

        return $pdf->download('laporan_' . $jenisLaporan . '_' . $startDate->format('d-m-Y') . '_' . $endDate->format('d-m-Y') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        [$startDate, $endDate, $error] = $this->parseDateRange($request->input('date_range'));

        if ($error) {
            return redirect()->route('laporan.index')->with('error', $error);
        }

        $jenisLaporan = $request->input('jenis_laporan', 'asset');
        $data = $this->getLaporanData($jenisLaporan, $startDate, $endDate,
            $request->input('status'), $request->input('kondisi'));

        return Excel::download(
            new LaporanExport($data, $jenisLaporan, $startDate, $endDate),
            'laporan_' . $jenisLaporan . '_' . $startDate->format('d-m-Y') . '_' . $endDate->format('d-m-Y') . '.xlsx'
        );
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Parse "DD-MM-YYYY - DD-MM-YYYY" → [Carbon $start, Carbon $end, ?string $error]
     */
    private function parseDateRange(?string $dateRange): array
    {
        if (empty($dateRange) || !str_contains($dateRange, ' - ')) {
            return [null, null, 'Pilih rentang tanggal terlebih dahulu!'];
        }

        [$from, $to] = array_map('trim', explode(' - ', $dateRange, 2));

        try {
            $startDate = Carbon::createFromFormat('d-m-Y', $from)->startOfDay();
            $endDate   = Carbon::createFromFormat('d-m-Y', $to)->endOfDay();
        } catch (\Exception) {
            return [null, null, 'Format tanggal tidak valid! Gunakan format DD-MM-YYYY.'];
        }

        if ($startDate->gt($endDate)) {
            return [null, null, 'Tanggal awal tidak boleh lebih besar dari tanggal akhir.'];
        }

        return [$startDate, $endDate, null];
    }

    private function getLaporanData(string $jenisLaporan, Carbon $startDate, Carbon $endDate, ?string $status = null, ?string $kondisi = null): array
    {
        switch ($jenisLaporan) {

            case 'asset':
                $query = Asset::with(['kategori', 'lokasi'])
                    ->whereBetween('created_at', [$startDate, $endDate]);

                if ($status)  $query->where('status_asset', $status);
                if ($kondisi) $query->where('kondisi', $kondisi);

                $items = $query->orderByDesc('created_at')->get();
                $total = Asset::count();
                break;

            case 'kerusakan':
                // Relasi: asset, lokasi, pelapor (bukan 'user')
                $query = Kerusakan::with(['asset', 'lokasi', 'pelapor'])
                    ->whereBetween('tanggal_laporan', [$startDate->toDateString(), $endDate->toDateString()]);

                $items = $query->orderByDesc('tanggal_laporan')->get();
                $total = Kerusakan::count();
                break;

            case 'penghapusan':
                $query = Penghapusan::with(['asset.kategori', 'user']);

                if (\Schema::hasColumn('penghapusans', 'tanggal_penghapusan')) {
                    $query->whereBetween('tanggal_penghapusan', [$startDate->toDateString(), $endDate->toDateString()]);
                } else {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }

                $items = $query->orderByDesc('created_at')->get();
                $total = Penghapusan::count();
                break;

            case 'penyusutan':
                $items = Penyusutan::with(['asset'])
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->orderByDesc('created_at')
                    ->get();
                $total = Penyusutan::count();
                break;

            default:
                $items = collect();
                $total = 0;
        }

        return [
            'items'      => $items,
            'total'      => $total,
            'filtered'   => $items->count(),
            'jenis'      => $jenisLaporan,
            'start_date' => $startDate,
            'end_date'   => $endDate,
        ];
    }
}