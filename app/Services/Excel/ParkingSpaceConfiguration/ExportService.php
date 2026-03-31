<?php

declare(strict_types=1);

namespace App\Services\Excel\ParkingSpaceConfiguration;

use Illuminate\Support\Collection;

use Maatwebsite\Excel\Facades\Excel;

use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

use App\Support\Abstract\Service;
use App\Support\Tool\Excel\Export;

use App\Repositories\Space\CrmLanguageConfigurationRepository;
use App\Repositories\Space\CrmBuildingSpaceRepository;
use App\Repositories\Space\CrmLanguageSystemRepository;
use App\Repositories\Space\CrmParkingSpaceSelectRepository;
use App\Repositories\Space\CrmParkingSpaceRepository;
use App\Repositories\Space\CrmBuildingCommonSpaceRepository;

final class ExportService extends Service
{
    use \App\Support\Trait\Excel\ConfigurationExportTrait;

    public function __construct(
        private readonly CrmLanguageConfigurationRepository $crmLanguageConfigurationRepository,
        private readonly CrmLanguageSystemRepository        $crmLanguageSystemRepository,
        private readonly CrmBuildingSpaceRepository         $crmBuildingSpaceRepository,
        private readonly CrmParkingSpaceSelectRepository    $crmParkingSpaceSelectRepository,
        private readonly CrmParkingSpaceRepository          $crmParkingSpaceRepository,
        private readonly CrmBuildingCommonSpaceRepository   $crmBuildingCommonSpaceRepository,
    ) {
    }

    /**
     * Excel下載
     *
     * @return BinaryFileResponse
     */
    public function execute($identity): BinaryFileResponse
    {
        $comname = crm()->currentCommunity()['comname'];

        switch ($identity) {
                // 模板
            case 'template':
                $data = [];
                $selectMenuParameters = self::fetchSelectMenuParameters();
                $isSelectMenu = true;
                $fileName = $comname . '_車位配置模板.xlsx';
                break;

                // 資料
            case 'material':
                $data = self::fetchCrmParkingSpace();
                $selectMenuParameters = self::fetchSelectMenuParameters();
                $isSelectMenu = true;
                $fileName = $comname . '_車位配置資料.xlsx';
                break;

                // 總表
            case 'summary-table':
                $data = self::fetchCrmParkingSpace();
                $isSelectMenu = false;
                $selectMenuParameters = [];
                $fileName = $comname . '_車位總表.xlsx';
                break;
        }

        $parkingCarList[] = [
            '', '建號', '區名', '棟別', '梯間', '樓層', '公設', '平方公尺', '坪數', '分子', '分母', '平方公尺', '坪數', '平方公尺', '坪數', '分子', '分母',
            '平方公尺', '坪數', '平方公尺', '坪數', '車位類型', '車位尺寸',
            '車位售價', '車位法定名稱', '車位屬性', '使用方式', '車位種類',
        ];

        $export = new Export();
        $export->condition([
            '車位號', '車位位置', '', '', '', '', '', '(預售)總面積', '', '(預售)權利範圍(1/100000)', '', '(預售)車位坪數', '', '(保存)總面積', '',
            '(保存)權利範圍(1/100000)', '', '(保存)車位坪數', '', '土地持分', '', '車位類型',
            '車位尺寸', '車位售價', '車位法定名稱', '車位屬性', '使用方式', '車位種類',
        ])
            ->appendCollectionData(array_merge($parkingCarList, $data))
            ->asHorizontal(true)
            ->asVertical(true)
            ->appendColumnHeight(300)
            ->asMergeTitle(true)
            ->appendMergeTitleToCol(self::fetchMergeTitleColumns(), [1, 21, 27, 32])
            ->asSelectMenu($isSelectMenu)
            ->appendSelectMenuParameter($selectMenuParameters)
            ->asColumnFormat(true)
            ->applyColumnFormat(self::fetchColumnFormats())
            ->asCarCalculationFormula(true);

        return Excel::download($export, $fileName);
    }

    /**
     * 合並標題列
     *
     * @return array
     */
    private function fetchMergeTitleColumns(): array
    {
        return [
            'A1:A2',
            'B1:G1',
            'H1:I1',
            'J1:K1',
            'L1:M1',
            'N1:O1',
            'P1:Q1',
            'R1:S1',
            'T1:U1',
            'AB1:AF1',
            'AG1:AG2'
        ];
    }

    /**
     * 選單參數
     *
     * @return array
     */
    private function fetchSelectMenuParameters(): array
    {
        $menuParameters = [];
        $fields = [
            'district_name'  => 'C',
            'building_name'  => 'D',
            'staircase_name' => 'E',
            'floor_name'     => 'F',
            'household_name' => 'G',
        ];

        foreach ($fields as $field => $col) {
            $menuParameters[] = $this->createMenuParameter($col, $field, 'car_space');
        }

//        $fields = [
//            'district_name'  => 'AB',
//            'building_name'  => 'AC',
//            'staircase_name' => 'AD',
//            'floor_name'     => 'AE',
//            'household_name' => 'AF',
//        ];
//
//        foreach ($fields as $field => $col) {
//            $menuParameters[] = $this->createMenuParameter($col, $field, 'household_space');
//        }

        $fields = [
            'parking_attribute'  => 'Z',
            'parking_type'       => 'V',
            'use_direction'      => 'AA',
            'car_size'           => 'WW',
        ];

        foreach ($fields as $field => $col) {
            $menuParameters[] = $this->createMenuParameter($col, $field);
        }

        $menuParameters[] = [
            'col'     => 'AB',
            'selects' => ['機車', '汽車'],
            'count'   => 300
        ];

        $menuParameters[] = [
            'col'     => 'Y',
            'selects' => self::getCarApplicationType(),
            'count'   => 300
        ];

        return $menuParameters;
    }

    /**
     * 創建選單参数
     *
     * @param string $col
     * @param string $field
     * @param string|null $spaceType
     * @return array
     */
    private function createMenuParameter(string $col, string $field, ?string $spaceType = null): array
    {
        if ($spaceType === null) {
            $crmParkingSpaceSelect = $this->fetchCrmParkingSpaceSelect();
            $selects = isset($crmParkingSpaceSelect[$field]) ? $crmParkingSpaceSelect[$field]->pluck('value')->toArray() : [];
        } else {
            $spaceMaterial = $this->fetchSpaceMaterial($field);
            $selects = isset($spaceMaterial[$spaceType]) ? $spaceMaterial[$spaceType] : [];
        }

        return [
            'col' => $col,
            'selects' => $selects,
            'count' => 300,
        ];
    }

    /**
     * 列格式
     *
     * @return array
     */
    private function fetchColumnFormats(): array
    {
        return [
            [
                'columns' => ['A', 'B', 'C', 'E', 'I'],
                'format'  => NumberFormat::FORMAT_TEXT
            ],
            [
                'columns' => ['H', 'I', 'L', 'M', 'N', 'P', 'R', 'S', 'T', 'U'],
                'format'  => NumberFormat::FORMAT_NUMBER_00
            ]
        ];
    }

    /**
     * @param  string  $name
     *
     * @return array
     */
    private function fetchSpaceMaterial(string $name): array
    {
        $buildingSpaces = $this->crmBuildingSpaceRepository->findByAll();

        $crmBuildingCommon = $this->crmBuildingCommonSpaceRepository->findByAll();

        if ($buildingSpaces->isEmpty() && $crmBuildingCommon->isEmpty()) {
            return [];
        }

        $carSpace = $crmBuildingCommon
            ->map(fn ($space) => self::mapCommonFields($space))
            ->pluck($name)
            ->unique()
            ->values()
            ->toArray();

        $householdSpace = $buildingSpaces->whereIn('main_application', ['H001', 'H002', 'H004', 'H005', 'H006', 'H007', 'H014'])
            ->map(fn ($space) => self::mapCommonFields($space))
            ->pluck($name)
            ->unique()
            ->values()
            ->toArray();

        return [
            'car_space'       => $carSpace,
            'household_space' => $householdSpace
        ];
    }

    /**
     *
     * @return array
     */
    private function fetchCrmParkingSpaceSelect(): Collection
    {
        return  $this->crmParkingSpaceSelectRepository
            ->findAll()
            ->groupBy('type');
    }

    /**
     * @param $item
     *
     * @return array
     */
    private function mapCommonFields($item): array
    {
        return [
            'district_name'  => $item->district_name,
            'building_name'  => $item->building_name,
            'staircase_name' => $item->staircase_name,
            'floor_name'     => $item->floor_name,
            'household_name' => $item->household_name,
        ];
    }

    /**
     * @return array
     */
    private function fetchCrmParkingSpace(): array
    {
        return $this->crmParkingSpaceRepository
            ->fetchExcelDownload()
            ->map(fn ($item) => $this->mapCrmParkingSpaceItem($item))
            ->toArray();
    }

    /**
     * @param $item
     *
     * @return array
     */
    private function mapCrmParkingSpaceItem($item): array
    {
        return [
            'parking_number' => $item->parking_number ?? null,
            'block_id'       => $item->CrmBuildingSpaceForCar?->crmBuildingCommonInfo->block_id ?? null,
            'district_name'  => $item->CrmBuildingSpaceForCar->district_name ?? null,
            'building_name'  => $item->CrmBuildingSpaceForCar->building_name ?? null,
            'staircase_name' => $item->CrmBuildingSpaceForCar->staircase_name ?? null,
            'floor_name'     => $item->CrmBuildingSpaceForCar->floor_name ?? null,
            'household_name' => $item->CrmBuildingSpaceForCar->household_name ?? null,
            $item->CrmBuildingSpaceForCar?->crmBuildingCommonInfo->pre_sale_total_area ?? 0,
            ($item->CrmBuildingSpaceForCar?->crmBuildingCommonInfo->pre_sale_total_area * 0.3) ?? 0,
            'default_extent_of_ownership_numerator'   => $item->default_extent_of_ownership_numerator ?? 0, // 權利範圍(分子)
            'default_extent_of_ownership_denominator' => $item->default_extent_of_ownership_denominator ?? 0, // 權利範圍(分母)
            'default_parking_meter'                   => $item->default_parking_meter ?? 0, // 車位坪數(平方公尺)
            'default_parking_area'                    => $item->default_parking_area ?? 0, // 車位坪數(坪)
            'extent_of_ownership_numerator'    => $item->extent_of_ownership_numerator ?? 0, // 權利範圍(分子)
            'extent_of_ownership_denominator'  => $item->extent_of_ownership_denominator ?? 0, // 權利範圍(分母)
            'parking_square_meter'             => $item->parking_square_meter ?? 0, // 車位坪數(平方公尺)
            'parking_area'                     => $item->parking_area ?? 0, // 車位坪數(坪)
            $item->CrmBuildingSpaceForCar?->crmBuildingCommonInfo->pre_sale_total_area ?? 0,
            ($item->CrmBuildingSpaceForCar?->crmBuildingCommonInfo->pre_sale_total_area * 0.3) ?? 0,
            'land_square_meter'                => $item->land_square_meter ?? 0,
            'land_area'                        => $item->land_area ?? 0,
            'parking_type'                     => $item->parking_type ?? null,
            'parking_size'                     => $item->parking_size ?? null,
            'sell_price'                       => $item->sell_price ?? 0,
            'application'                      => self::getCarApplicationType($item->application) ?? null,
            'parking_attribute'                => $item->parking_attribute ?? null,
            'use_direction'                    => $item->use_direction ?? null,
            'district_name_household'          => $item->CrmBuildingSpace->district_name ?? null,
            'building_name_household'          => $item->CrmBuildingSpace->building_name ?? null,
            'staircase_name_household'         => $item->CrmBuildingSpace->staircase_name ?? null,
            'floor_name_household'             => $item->CrmBuildingSpace->floor_name ?? null,
            'household_name_household'         => $item->CrmBuildingSpace->household_name ?? null,
            'car_type'                         => ['機車', '汽車', '電動車'][$item->car_type] ?? null,
        ];
    }

    /**
     *
     * @param  int|null  $carApplicationType
     *
     * @return string|array
     */
    private function getCarApplicationType(?int $carApplicationType = null): string|array
    {
        $carApplicationTypes = [
            '法定車位',
            '增設車位',
            '獎勵車位',
            '殘障車位',
            '訪客貴賓專用車位'
        ];

        if (is_null($carApplicationType)) {
            return $carApplicationTypes;
        }

        return $carApplicationTypes[$carApplicationType] ?? [];
    }
}
