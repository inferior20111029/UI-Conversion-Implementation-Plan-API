<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;

use App\Exports\Component\Style\SheetStyle;
use App\Exports\Component\Style\SheetSelect;
use App\Exports\Component\Area\PublicHoldingHeader;

class PublicHoldingArea implements WithHeadings, WithTitle, WithEvents
{
    use Exportable;

    protected readonly array $selects;

    protected int $rowCount = 200;

    /**
     * @param array $spaceData
     */
    public function __construct(
        protected readonly array $spaceData,
    ) {
        $this->selects = [
            ['columnsName' => 'A', 'options' => $spaceData]
        ];
    }

    /**
     * 設定 excel head
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            ['戶別', '建號', '共有總面積', '權力範圍-分母', '權力範圍-分子'],
        ];
    }

    /**
     * 設定 Work Sheet 名稱
     *
     * @return string
     */
    public function title(): string
    {
        return '公設持分面積';
    }

    /**
     * Excel 資料處理
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                (new PublicHoldingHeader($event))->setHeaderStyle();

                $this->setHeaderTextRun($event);

                SheetStyle::setCenterPosition($event->sheet, "A1:E{$this->rowCount}");
                SheetStyle::sethNumberFormat($event->sheet, "C2:E{$this->rowCount}");

                SheetSelect::fillingValue(
                    $event->sheet,
                    $this->selects,
                    beginSheetNumber: 2,
                    fillTotal: $this->rowCount
                );
            },
        ];
    }

    /**
     * 設定 header 備註
     *
     * @param AfterSheet $event
     *
     * @return void
     */
    private function setHeaderTextRun(AfterSheet $event): void
    {
        $event->sheet->getDelegate()->getComment('A1')->getText()->createTextRun('說明：此項必填，請選擇欲建立面積資料的戶別，可以重複');
        $event->sheet->getDelegate()->getComment('B1')->getText()->createTextRun('說明：此項必填，請輸入建號，格式為文字，最大字元：255');
        $event->sheet->getDelegate()->getComment('C1')->getText()->createTextRun('說明：請輸入共有總面積，格式為數字，最大：15000000');
        $event->sheet->getDelegate()->getComment('D1')->getText()->createTextRun('說明：請輸入權力範圍-分母，格式為數字，最大：15000000');
        $event->sheet->getDelegate()->getComment('E1')->getText()->createTextRun('說明：請輸入權力範圍-分子，格式為數字，最大：15000000');
    }
}
