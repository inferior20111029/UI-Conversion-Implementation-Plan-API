<?php

declare(strict_types=1);

namespace App\Services\Excel\Equipment;

use App\Support\Abstract\Service;
use App\Support\Tool\Excel\Export;
use Maatwebsite\Excel\Facades\Excel;
use App\Repositories\Space\CrmBuildingSpaceRepository;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class ExportService extends Service
{
    public function __construct(
        private CrmBuildingSpaceRepository         $crmBuildingSpaceRepository,
    ) {
    }

    /**
     * Excel下載
     *
     * @return BinaryFileResponse
     */
    public function execute($identity): BinaryFileResponse
    {
        switch ($identity) {
                // 設備總量模板
            case 'example-empty-space':
                $data = [];
                $header = self::getSpaceEmptyExampleHeader();
                $cell = [['cell' => 'E1'], ['cell' => 'G1'], ['cell' => 'Q1']];
                $commentToCell = self::setCommentToCell(['AA1', 'G1']);
                $cellCoordinate = 'AA1:AP1';
                $fileName = 'excel設備總量匯入模板.xlsx';
                break;

                // 設備細項模板
            case 'example-detail':
                $data = self::fetchSpaceMaterial();
                $header = self::getDetailHeader();
                $cell = [['cell' => 'J1']];
                $commentToCell = self::setCommentToCell(['AE1', 'L1']);
                $cellCoordinate = 'AE1:AT1';
                $fileName = 'excel設備細項匯入模板.xlsx';
                break;

            case 'properties':
                $filePath = public_path('excel/excel詳細屬性編輯工具.xlsm');
                return response()->download($filePath);
        }

        $export = new Export();
        $export->condition($header)
            ->appendColumnWidth(36)
            ->appendCollectionData($data)
            ->asStyleBox(true)
            ->appendStyleRedBox([
                'color'          => 'FFFF0000',
                'cellCoordinate' => $cellCoordinate,
            ]) // 設定框線範圍&顏色
            ->asHeadColor(true)
            ->appendHeadColorParameter(
                array_merge(
                    [['cell' => 'A1',], ['cell' => 'C1',], ['cell' => 'B1',]],
                    $cell
                )
            ) // 設定標題顏色
            ->asCommentToCell(true) // 添加注釋
            ->appendCommentToCell($commentToCell);


        return Excel::download($export, $fileName);
    }

    /**
     * 获取模板表头
     *
     * @param bool $isSpaceExample
     * @return array
     */
    private function getTemplateHeader(bool $isSpaceExample): array
    {
        $commonHeader = [
            '*類別名稱', '*系統/工種名稱', '*設備名稱'
        ];

        $spaceExampleAdditionalHeader = [
            '*區名', '*棟別', '*梯間', '*樓層', '*戶別 / 公設'
        ];

        $feeNumberAdditionalHeader = [
            '區域', '*空間', '位置', '*空間屬性(S/L/P)', '公共工程編碼', 'OminiClass編碼', '設備編碼', '品牌', '型號', '補充規格資訊', '尺寸', '重量', '產地'
        ];

        $commonTailHeader = [
            '單位', '取得來源', '預估成本', '取得成本', '使用年限', '養護週期', '保固年限', '取得日期', '保固日期', '設備圖檔名稱', 'BIM圖資', '施工圖', '竣工圖', '保養說明書', '設備說明書/操作手冊', '出廠報告', '測試報告', '廠商保固養護紀錄', '設備規格書', '設備認證報告書', '保養材料費用清單', '樓層平面圖', '建築立面圖', '建築燈光計畫', '建築物能源損耗預估(水費, 電費)', '詳細屬性'
        ];

        $header = array_merge(
            $commonHeader,
            $isSpaceExample ? $spaceExampleAdditionalHeader : [],
            $feeNumberAdditionalHeader,
            $commonTailHeader
        );

        if (!$isSpaceExample) {
            array_splice($header, 16, 0, ['數量']);
        }

        return $header;
    }

    /**
     *
     * @return array
     */
    private function getSpaceEmptyExampleHeader(): array
    {
        return $this->getTemplateHeader(false);
    }

    /**
     *
     * @return array
     */
    private function getDetailHeader(): array
    {
        return $this->getTemplateHeader(true);
    }

    /**
     * @param $cells
     *
     * @return array[]
     */
    private function setCommentToCell($cells): array
    {
        return  [
            [
                'cell' => $cells[0],
                'comments' => [
                    'PS: 紅框項目內容，' => null,
                    '檔案名稱(包括副檔名)，' => 'FFFF0000',
                    '不同檔案名稱' => null,
                    '請勿重複，且勿用中文字，' => 'FFFF0000',
                    '避免系統程式對應失敗。' => null,
                    '如圖檔需複數' => 'FFFF0000',
                    '可用' => null,
                    '逗號隔開。' => 'FFFF0000',
                    '如不顯示此對話框，請在三角型處按右鍵，選擇隱藏註解。' => null
                ],
                'width' => '200px',
                'height' => '200px',
                'marginLeft' => '7850px',
                'marginTop' => '80px'
            ],
            [
                'cell' => $cells[1],
                'comments' => [
                    'S = 小公' => null,
                    "\r\n" => null,
                    'L = 大公' => null,
                    "\r\n" => null,
                    'P = 專有' => null,
                    "\r\n" => null,
                    '如不顯示此對話框，請在三角型處按右鍵，選擇隱藏註解。' => null
                ],
                'width' => '250px',
                'height' => '150px',
                'marginLeft' => '3050px',
                'marginTop' => '80px'
            ]
        ];
    }

    /**
     * 取得戶別資料
     * @param string $type
     * @return array
     */
    public function fetchSpaceMaterial(): array
    {
        return $this->crmBuildingSpaceRepository->findByAll()
            ->map(fn ($item, $key) => $this->mapCommonFields($item))
            ->toArray();
    }

    /**
     *
     * @param object $item
     * @param int $key
     * @return array
     */
    private function mapCommonFields(object $item): array
    {
        return [
            'type_name'      => '',
            'class_name'     => '',
            'name'           => '',
            "district_name"  => $item->district_name,
            "building_name"  => $item->building_name,
            "staircase_name" => $item->staircase_name,
            "floor_name"     => $item->floor_name,
            "household_name" => $item->household_name,
            'area'           => '',
            'space'          => '',
            'location'       => '',
            'public_type'    => $item->public_type == 0 ? 'L' : 'P',
        ];
    }
}
