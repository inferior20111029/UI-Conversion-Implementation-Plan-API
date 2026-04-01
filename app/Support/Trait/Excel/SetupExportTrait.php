<?php

namespace App\Support\Trait\Excel;

use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

trait SetupExportTrait
{
    /**
     * @param  AfterSheet  $event
     * @param  int  $height
     *
     * @return void
     */
    private function setupVertical(AfterSheet $event, int $height)
    {
        $event->sheet->getDelegate()->getStyle('A1:AG'.$height)
            ->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);//垂直置頂

    }

    /**
     * @param  AfterSheet  $event
     * @param $height
     *
     * @return void
     */
    private function setupHorizontal(AfterSheet $event, $height,$column)
    {
        $event->sheet->getDelegate()
            ->getStyle('A1:A'.$column.$height)->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }

    /**
     * Setup select menu validation for the sheet.
     *
     * @param AfterSheet $event
     * @return void
     */
    private function setupSelectMenu(AfterSheet $event): void
    {
        $init_num = 60;
        $sheet = $event->sheet->getDelegate();

        foreach ($this->selectMenuParameter as $parameter) {
            $orig_hid_select = $this->num2alpha($init_num);

            $rowIndex = 1;
            foreach ($parameter['selects'] as $selectValue) {
                $sheet->getCell($this->num2alpha($init_num) . $rowIndex)->setValue($selectValue);
                $rowIndex++;
            }

            $sheet->getColumnDimension($this->num2alpha($init_num))->setVisible(false);

            for ($i = 2; $i <= $parameter['count']; $i++) {
                $cell = $parameter['col'] . $i;
                $validation = $sheet->getCell($cell)->getDataValidation();
                $this->setSelectMenuValidation($validation, '=$' . $orig_hid_select . '$1:$' . $orig_hid_select . '$' . ($rowIndex - 1));
            }

            $init_num++;
        }

        $sheet->getStyle('A1:G100')
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }

    /**
     * @param  AfterSheet  $event
     *
     * @return void
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function setupDateFormat(AfterSheet $event): void
    {
        foreach ($this->selectDateFormatParameter as $parameter) {
            for ($i = 2; $i <= $parameter['count']; $i++) {
                $cell = $parameter['col'] . $i;
                $validation = $event->sheet->getDelegate()->getCell($cell)->getDataValidation();
                $this->setDateFormat($validation, $parameter['title'], $parameter['value']);
            }
        }

        $event->sheet->getDelegate()->getStyle('A1:G100')
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }

    /**
     * 車位計算公式
     *
     * @param  AfterSheet  $event
     *
     * @return void
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function setupCarCalculationFormula(AfterSheet $event): void
    {
        for ($i = 2; $i <= $this->cellHeight; $i++) {
            if ($i == 2) {
                continue;
            }

            $event->sheet->getDelegate()->getCell('Q'.$i)->setValue("1");
            $event->sheet->getDelegate()->getCell('K'.$i)->setValue("1");
            $event->sheet->getDelegate()->getCell('L'.$i)->setValue("=(H".$i.'*J'.$i.'/K'.$i.')');
            $event->sheet->getDelegate()->getCell('M'.$i)->setValue("=(L".$i."*0.3025)");
            $event->sheet->getDelegate()->getCell('R'.$i)->setValue("=(N".$i.'*P'.$i.'/Q'.$i.')');
            $event->sheet->getDelegate()->getCell('S'.$i)->setValue("=(R".$i."*0.3025)");
        }
    }

    /**
     * Setup Head Color for the sheet.
     *
     * @param AfterSheet $event
     * @return void
     */
    private function setupHeadColor(AfterSheet $event): void
    {
        array_walk($this->headColorParameter, function ($parameter) use ($event) {
            $event->sheet->getStyle($parameter['cell'])
                ->getFont()
                ->getColor()
                ->setRGB($parameter['color'] ?? 'ff0000');
        });
    }

    /**
     * @param  AfterSheet  $event
     * @param $range
     *
     * @return void
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function setupStyleBox(AfterSheet $event)
    {
        $styleRedBox = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THICK,
                    'color'       => ['argb' => $this->styleRedBox['color']],
                ],
            ],
        ];

        $event->sheet->getDelegate()->getStyle($this->styleRedBox['cellCoordinate'])->applyFromArray($styleRedBox);
    }

    /**
     *
     * @param AfterSheet $event
     * @return void
     */
    private function setupCommentToCell(AfterSheet $event): void
    {
        foreach ($this->commentToCell as $commentToCell) {
            $this->applyCommentToCell(
                $event->sheet->getDelegate(),
                $commentToCell['cell'],
                $commentToCell['comments'],
                $commentToCell['width'],
                $commentToCell['height'],
                $commentToCell['marginLeft'],
                $commentToCell['marginTop']
            );
        }
    }

    /**
     * Set data validation for select menus.
     *
     * @param  DataValidation  $validation
     * @param  string  $range
     *
     * @return DataValidation
     */
    private function setSelectMenuValidation(DataValidation $validation, string $range): DataValidation
    {
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
        $validation->setAllowBlank(false);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setErrorTitle('輸入的值有誤');
        $validation->setError('您输入的值不在下拉框列表内.');
        $validation->setPromptTitle('從選項中選擇');
        $validation->setPrompt('請從下拉列表中選擇一個值');
        $validation->setFormula1($range);

        return $validation;
    }

    /**
     * @param  DataValidation  $validation
     * @param  string  $title
     * @param  string  $value
     *
     * @return DataValidation
     */
    protected function setDateFormat(DataValidation $validation,string $title,string $value): DataValidation
    {
        return $validation->setType(DataValidation::TYPE_DATE)
            ->setAllowBlank(false)
            ->setShowInputMessage(true)
            ->setShowDropDown(true)
            ->setPromptTitle($title)
            ->setPrompt($value);
    }

    /**
     * @param $event
     *
     * @return mixed
     */
    private function setupMergeTitle($event)
    {
        foreach ($this->mergeTitleToCol as $range) {
            $event->sheet->mergeCells($range);
            $event = $this->setBorder($event, $range);
        }

        if (!empty($this->setBorderRange)) {
            $this->applyBorderRange(
                $event,
                $this->setBorderRange[0],
                $this->setBorderRange[1],
                '2'
            );
            $this->mergeAndApplyBorderRange(
                $event,
                $this->setBorderRange[1],
                $this->setBorderRange[2],
                '1',
                '2'
            );
            $this->applyBorderRange(
                $event,
                $this->setBorderRange[2],
                $this->setBorderRange[3],
                '2'
            );
        }

        return $event;
    }

    /**
     * 為指定範圍的單元格设置邊框
     *
     * @param $event
     * @param int $start
     * @param int $end
     * @param string $row
     * @return void
     */
    private function applyBorderRange($event, int $start, int $end, string $row): void
    {
        for ($i = $start; $i < $end; $i++) {
            $cell = $this->num2alpha($i) . $row;
            $event = $this->setBorder($event, $cell);
        }
    }

    /**
     * 為指定範圍的單元格设置邊框
     *
     * @param $event
     * @param int $start
     * @param int $end
     * @param string $startRow
     * @param string $endRow
     * @return void
     */
    private function mergeAndApplyBorderRange($event, int $start, int $end, string $startRow, string $endRow): void
    {
        for ($i = $start; $i < $end; $i++) {
            $range = $this->num2alpha($i) . $startRow . ':' . $this->num2alpha($i) . $endRow;
            $event->sheet->mergeCells($range);
            $event = $this->setBorder($event, $range);
        }
    }


    /**
     * @param  AfterSheet  $event
     *
     * @return void
     */
    private function setupColumnFormat(AfterSheet $event): void
    {
        foreach ($this->columnFormat as $item) {
            foreach ($item['columns'] as $column) {
                $event->sheet->getDelegate()->getStyle($column)->getNumberFormat()->setFormatCode($item['format']);
            }
        }

    }

}
