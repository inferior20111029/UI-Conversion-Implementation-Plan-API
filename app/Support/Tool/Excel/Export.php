<?php

declare(strict_types=1);

namespace App\Support\Tool\Excel;

use Home\Traits\AsObject;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Export implements FromCollection, WithEvents
{
    use Exportable;
    use AsObject;
    use \App\Support\Trait\Excel\VerifyExportTrait;
    use \App\Support\Trait\Excel\SetupExportTrait;
    use \App\Support\Trait\Excel\AppendInfoExportTrait;

    protected $headings;

    protected $collectionData;

    protected $isSelectMenu = false;
    protected $isDateFormat = false; // 資料格式

    protected $isHeadColor = false;

    protected $isStyleBox = false;

    protected $isCommentToCell = false;

    protected $isVertical = false;

    protected $isHorizontal = false;

    protected $isMergeTitle = false;

    protected $carCalculationFormula = false;

    protected $isColumnFormat = true;

    protected $selectMenuParameter; // 選單


    protected $headColorParameter; // 標題顏色

    protected $colWidth = 20; // 寬度

    protected $cellHeight = 300; // cellCoordinate

    protected $styleRedBox; // 標題框線

    protected $commentToCell; // 注釋

    protected $mergeTitleToCol = false; // 合併標題資訊

    protected $setBorderRange = []; // 設定特殊邊框範圍

    protected $columnFormat = []; // column　格式

    public function condition($headings): self
    {
        $this->headings = $headings;
        return $this;
    }

    public function collection(): Collection
    {
        $data   = [$this->headings];
        $data[] = $this->collectionData;
        return collect($data);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                foreach ($this->headings as $key => $heading) {
                    $column = $this->num2alpha($key);
                    $event->sheet->getDelegate()->getColumnDimension($column)->setWidth($this->colWidth);
                    $event->sheet->getStyle($column)->getAlignment()->setWrapText(true);

                    $event->sheet->getDelegate()
                        ->getStyle($column)
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                }

                if ($this->isMergeTitle) { // 合併標題
                    $this->setupMergeTitle($event);
                }

                if ($this->isHeadColor) {
                    $this->setupHeadColor($event);
                }

                if ($this->isStyleBox) { // 設定框線範圍&顏色
                    $this->setupStyleBox($event);
                }

                if($this->commentToCell) {
                    $this->setupCommentToCell($event);
                }

                if ($this->isSelectMenu) {
                    $this->setupSelectMenu($event);
                }

                if ($this->isDateFormat) {
                    $this->setupDateFormat($event);
                }

                if ($this->isColumnFormat) {
                    $this->setupColumnFormat($event);
                }

                if ($this->carCalculationFormula) {
                    $this->setupCarCalculationFormula($event);
                }
            }
        ];
    }

    /**
     * @param $sheet
     * @param  string  $cell
     * @param  array  $comments
     * @param  string  $width
     * @param  string  $height
     * @param  string  $marginLeft
     * @param  string  $marginTop
     *
     * @return void
     */
    private function applyCommentToCell($sheet, string $cell, array $comments, string $width, string $height, string $marginLeft, string $marginTop): void
    {
        $comment = $sheet->getComment($cell);
        $comment->setWidth($width)->setHeight($height);

        foreach ($comments as $text => $color) {
            $textRun = $comment->getText()->createTextRun($text);
            if ($color) {
                $textRun->getFont()->getColor()->setARGB($color);
            }
        }

        $comment->setVisible(true)->setMarginLeft($marginLeft)->setMarginTop($marginTop);
    }

    private function num2alpha($n): string
    {
        for ($r = ""; $n >= 0; $n = intval($n / 26) - 1) {
            $r = chr($n % 26 + 0x41) . $r;
        }
        return $r;
    }

    private function setBorder($event, $range)
    {
        $event->sheet->getStyle($range)->applyFromArray([
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color'       => ['rgb' => '000000'],
                ],
            ],
        ]);

        return $event;
    }

}
