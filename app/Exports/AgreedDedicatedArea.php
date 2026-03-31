<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;

use App\Exports\Component\Style\SheetStyle;
use App\Exports\Component\Style\SheetSelect;
use App\Exports\Component\Area\AgreedDedicatedHeader;

class AgreedDedicatedArea implements WithHeadings, WithTitle, WithEvents
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
            ['戶別', '面積名稱', '面積大小'],
        ];
    }

    /**
     * 設定 Work Sheet 名稱
     *
     * @return string
     */
    public function title(): string
    {
        return '約定專用面積';
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
                (new AgreedDedicatedHeader($event))->setHeaderStyle();

                $this->setHeaderTextRun($event);

                SheetStyle::setCenterPosition($event->sheet, "A1:C{$this->rowCount}");
                SheetStyle::sethNumberFormat($event->sheet, "C2:C{$this->rowCount}");

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
        $event->sheet->getDelegate()->getComment('B1')->getText()->createTextRun('說明：此項必填，請輸入面積名稱，格式為文字，最大字元：255');
        $event->sheet->getDelegate()->getComment('C1')->getText()->createTextRun('說明：請輸入面積大小，格式為數字，最大：15000000');
    }
}
