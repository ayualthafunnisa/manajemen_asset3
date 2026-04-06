<?php
// app/Exports/LaporanExport.php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class LaporanExport implements FromArray, WithHeadings, WithStyles
{
    protected $data;
    protected $request;
    
    public function __construct($data, $request)
    {
        $this->data = $data;
        $this->request = $request;
    }
    
    public function array(): array
    {
        $jenis = $this->request->jenis_laporan;
        $rows = [];
        
        // Header informasi laporan
        $rows[] = ['LAPORAN ' . strtoupper($jenis)];
        $rows[] = ['Tanggal Cetak: ' . Carbon::now()->format('d/m/Y H:i:s')];
        
        if ($this->request->tanggal_mulai && $this->request->tanggal_akhir) {
            $rows[] = ['Periode: ' . Carbon::parse($this->request->tanggal_mulai)->format('d/m/Y') . 
                      ' - ' . Carbon::parse($this->request->tanggal_akhir)->format('d/m/Y')];
        }
        
        $rows[] = []; // Baris kosong
        
        // Header kolom
        switch ($jenis) {
            case 'asset':
                $rows[] = ['No', 'Kode Asset', 'Nama Asset', 'Kategori', 'Lokasi', 'Merk', 
                          'Serial Number', 'Kondisi', 'Status', 'Tanggal Dibuat'];
                foreach ($this->data['items'] as $index => $item) {
                    $rows[] = [
                        $index + 1,
                        $item->kode_asset,
                        $item->nama_asset,
                        $item->kategori->NamaKategori ?? '-',
                        $item->lokasi->NamaLokasi ?? '-',
                        $item->merk ?? '-',
                        $item->serial_number ?? '-',
                        $this->getKondisiText($item->kondisi),
                        $this->getStatusText($item->status_asset),
                        Carbon::parse($item->created_at)->format('d/m/Y')
                    ];
                }
                break;
                
            case 'kerusakan':
                $rows[] = ['No', 'Asset', 'Kode Asset', 'Deskripsi', 'Tanggal Laporan', 
                          'Status', 'Lokasi', 'Pelapor'];
                foreach ($this->data['items'] as $index => $item) {
                    $rows[] = [
                        $index + 1,
                        $item->asset->nama_asset ?? '-',
                        $item->asset->kode_asset ?? '-',
                        $item->deskripsi_kerusakan,
                        Carbon::parse($item->tanggal_laporan)->format('d/m/Y'),
                        $item->status_perbaikan,
                        $item->lokasi_kerusakan ?? '-',
                        $item->user->name ?? '-'
                    ];
                }
                break;
                
            case 'penghapusan':
                $rows[] = ['No', 'Asset', 'Kode Asset', 'Tanggal Penghapusan', 'Alasan', 
                          'Status', 'Disetujui Oleh'];
                foreach ($this->data['items'] as $index => $item) {
                    $rows[] = [
                        $index + 1,
                        $item->asset->nama_asset ?? '-',
                        $item->asset->kode_asset ?? '-',
                        Carbon::parse($item->tanggal_penghapusan)->format('d/m/Y'),
                        $item->alasan_penghapusan,
                        $item->status_penghapusan,
                        $item->approved_by ?? '-'
                    ];
                }
                break;
                
            case 'penyusutan':
                $rows[] = ['No', 'Asset', 'Kode Asset', 'Nilai Awal', 'Nilai Akhir', 
                          'Penyusutan per Tahun', 'Metode', 'Tanggal Mulai', 'Masa Manfaat'];
                foreach ($this->data['items'] as $index => $item) {
                    $rows[] = [
                        $index + 1,
                        $item->asset->nama_asset ?? '-',
                        $item->asset->kode_asset ?? '-',
                        'Rp ' . number_format($item->nilai_awal, 0, ',', '.'),
                        'Rp ' . number_format($item->nilai_akhir, 0, ',', '.'),
                        $item->penyusutan_per_tahun . '%',
                        $item->metode_penyusutan,
                        Carbon::parse($item->tanggal_mulai)->format('d/m/Y'),
                        $item->masa_manfaat . ' tahun'
                    ];
                }
                break;
        }
        
        $rows[] = [];
        $rows[] = ['Total Data: ' . $this->data['filtered'] . ' dari ' . $this->data['total']];
        
        return $rows;
    }
    
    public function headings(): array
    {
        return [];
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['italic' => true]],
            4 => ['font' => ['bold' => true]],
        ];
    }
    
    private function getKondisiText($kondisi)
    {
        $labels = [
            'baik' => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat' => 'Rusak Berat',
            'tidak_berfungsi' => 'Tidak Berfungsi',
            'hilang' => 'Hilang'
        ];
        return $labels[$kondisi] ?? $kondisi;
    }
    
    private function getStatusText($status)
    {
        $labels = [
            'aktif' => 'Aktif',
            'non_aktif' => 'Non Aktif',
            'dipinjam' => 'Dipinjam',
            'diperbaiki' => 'Diperbaiki'
        ];
        return $labels[$status] ?? $status;
    }
}