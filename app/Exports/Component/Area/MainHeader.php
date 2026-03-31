<?php

namespace App\Exports\Component\Area;

use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Events\AfterSheet;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use App\Exports\Component\Style\SheetStyle;

class MainHeader
{
    /**
     * 戶別資料範圍
     * @var string
     */
    public const BASIC_INFORMATION_RANGE = 'A1';

    /**
     * 土地面積範圍
     * @var string
     */
    public const LAND_AREA_RANGE = 'B1:C1';

    /**
     * 專有面積範圍
     * @var string
     */
    public const EXCLUSIVE_AREA = 'D1:F1';

    /**
     * 約定專用面積範圍
     * @var string
     */
    public const AGREED_DEDICATED_AREA = 'G1';

    /**
     * 標題範圍
     * @var string
     */
    public const TITLE_RANGE = 'A1:G1';

    public const MENU_RANGE = 'A2:G2';

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
        $this->worksheet->freezePane('A3');

        SheetStyle::setFontBold($this->worksheet, self::TITLE_RANGE);

        $this->setBasicInformationStyle();
        $this->setLandAreaStyle();
        $this->setExclusiveAreaStyle();
        $this->setAgreedDedicatedAreaStyle();
    }

    /**
     * 設定基本資料
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $worksheet
     *
     * @return void
     */
    private function setBasicInformationStyle(): void
    {
        SheetStyle::setColor($this->worksheet, self::BASIC_INFORMATION_RANGE, colorCode: 'DCE6F1');
        SheetStyle::multipleSetWith($this->worksheet, self::BASIC_INFORMATION_RANGE, size: 30);
    }

    /**
     * 設定土地面積
     *
     * @return void
     */
    private function setLandAreaStyle(): void
    {
        SheetStyle::mergeCells($this->sheet, self::LAND_AREA_RANGE);
        SheetStyle::setColor($this->worksheet, self::LAND_AREA_RANGE, colorCode: 'FFDAC8');
        SheetStyle::multipleSetWith($this->worksheet, self::LAND_AREA_RANGE, size: 30);
    }

    /**
     * 設定專有面積
     *
     * @return void
     */
    private function setExclusiveAreaStyle(): void
    {
        SheetStyle::mergeCells($this->sheet, self::EXCLUSIVE_AREA);
        SheetStyle::setColor($this->worksheet, self::EXCLUSIVE_AREA, colorCode: 'BBFFBB');
        SheetStyle::multipleSetWith($this->worksheet, self::EXCLUSIVE_AREA, size: 30);
    }

    /**
     * 設定約定專用面積
     *
     * @return void
     */
    private function setAgreedDedicatedAreaStyle(): void
    {
        SheetStyle::setColor($this->worksheet, self::AGREED_DEDICATED_AREA, colorCode: 'E6CAFF');
        SheetStyle::multipleSetWith($this->worksheet, self::AGREED_DEDICATED_AREA, size: 30);
    }
}
