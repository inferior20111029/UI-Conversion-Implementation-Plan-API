<?php

declare(strict_types=1);

namespace App\Support\Tool\Excel;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultipleSheetsExport implements WithMultipleSheets
{
    protected $sheets;

    public function __construct(array $sheets)
    {
        $this->sheets = $sheets;
    }

    public function sheets(): array
    {
        return $this->sheets;
    }
}
