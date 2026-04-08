<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Kerusakan;
use App\Models\Penghapusan;
use App\Models\Penyusutan;
use App\Models\KategoriAsset;
use App\Models\LokasiAsset;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        // Data awal untuk ditampilkan (opsional)
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        $penjualan = Asset::with(['kategori', 'lokasi'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('laporan.index', compact('penjualan', 'startDate', 'endDate'));
    }
    
    public function filter(Request $request)
    {
        $dateRange = $request->input('date_range');
        $jenisLaporan = $request->input('jenis_laporan', 'asset');
        $status = $request->input('status');
        $kondisi = $request->input('kondisi');

        // Validasi date range
        if (empty($dateRange) || !str_contains($dateRange, ' - ')) {
            if ($request->ajax()) {
                return response()->json([
                    'error' => 'Pilih rentang tanggal terlebih dahulu!'
                ], 400);
            }
            return redirect()->route('laporan.index')->with('error', 'Pilih rentang tanggal terlebih dahulu!');
        }

        $date = explode(' - ', $dateRange);

        try {
            $startDate = Carbon::createFromFormat('d-m-Y', trim($date[0]))->startOfDay();
            $endDate   = Carbon::createFromFormat('d-m-Y', trim($date[1]))->endOfDay();
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'error' => 'Format tanggal tidak valid!'
                ], 400);
            }
            return redirect()->route('laporan.index')->with('error', 'Format tanggal tidak valid!');
        }

        // Ambil data berdasarkan jenis laporan
        $data = $this->getLaporanData($jenisLaporan, $startDate, $endDate, $status, $kondisi);
        
        if ($request->ajax()) {
            return response()->json([
                'html' => view('laporan.partials.table', compact('data', 'jenisLaporan'))->render(),
                'total' => $data['total'],
                'filtered' => $data['filtered']
            ]);
        }
        
        return view('laporan.index', compact('data', 'startDate', 'endDate', 'jenisLaporan'));
    }
    
    public function preview(Request $request)
    {
        $dateRange = $request->input('date_range');
        $jenisLaporan = $request->input('jenis_laporan', 'asset');
        $status = $request->input('status');
        $kondisi = $request->input('kondisi');

        // Validasi date range
        if (empty($dateRange) || !str_contains($dateRange, ' - ')) {
            return response()->json([
                'error' => 'Pilih rentang tanggal terlebih dahulu!'
            ], 400);
        }

        $date = explode(' - ', $dateRange);

        try {
            $startDate = Carbon::createFromFormat('d-m-Y', trim($date[0]))->startOfDay();
            $endDate   = Carbon::createFromFormat('d-m-Y', trim($date[1]))->endOfDay();
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Format tanggal tidak valid!'
            ], 400);
        }

        // Ambil data berdasarkan jenis laporan
        $data = $this->getLaporanData($jenisLaporan, $startDate, $endDate, $status, $kondisi);
    
        // Tambahkan tanggal ke response
        return response()->json([
            'html' => view('laporan.partials.table', compact('data', 'jenisLaporan'))->render(),
            'total' => $data['total'],
            'filtered' => $data['filtered'],
            'start_date' => $startDate->format('d-m-Y'),
            'end_date' => $endDate->format('d-m-Y')
        ]);
    }
    
    public function exportPdf(Request $request)
    {
        $dateRange = $request->input('date_range');
        $jenisLaporan = $request->input('jenis_laporan', 'asset');
        $status = $request->input('status');
        $kondisi = $request->input('kondisi');

        // Validasi date range
        if (empty($dateRange) || !str_contains($dateRange, ' - ')) {
            return redirect()->route('laporan.index')->with('error', 'Pilih rentang tanggal terlebih dahulu!');
        }

        $date = explode(' - ', $dateRange);

        try {
            $startDate = Carbon::createFromFormat('d-m-Y', trim($date[0]))->startOfDay();
            $endDate   = Carbon::createFromFormat('d-m-Y', trim($date[1]))->endOfDay();
        } catch (\Exception $e) {
            return redirect()->route('laporan.index')->with('error', 'Format tanggal tidak valid!');
        }

        // Ambil data berdasarkan jenis laporan
        $data = $this->getLaporanData($jenisLaporan, $startDate, $endDate, $status, $kondisi);
        
        // Gunakan loadHTML dengan compact
        $html = view('laporan.pdf', compact('data', 'jenisLaporan', 'startDate', 'endDate'))->render();
        
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('a4', 'landscape');
        
        $fileName = 'laporan_' . $jenisLaporan . '_' . $startDate->format('d-m-Y') . '_' . $endDate->format('d-m-Y') . '.pdf';
        
        return $pdf->download($fileName);
    }
    
    public function exportExcel(Request $request)
    {
        $dateRange = $request->input('date_range');
        $jenisLaporan = $request->input('jenis_laporan', 'asset');
        $status = $request->input('status');
        $kondisi = $request->input('kondisi');

        // Validasi date range
        if (empty($dateRange) || !str_contains($dateRange, ' - ')) {
            return redirect()->route('laporan.index')->with('error', 'Pilih rentang tanggal terlebih dahulu!');
        }

        $date = explode(' - ', $dateRange);

        try {
            $startDate = Carbon::createFromFormat('d-m-Y', trim($date[0]))->startOfDay();
            $endDate   = Carbon::createFromFormat('d-m-Y', trim($date[1]))->endOfDay();
        } catch (\Exception $e) {
            return redirect()->route('laporan.index')->with('error', 'Format tanggal tidak valid!');
        }

        // Ambil data berdasarkan jenis laporan
        $data = $this->getLaporanData($jenisLaporan, $startDate, $endDate, $status, $kondisi);
        
        return Excel::download(new LaporanExport($data, $jenisLaporan, $startDate, $endDate), 
            'laporan_' . $jenisLaporan . '_' . $startDate->format('d-m-Y') . '_' . $endDate->format('d-m-Y') . '.xlsx');
    }
    
    private function getLaporanData($jenisLaporan, $startDate, $endDate, $status = null, $kondisi = null)
    {
        $items = collect();
        $total = 0;
        
        switch ($jenisLaporan) {
            case 'asset':
                $query = Asset::with(['kategori', 'lokasi']);
                
                if ($startDate && $endDate) {
                    $query->whereDate('created_at', '>=', $startDate->toDateString())
                        ->whereDate('created_at', '<=', $endDate->toDateString());
                }
                
                if ($status) {
                    $query->where('status_asset', $status);
                }
                
                if ($kondisi) {
                    $query->where('kondisi', $kondisi);
                }
                
                $items = $query->orderBy('created_at', 'desc')->get();
                $total = Asset::count();
                break;
                
            case 'kerusakan':
                $query = Kerusakan::with(['asset', 'user']);
                
                // Filter tanggal berdasarkan tanggal_laporan
                if ($startDate && $endDate) {
                    $query->whereBetween('tanggal_laporan', [$startDate, $endDate]);
                }
                
                $items = $query->orderBy('tanggal_laporan', 'desc')->get();
                $total = Kerusakan::count();
                break;
                
            case 'penghapusan':
                $query = Penghapusan::with(['asset', 'user']);
                
                // Filter tanggal berdasarkan tanggal_penghapusan
                if ($startDate && $endDate) {
                    $query->whereBetween('tanggal_penghapusan', [$startDate, $endDate]);
                }
                
                $items = $query->orderBy('tanggal_penghapusan', 'desc')->get();
                $total = Penghapusan::count();
                break;
                
            case 'penyusutan':
                $query = Penyusutan::with(['asset']);
                
                // Filter tanggal berdasarkan tanggal_mulai
                if ($startDate && $endDate) {
                    $query->whereBetween('tanggal_mulai', [$startDate, $endDate]);
                }
                
                $items = $query->orderBy('tanggal_mulai', 'desc')->get();
                $total = Penyusutan::count();
                break;
                
            default:
                $items = collect();
                $total = 0;
        }
        
        return [
            'items' => $items,
            'total' => $total,
            'filtered' => $items->count(),
            'jenis' => $jenisLaporan,
            'start_date' => $startDate,
            'end_date' => $endDate
        ];
    }
}