<?php

declare(strict_types=1);

namespace App\Services\Excel\ConfigurationCommon;

use Illuminate\Support\Collection;

use App\Support\Enum\LandUseZoningType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

use Maatwebsite\Excel\Facades\Excel;

use App\Support\Abstract\Service;
use App\Support\Tool\Excel\Export;
use App\Support\Enum\CrmHouseType;

use App\Repositories\Space\CrmBuildingCommonSpaceRepository;
use App\Repositories\Space\CrmLanguageSystemRepository;
use App\Repositories\Space\CrmLanguageConfigurationRepository;

final class ExportService extends Service
{
    use \App\Support\Trait\Excel\ConfigurationExportTrait;

    public function __construct(
        private readonly CrmLanguageConfigurationRepository $crmLanguageConfigurationRepository,
        private readonly CrmLanguageSystemRepository        $crmLanguageSystemRepository,
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
        $comname = crm()->currentCommunity('comname');
        $spaceHeader = ['門牌', '稅籍號碼', '建號', '座落', '權利範圍', '建造執照號碼', '使用執照號碼', '主要用途', '土地使用分區', '預售-建物總面積', '保存-建物總面積'];

        switch ($identity) {
                // 空間配置-模板
            case 'space-example':
                $data = [];
                $header = $spaceHeader;
                $fileName = $comname.'_空間配置模板.xlsx';
                break;

                // 水電號-範例
            case 'fee-number-example':
                $data = self::fetchSpaceMaterial('fee-number');
                $header = ['主電表', '子電表', '主水表', '子水表'];
                $fileName = $comname.'_水電號模板.xlsx';
                break;

                // 空間配置-資料
            case 'space-material':
                $data = self::fetchSpaceMaterial('space');
                $header = $spaceHeader;
                $fileName = $comname.'_空間配置資料.xlsx';
                break;

                // 水電號-資料
            case 'fee-number-material':
                $data = self::fetchFeeNumberMaterial();
                $header = ['主電表', '子電表', '主水表', '子水表'];
                $fileName = $comname.'_水電號資料.xlsx';
                break;
        }

        $export = new Export();
        $export->condition([...['區名', '棟別', '梯間', '樓層', '公設'], ...$header])
            ->appendCollectionData($data)
            ->asHorizontal(true)
            ->asVertical(true)
            ->asSelectMenu(true)
            ->appendSelectMenuParameter(self::fetchOption('public'));

        return Excel::download($export, $fileName);
    }

    /**
     * 取得戶別資料
     *
     * @return array
     */
    public function fetchSpaceMaterial($type): array
    {
        return $this->crmBuildingCommonSpaceRepository->findByAll()->map(
            function ($item) use ($type) {
                $result = $this->mapCommonFields($item);

                if ($type === 'space') {
                    $result = [...$result, ...$this->mapSpaceFields($item)];
                }

                return $result;
            }
        )->toArray();
    }

    /**
     * 取得戶別水電資料
     *
     * @return array
     */
    public function fetchFeeNumberMaterial(): array
    {
        return $this->crmBuildingCommonSpaceRepository->fetchFeeNumberExcel()->flatMap(function ($item) {
            $commonFields = $this->mapCommonFields($item);
            $crmHouseFeeNumber = self::mapCommonFeeNumber($item->crmHouseFeeNumber);

            return array_map(
                fn ($feeNumber) => [...$commonFields, ...$feeNumber],
                $crmHouseFeeNumber
            );
        })->toArray();
    }

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

    private function mapSpaceFields($item): array
    {
        $crmBuildingCommonInfo = $item->crmBuildingCommonInfo;
        return [
            'doorplate'                 => $crmBuildingCommonInfo?->doorplate,
            'tax_id'                    => $crmBuildingCommonInfo?->tax_id,
            'block_id'                  => $crmBuildingCommonInfo?->block_id,
            'locate'                    => $crmBuildingCommonInfo?->locate,
            'extent_of_ownership'       => $crmBuildingCommonInfo?->extent_of_ownership,
            'building_build_licence_id' => $crmBuildingCommonInfo?->building_build_licence_id,
            'use_license_id'            => $crmBuildingCommonInfo?->use_license_id,
            'main_application'          => CrmHouseType::array()[$crmBuildingCommonInfo?->main_application] ?? null,
            'land_use_zoning'           => LandUseZoningType::array()[$crmBuildingCommonInfo?->land_use_zoning] ?? null ,
            'pre_sale_total_area'       => $crmBuildingCommonInfo?->pre_sale_total_area,
            'preserved_total_area'      => $crmBuildingCommonInfo?->preserved_total_area,
        ];
    }

    private function mapCommonFeeNumber(Collection $feeNumber): array
    {
        $sub = $feeNumber->whereNotNull('parent_id');
        $parent = $feeNumber->whereNull('parent_id')->pluck('value', 'id');

        $originalArray = $sub->reduce(function ($carry, $item) use ($parent) {
            $parentId = $item->parent_id;
            $type = $item->type;

            if (isset($parent[$parentId])) {
                if (!isset($carry[$type])) {
                    $carry[$type] = [];
                }

                $carry[$type][] = [
                    $parent[$parentId],
                    $item->value,
                ];
            }

            return $carry;
        }, []);

        return self::mergeArrays($originalArray);
    }

    public function mergeArrays($data): array
    {
        $water    = $data['water'] ?? [];
        $electric = $data['electric'] ?? [];

        return array_map(function ($electricItem, $waterItem) {
            return [...$electricItem ?? ['', ''], ...$waterItem ?? ['', '']];
        }, $electric, array_pad($water, count($electric), []));
    }
}