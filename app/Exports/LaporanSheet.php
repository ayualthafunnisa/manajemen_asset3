<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class LaporanSheet implements FromArray, WithTitle, WithStyles, ShouldAutoSize
{
    private array $headingMap = [
        'asset'       => ['No','Kode Asset','Nama Asset','Kategori','Lokasi','Merk','Serial Number','Kondisi','Status','Tanggal Masuk'],
        'kerusakan'   => ['No','Kode Laporan','Nama Asset','Kode Asset','Jenis Kerusakan','Deskripsi Kerusakan','Tingkat Kerusakan','Lokasi','Status Perbaikan','Pelapor','Tanggal Laporan'],
        'penghapusan' => ['No','Kode Asset','Nama Asset','Kategori','Alasan Penghapusan','Status','Diajukan Oleh','Tanggal'],
        'penyusutan'  => ['No','Kode Asset','Nama Asset','Nilai Awal (Rp)','Nilai Buku (Rp)','Penyusutan/Tahun (Rp)','Metode','Masa Manfaat (Tahun)'],
    ];

    private array $titleMap = [
        'asset'       => 'LAPORAN DATA ASSET',
        'kerusakan'   => 'LAPORAN KERUSAKAN ASSET',
        'penghapusan' => 'LAPORAN PENGHAPUSAN ASSET',
        'penyusutan'  => 'LAPORAN PENYUSUTAN ASSET',
    ];

    private int $rowTitle  = 1;
    private int $rowInfo   = 2;
    private int $rowBlank  = 3;
    private int $rowHeader = 4;
    private int $rowData   = 5;

    public function __construct(
        protected array  $data,
        protected string $jenisLaporan,
        protected Carbon $startDate,
        protected Carbon $endDate
    ) {}

    public function title(): string
    {
        return match ($this->jenisLaporan) {
            'asset'       => 'Data Asset',
            'kerusakan'   => 'Kerusakan Asset',
            'penghapusan' => 'Penghapusan Asset',
            'penyusutan'  => 'Penyusutan Asset',
            default       => 'Laporan',
        };
    }

    // ── Build all rows manually (no WithHeadings to avoid duplication) ────────

    public function array(): array
    {
        $headings = $this->headingMap[$this->jenisLaporan] ?? ['No', 'Data'];
        $colCount = count($headings);
        $rows     = [];

        // Row 1: Title
        $titleRow = array_fill(0, $colCount, null);
        $titleRow[0] = $this->titleMap[$this->jenisLaporan] ?? 'LAPORAN';
        $rows[] = $titleRow;

        // Row 2: Info
        $infoRow = array_fill(0, $colCount, null);
        $infoRow[0] = 'SMK Informatika Utama'
            . '     |     Periode: ' . $this->startDate->format('d/m/Y') . ' s/d ' . $this->endDate->format('d/m/Y')
            . '     |     Dicetak: ' . Carbon::now()->format('d/m/Y H:i');
        $rows[] = $infoRow;

        // Row 3: Blank spacer
        $rows[] = array_fill(0, $colCount, null);

        // Row 4: Column headers
        $rows[] = $headings;

        // Rows 5+: Data
        $items = $this->data['items'] ?? collect();
        foreach ($items->values() as $i => $item) {
            $rows[] = match ($this->jenisLaporan) {
                'asset'       => $this->rowAsset($item, $i),
                'kerusakan'   => $this->rowKerusakan($item, $i),
                'penghapusan' => $this->rowPenghapusan($item, $i),
                'penyusutan'  => $this->rowPenyusutan($item, $i),
                default       => [$i + 1],
            };
        }

        // Summary row
        $count  = $items->count();
        $total  = $this->data['total'] ?? 0;
        $sumRow = array_fill(0, $colCount, null);
        $sumRow[0] = 'Total: ' . $count . ' dari ' . $total . ' record';
        $rows[] = $sumRow;

        return $rows;
    }

    // ── Row builders ─────────────────────────────────────────────────────────

    private function rowAsset($item, int $i): array
    {
        return [
            $i + 1,
            $item->kode_asset ?? '-',
            $item->nama_asset ?? '-',
            $item->kategori->NamaKategori ?? '-',
            $item->lokasi->NamaLokasi ?? '-',
            $item->merk ?? '-',
            $item->serial_number ?? '-',
            ucfirst(str_replace('_', ' ', $item->kondisi ?? '-')),
            ucfirst(str_replace('_', ' ', $item->status_asset ?? '-')),
            $item->created_at ? Carbon::parse($item->created_at)->format('d/m/Y') : '-',
        ];
    }

    private function rowKerusakan($item, int $i): array
    {
        return [
            $i + 1,
            $item->kode_laporan ?? '-',
            $item->asset->nama_asset ?? '-',
            $item->asset->kode_asset ?? '-',
            $item->jenis_kerusakan ?? '-',
            $item->deskripsi_kerusakan ?? '-',
            ucfirst($item->tingkat_kerusakan ?? '-'),
            $item->lokasi->NamaLokasi ?? '-',
            ucfirst($item->status_perbaikan ?? '-'),
            $item->pelapor->name ?? '-',
            $item->tanggal_laporan
                ? Carbon::parse($item->tanggal_laporan)->format('d/m/Y')
                : ($item->created_at ? Carbon::parse($item->created_at)->format('d/m/Y') : '-'),
        ];
    }

    private function rowPenghapusan($item, int $i): array
    {
        return [
            $i + 1,
            $item->asset->kode_asset ?? '-',
            $item->asset->nama_asset ?? '-',
            $item->asset->kategori->NamaKategori ?? $item->asset->kategori->nama_kategori ?? '-',
            $item->alasan ?? $item->alasan_penghapusan ?? $item->keterangan ?? '-',
            ucfirst($item->status ?? $item->status_penghapusan ?? '-'),
            optional($item->pengaju)->name ?? '-',
            $item->tanggal_penghapusan
                ? Carbon::parse($item->tanggal_penghapusan)->format('d/m/Y')
                : ($item->created_at ? Carbon::parse($item->created_at)->format('d/m/Y') : '-'),
        ];
    }

    private function rowPenyusutan($item, int $i): array
    {
        return [
            $i + 1,
            $item->asset->kode_asset ?? '-',
            $item->asset->nama_asset ?? '-',
            (int) ($item->nilai_awal ?? 0),
            (int) ($item->nilai_buku ?? $item->nilai_akhir ?? 0),
            (int) ($item->penyusutan_per_tahun ?? $item->nilai_penyusutan ?? 0),
            $item->metode ?? $item->metode_penyusutan ?? '-',
            $item->asset->umur_ekonomis ?? '-',
        ];
    }

    // ── Styles ───────────────────────────────────────────────────────────────

    public function styles(Worksheet $sheet): array
    {
        $items    = $this->data['items'] ?? collect();
        $count    = $items->count();
        $headings = $this->headingMap[$this->jenisLaporan] ?? ['No', 'Data'];
        $colCount = count($headings);
        $lastCol  = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colCount);
        $lastData = $this->rowData + $count - 1;
        $sumRow   = $lastData + 1;

        // ── Row 1: Title ──────────────────────────────────────────────────
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'name' => 'Arial',
                            'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID,
                            'startColor' => ['argb' => 'FF1A3C5E']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical'   => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(36);

        // ── Row 2: Info periode ───────────────────────────────────────────
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['size' => 9, 'name' => 'Arial', 'italic' => true,
                            'color' => ['argb' => 'FF444444']],
            'fill'      => ['fillType' => Fill::FILL_SOLID,
                            'startColor' => ['argb' => 'FFF0F4F8']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical'   => Alignment::VERTICAL_CENTER],
            'borders'   => ['bottom' => ['borderStyle' => Border::BORDER_THIN,
                                         'color'       => ['argb' => 'FFCCCCCC']]],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(20);

        // ── Row 3: Blank spacer ───────────────────────────────────────────
        $sheet->getRowDimension(3)->setRowHeight(8);

        // ── Row 4: Column headers ─────────────────────────────────────────
        $sheet->getStyle("A4:{$lastCol}4")->applyFromArray([
            'font'      => ['bold' => true, 'size' => 9, 'name' => 'Arial',
                            'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID,
                            'startColor' => ['argb' => 'FF2E6DA4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical'   => Alignment::VERTICAL_CENTER,
                            'wrapText'   => true],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN,
                                             'color'       => ['argb' => 'FF1A3C5E']]],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(30);

        // ── Data rows ─────────────────────────────────────────────────────
        if ($count > 0) {
            for ($r = $this->rowData; $r <= $lastData; $r++) {
                $bg = ($r % 2 === 0) ? 'FFF0F4F8' : 'FFFFFFFF';
                $sheet->getStyle("A{$r}:{$lastCol}{$r}")->applyFromArray([
                    'font'      => ['size' => 9, 'name' => 'Arial',
                                    'color' => ['argb' => 'FF333333']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID,
                                    'startColor' => ['argb' => $bg]],
                    'borders'   => [
                        'bottom' => ['borderStyle' => Border::BORDER_THIN,
                                     'color'       => ['argb' => 'FFD8E4F0']],
                        'left'   => ['borderStyle' => Border::BORDER_THIN,
                                     'color'       => ['argb' => 'FFD8E4F0']],
                        'right'  => ['borderStyle' => Border::BORDER_THIN,
                                     'color'       => ['argb' => 'FFD8E4F0']],
                    ],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension($r)->setRowHeight(18);
            }

            // No. column: center align
            $sheet->getStyle("A{$this->rowData}:A{$lastData}")
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Outline seluruh tabel
            $sheet->getStyle("A4:{$lastCol}{$lastData}")->applyFromArray([
                'borders' => ['outline' => ['borderStyle' => Border::BORDER_MEDIUM,
                                            'color'       => ['argb' => 'FF1A3C5E']]],
            ]);

            // Penyusutan: format currency + right-align
            if ($this->jenisLaporan === 'penyusutan') {
                foreach (['D', 'E', 'F'] as $col) {
                    $sheet->getStyle("{$col}{$this->rowData}:{$col}{$lastData}")
                        ->getNumberFormat()->setFormatCode('#,##0');
                    $sheet->getStyle("{$col}{$this->rowData}:{$col}{$lastData}")
                        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                }
            }

            // ── Summary row ───────────────────────────────────────────────
            $sheet->mergeCells("A{$sumRow}:{$lastCol}{$sumRow}");
            $sheet->getStyle("A{$sumRow}:{$lastCol}{$sumRow}")->applyFromArray([
                'font'      => ['bold' => true, 'size' => 9, 'name' => 'Arial',
                                'italic' => true, 'color' => ['argb' => 'FF1A3C5E']],
                'fill'      => ['fillType' => Fill::FILL_SOLID,
                                'startColor' => ['argb' => 'FFE2EBF5']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT,
                                'vertical'   => Alignment::VERTICAL_CENTER],
                'borders'   => ['outline' => ['borderStyle' => Border::BORDER_MEDIUM,
                                              'color'       => ['argb' => 'FF1A3C5E']]],
            ]);
            $sheet->getRowDimension($sumRow)->setRowHeight(20);
        }

        // Freeze panes di bawah header
        $sheet->freezePane('A5');

        // Kolom No. lebar manual
        $sheet->getColumnDimension('A')->setAutoSize(false);
        $sheet->getColumnDimension('A')->setWidth(6);

        return [];
    }
}