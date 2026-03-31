<?php

namespace App\Exports\Component\Area;

use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Events\AfterSheet;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use App\Exports\Component\Style\SheetStyle;

class PublicHoldingHeader
{
    /**
     * 標題範圍
     * @var string
     */
    public const MENU_RANGE = 'A1:E1';

    public readonly Sheet $sheet;

    public readonly Worksheet $worksheet;

    /**
     * @param \Maatwebsite\Excel\Events\AfterSheet $event
     */
    public function __construct(
        public readonly AfterSheet $event
    ) {
        $sheet = $event->sheet;
        $worksheet = $sheet->getDelegate();

        $this->sheet = $sheet;
        $this->worksheet = $worksheet;
    }

    /**
     * 設定 header 樣式
     *
     * @return void
     */
    public function setHeaderStyle(): void
    {
        // 設定高
        $this->sheet->getRowDimension(1)->setRowHeight(25);

        // 凍結欄
        $this->worksheet->freezePane('A2');

        SheetStyle::setFontBold($this->worksheet, self::MENU_RANGE);
        SheetStyle::setColor($this->worksheet, self::MENU_RANGE, colorCode: 'BBFFBB');
        SheetStyle::multipleSetWith($this->worksheet, self::MENU_RANGE, size: 30);
    }
}
