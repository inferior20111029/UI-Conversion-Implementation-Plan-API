<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;

use App\Exports\Component\Area\MainHeader;
use App\Exports\Component\Style\SheetStyle;
use App\Exports\Component\Style\SheetSelect;

class AreaMain implements WithHeadings, WithTitle, WithEvents
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
            ['戶別資料', '土地面積', '', '專有面積', '', '', '約定專用面積'],
            ['戶別', '土地專用面積', '土地約定專用面積', '室內面積', '室內陽台面積', '室內雨遮面積', '保存面積']
        ];
    }

    /**
     * 設定 Work Sheet 名稱
     *
     * @return string
     */
    public function title(): string
    {
        return '戶別相關面積';
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
                (new MainHeader($event))->setHeaderStyle();

                $this->setHeaderTextRun($event);

                SheetStyle::setCenterPosition($event->sheet, "A1:G{$this->rowCount}");
                SheetStyle::sethNumberFormat($event->sheet, "B3:G{$this->rowCount}");

                SheetSelect::fillingValue(
                    $event->sheet,
                    $this->selects,
                    beginSheetNumber: 3,
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
        $event->sheet->getDelegate()->getComment('A2')->getText()->createTextRun('說明：此項必填，請選擇欲建立面積資料的戶別，不得重複');
        $event->sheet->getDelegate()->getComment('B2')->getText()->createTextRun('說明：請輸入數字，最大：15000000');
        $event->sheet->getDelegate()->getComment('C2')->getText()->createTextRun('說明：請輸入數字，最大：15000000');
        $event->sheet->getDelegate()->getComment('D2')->getText()->createTextRun('說明：請輸入數字，最大：15000000');
        $event->sheet->getDelegate()->getComment('E2')->getText()->createTextRun('說明：請輸入數字，最大：15000000');
        $event->sheet->getDelegate()->getComment('F2')->getText()->createTextRun('說明：請輸入數字，最大：15000000');
        $event->sheet->getDelegate()->getComment('G2')->getText()->createTextRun('說明：請輸入數字，最大：15000000');
    }
}
