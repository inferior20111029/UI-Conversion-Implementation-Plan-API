<?php

namespace App\Exports\Component\Style;

use Maatwebsite\Excel\Sheet;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SheetSelect
{
    /**
     * 填充選擇資料
     *
     * @param \Maatwebsite\Excel\Sheet $sheet
     * @param array $selectData 選項資料
     * @param string $beginSheetNumber 開始的欄位號碼
     * @param int $fillTotal 填充總數
     *
     * @return void
     */
    public static function fillingValue(Sheet $sheet, array $selectData, string $beginSheetNumber, int $fillTotal): void
    {
        foreach ($selectData as $select) {
            $dropColumn = (string) data_get($select, 'columnsName');
            $options = (array) data_get($select, 'options');

            $hiddenSheet = $sheet->getDelegate()->getParent()->createSheet();
            $hiddenSheet->setTitle(str()->random(6));
            $hiddenSheet->setSheetState(Worksheet::SHEETSTATE_HIDDEN);

            // Populate hidden sheet with dropdown values
            foreach ($options as $index => $option) {
                $cellCoordinate = Coordinate::stringFromColumnIndex(1) . ($index + 1);
                $hiddenSheet->setCellValue($cellCoordinate, $option);
            }

            // Set data validation formula to refer to hidden sheet cells
            $validationBegin = "{$dropColumn}{$beginSheetNumber}";
            $validation = self::setValidationSheet($sheet, $validationBegin, count($options), $hiddenSheet->getTitle());

            // Clone validation to remaining rows
            for ($i = $beginSheetNumber; $i <= $fillTotal; $i++) {
                $sheet->getCell("{$dropColumn}{$i}")->setDataValidation(clone $validation);
            }
        }
    }

    /**
     * 設定驗證 Sheet
     *
     * @param Sheet $sheet
     * @param string $validationBegin
     * @param array $options
     * @param string $hiddenSheetName
     *
     * @return DataValidation
     */
    public static function setValidationSheet(
        Sheet $sheet,
        string $validationBegin,
        int $totalOptions,
        string $hiddenSheetName,
    ): DataValidation {
        return $sheet
            ->getCell($validationBegin)
            ->getDataValidation()
            ->setType(DataValidation::TYPE_LIST)
            ->setErrorStyle(DataValidation::STYLE_STOP)
            ->setAllowBlank(false)
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setShowDropDown(true)
            ->setErrorTitle('輸入的值有誤')
            ->setError('您输入的值不在下拉框列表内.')
            ->setPromptTitle('從選項中選擇')
            ->setPrompt('請從下拉列表中選擇一個值')
            ->setFormula1($hiddenSheetName . '!$A$1:$A$' . $totalOptions);
    }
}
