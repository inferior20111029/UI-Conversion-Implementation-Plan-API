<?php

namespace App\Exports\Component\Style;

use Maatwebsite\Excel\Sheet;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SheetStyle
{
    /**
     * 合併儲存格
     *
     * @param \Maatwebsite\Excel\Sheet $sheet
     * @param string $range 範圍
     *
     * @return void
     */
    public static function mergeCells(Sheet $sheet, string $range): void
    {
        $sheet->mergeCells($range);
    }

    /**
     * 批次設定欄寬
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $worksheet
     * @param string $rang
     * @param int $size
     *
     * @return void
     */
    public static function multipleSetWith(Worksheet $worksheet, string $rang, int $size): void
    {
        $rageData = explode(':', $rang);
        $sheetRange = array_map(fn (string $value): string => str(substr($value, 0, 1))->upper(), $rageData);
        $range = 2 > count($sheetRange) ? $sheetRange : range($sheetRange[0], $sheetRange[1]);

        foreach ($range as $columnDimension) {
            static::setWidth($worksheet, $columnDimension, $size);
        }
    }

    /**
     * 設定欄位背景顏色
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $worksheet
     * @param string $range 範圍
     * @param string $colorCode 色碼
     *
     * @return void
     */
    public static function setColor(Worksheet $worksheet, string $range, string $colorCode): void
    {
        $worksheet->getStyle($range)
            ->applyFromArray([
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => $colorCode
                    ],
                ],
            ]);
    }

    /**
     * 設定字體寬度
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $worksheet
     * @param string $range 範圍
     *
     * @return void
     */
    public static function setFontBold(Worksheet $worksheet, string $range): void
    {
        $worksheet->getStyle($range)
            ->applyFromArray([
                'font' => [
                    'bold' => true
                ],
            ]);
    }

    /**
     * 設定欄寬
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $worksheet
     * @param string $columnDimension
     * @param int $size
     *
     * @return void
     */
    public static function setWidth(Worksheet $worksheet, string $columnDimension, int $size): void
    {
        $worksheet->getColumnDimension($columnDimension)->setWidth($size);
    }

    /**
     * 設定欄位文字置中
     *
     * @param \Maatwebsite\Excel\Sheet $sheet
     * @param string $range
     *
     * @return void
     */
    public static function setCenterPosition(Sheet $sheet, string $range): void
    {
        $sheet
            ->getStyle($range)
            ->getAlignment()
            ->setWrapText(true) // 自動換行
            ->setHorizontal(Alignment::HORIZONTAL_CENTER) // 置中
            ->setVertical(Alignment::HORIZONTAL_CENTER); // 垂直置中
    }

    /**
     * 設定欄位數字格式
     * @param \Maatwebsite\Excel\Sheet $sheet
     * @param string $range
     *
     * @return void
     */
    public static function sethNumberFormat(Sheet $sheet, string $range): void
    {
        $sheet
            ->getDelegate()
            ->getStyle($range)
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_NUMBER);
    }
}
