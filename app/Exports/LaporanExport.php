<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class LaporanExport implements WithMultipleSheets
{
    public function __construct(
        protected array  $data,
        protected string $jenisLaporan,
        protected Carbon $startDate,
        protected Carbon $endDate
    ) {}

    public function sheets(): array
    {
        return [
            new LaporanSheet($this->data, $this->jenisLaporan, $this->startDate, $this->endDate),
        ];
    }
}

// ─────────────────────────────────────────────────────────────────────────────

class LaporanSheet implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    // Row layout constants
    private const ROW_TITLE   = 1;
    private const ROW_PERIODE = 2;
    private const ROW_BLANK   = 3;
    private const ROW_HEADER  = 4;
    private const ROW_DATA    = 5;   // first data row

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

    // ── Headings row (row 4) ─────────────────────────────────────────────────

    public function headings(): array
    {
        return match ($this->jenisLaporan) {
            'asset' => [
                'No', 'Kode Asset', 'Nama Asset', 'Kategori', 'Lokasi',
                'Merk', 'Serial Number', 'Kondisi', 'Status', 'Tanggal Masuk',
            ],
            'kerusakan' => [
                'No', 'Kode Laporan', 'Nama Asset', 'Kode Asset',
                'Jenis Kerusakan', 'Deskripsi Kerusakan',
                'Tingkat Kerusakan', 'Lokasi', 'Status Perbaikan',
                'Pelapor', 'Tanggal Laporan',
            ],
            'penghapusan' => [
                'No', 'Kode Asset', 'Nama Asset', 'Kategori',
                'Alasan Penghapusan', 'Status', 'Diajukan Oleh', 'Tanggal',
            ],
            'penyusutan' => [
                'No', 'Kode Asset', 'Nama Asset',
                'Nilai Awal (Rp)', 'Nilai Buku (Rp)', 'Penyusutan/Tahun (Rp)',
                'Metode', 'Masa Manfaat (Tahun)',
            ],
            default => ['No', 'Data'],
        };
    }

    // ── Data rows ────────────────────────────────────────────────────────────

    public function collection(): Collection
    {
        $items = $this->data['items'] ?? collect();

        return $items->values()->map(fn($item, $i) => match ($this->jenisLaporan) {
            'asset'       => $this->rowAsset($item, $i),
            'kerusakan'   => $this->rowKerusakan($item, $i),
            'penghapusan' => $this->rowPenghapusan($item, $i),
            'penyusutan'  => $this->rowPenyusutan($item, $i),
            default       => [$i + 1],
        });
    }

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
            optional($item->user)->name ?? '-',
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
            $item->masa_manfaat ?? '-',
        ];
    }

    // ── Styles ───────────────────────────────────────────────────────────────

    public function styles(Worksheet $sheet): array
    {
        $items    = $this->data['items'] ?? collect();
        $count    = $items->count();
        $colCount = count($this->headings());
        $lastCol  = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colCount);
        $lastData = self::ROW_DATA + $count - 1;   // last data row (or ROW_HEADER if empty)

        // ── Row 1 : Title ─────────────────────────────────────────────────
        $titleMap = [
            'asset'       => 'LAPORAN DATA ASSET',
            'kerusakan'   => 'LAPORAN KERUSAKAN ASSET',
            'penghapusan' => 'LAPORAN PENGHAPUSAN ASSET',
            'penyusutan'  => 'LAPORAN PENYUSUTAN ASSET',
        ];
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', $titleMap[$this->jenisLaporan] ?? 'LAPORAN');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'name' => 'Arial',
                            'color' => ['argb' => 'FF000000']],
            'fill'      => ['fillType' => Fill::FILL_SOLID,
                            'startColor' => ['argb' => 'FFD9D9D9']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical'   => Alignment::VERTICAL_CENTER],
            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_MEDIUM,
                                          'color'       => ['argb' => 'FF000000']]],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(32);

        // ── Row 2 : Info periode ──────────────────────────────────────────
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2',
            'SMK Informatika Utama'
            . '     Periode: ' . $this->startDate->format('d/m/Y') . ' s/d ' . $this->endDate->format('d/m/Y')
            . '     Dicetak: ' . Carbon::now()->format('d/m/Y H:i')
        );
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['size' => 9, 'name' => 'Arial', 'italic' => true],
            'fill'      => ['fillType' => Fill::FILL_SOLID,
                            'startColor' => ['argb' => 'FFF2F2F2']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical'   => Alignment::VERTICAL_CENTER],
            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN,
                                          'color'       => ['argb' => 'FF000000']]],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(18);

        // ── Row 3 : Blank spacer ──────────────────────────────────────────
        $sheet->getRowDimension(3)->setRowHeight(6);

        // ── Row 4 : Column headers ────────────────────────────────────────
        $sheet->getStyle("A4:{$lastCol}4")->applyFromArray([
            'font'      => ['bold' => true, 'size' => 9, 'name' => 'Arial',
                            'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID,
                            'startColor' => ['argb' => 'FF404040']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical'   => Alignment::VERTICAL_CENTER,
                            'wrapText'   => true],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN,
                                             'color'       => ['argb' => 'FF000000']]],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(28);

        // ── Data rows ─────────────────────────────────────────────────────
        if ($count > 0) {
            for ($r = self::ROW_DATA; $r <= $lastData; $r++) {
                $bg = ($r % 2 === 0) ? 'FFF2F2F2' : 'FFFFFFFF';
                $sheet->getStyle("A{$r}:{$lastCol}{$r}")->applyFromArray([
                    'font' => ['size' => 9, 'name' => 'Arial'],
                    'fill' => ['fillType' => Fill::FILL_SOLID,
                               'startColor' => ['argb' => $bg]],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN,
                                                   'color'       => ['argb' => 'FFB0B0B0']]],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ]);
            }

            // No. column: center align
            $sheet->getStyle("A4:A{$lastData}")
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Penyusutan: right-align & number format for currency columns
            if ($this->jenisLaporan === 'penyusutan') {
                foreach (['D', 'E', 'F'] as $col) {
                    $sheet->getStyle("{$col}" . self::ROW_DATA . ":{$col}{$lastData}")
                        ->getNumberFormat()->setFormatCode('#,##0');
                    $sheet->getStyle("{$col}" . self::ROW_DATA . ":{$col}{$lastData}")
                        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                }
            }

            // ── Summary row ───────────────────────────────────────────────
            $sumRow = $lastData + 1;
            $sheet->mergeCells("A{$sumRow}:{$lastCol}{$sumRow}");
            $sheet->setCellValue(
                "A{$sumRow}",
                'Total Data: ' . $count . ' dari ' . ($this->data['total'] ?? 0) . ' record'
            );
            $sheet->getStyle("A{$sumRow}:{$lastCol}{$sumRow}")->applyFromArray([
                'font'      => ['bold' => true, 'size' => 9, 'name' => 'Arial',
                                'italic' => true],
                'fill'      => ['fillType' => Fill::FILL_SOLID,
                                'startColor' => ['argb' => 'FFD9D9D9']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT,
                                'vertical'   => Alignment::VERTICAL_CENTER],
                'borders'   => ['outline' => ['borderStyle' => Border::BORDER_MEDIUM,
                                              'color'       => ['argb' => 'FF000000']]],
            ]);
            $sheet->getRowDimension($sumRow)->setRowHeight(18);
        }

        // Freeze panes below header row
        $sheet->freezePane('A5');

        return [];
    }
}