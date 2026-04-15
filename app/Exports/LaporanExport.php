<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

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