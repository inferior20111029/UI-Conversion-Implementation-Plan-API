<?php

declare(strict_types=1);

namespace App\Services\Excel\ConfigurationCommon;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;

use App\Support\Abstract\Service;
use App\Support\Enum\CrmHouseType;
use App\Support\Enum\ConfigurationType;
use App\Support\Enum\LandUseZoningType;

use App\Http\Requests\Excel\UploadExcelRequest;
use App\Repositories\Space\CrmBuildingCommonSpaceRepository;
use App\Repositories\Space\CrmBuildingCommonInfoRepository;
use App\Repositories\Space\CrmLanguageSystemRepository;
use App\Repositories\Space\CrmHouseFeeNumberRepository;
use App\Repositories\Space\CrmLanguageConfigurationRepository;

use Rap2hpoutre\FastExcel\FastExcel;

final class ImportService extends Service
{
    use \App\Support\Trait\Excel\ConfigurationExportTrait;
    use \App\Support\Trait\Excel\ConfigurationCommonExportTrait;
    use \App\Support\Trait\Space\CrmBuildingSpaceTrait;
    use \App\Support\Trait\Excel\FeeNumberExportTrait;
    use \App\Support\Trait\Excel\ExportTrait;

    public function __construct(
        private readonly CrmLanguageConfigurationRepository $crmLanguageConfigurationRepository,
        private readonly CrmLanguageSystemRepository        $crmLanguageSystemRepository,
        private readonly CrmBuildingCommonSpaceRepository   $crmBuildingCommonSpaceRepository,
        private readonly CrmBuildingCommonInfoRepository    $crmBuildingCommonInfoRepository,
        private readonly CrmHouseFeeNumberRepository        $crmHouseFeeNumberRepository
    ) {
    }

    /**
     * Excel 匯入
     *
     * @return array
     */
    public function execute(UploadExcelRequest $request, $identity): array
    {
        $file = $request->file;
        switch ($identity) {
                // 空間配置匯入
            case 'space':
                $importSpaceExcel = self::ImportSpaceExcel($file);

                if (!empty($importSpaceExcel[1])) {
                    return self::fetchErrorMessages($importSpaceExcel[1]);
                } else {
                    $this->insertExcel($importSpaceExcel[0]);
                }
                break;

                // 水電號匯入
            case 'fee-number':
                $importSpaceExcel = self::ImportFeeNumberExcel($file);

                if (!empty($importSpaceExcel[1])) {
                    return $importSpaceExcel[1];
                } else {
                    self::updateCrmHouseFeeNumber($importSpaceExcel[0]['water'], 'water');
                    self::updateCrmHouseFeeNumber($importSpaceExcel[0]['electric'], 'electric');
                }
                break;

                // 清除空間配置匯入
            case 'clear-space':
                $importSpaceExcel = self::ImportSpaceExcel($file);

                if (!empty($importSpaceExcel[1])) {
                    return self::fetchErrorMessages($importSpaceExcel[1]);
                } else {
                    $this->crmBuildingCommonSpaceRepository->forceDelete(null);
                    $this->crmBuildingCommonInfoRepository->forceDelete();

                    $this->insertExcel($importSpaceExcel[0]);
                }

                break;

                // 水電號-資料
            case 'clear-fee-number':
                $importSpaceExcel = self::ImportFeeNumberExcel($file);

                if (!empty($importSpaceExcel[1])) {
                    return $importSpaceExcel[1];
                } else {
                    $this->crmHouseFeeNumberRepository->delete();
                    self::updateCrmHouseFeeNumber($importSpaceExcel[0]['water'], 'water');
                    self::updateCrmHouseFeeNumber($importSpaceExcel[0]['electric'], 'electric');
                }

                break;
        }
        return [];
    }

    private function ImportSpaceExcel($file)
    {
        $fetchOptionGroupBy = collect($this->fetchOptionGroupBy());
        $currentRowNumber = 1;
        $errorMessages    = [];
        $importedData = (new FastExcel())->import($file, function ($row) use ($fetchOptionGroupBy, &$errorMessages, &$currentRowNumber) {
            $rowValues = array_values($row);

            if ($rowValues[5] !== '') {
                $keys = ['district', 'building', 'staircase', 'floor', 'public'];

                $mappedConfigurations = $this->getMappedConfigurations($keys, $fetchOptionGroupBy, $rowValues, $currentRowNumber, $errorMessages);

                $mergedData = array_reduce($mappedConfigurations, function ($carry, $item) {
                    return [...$carry, ...$item];
                }, self::mapCommonFields($rowValues));
                $currentRowNumber++;

                return $mergedData;
            }

        })->toArray();


        return [$importedData, $errorMessages];
    }

    /**
     * @param $file
     *
     * @return array
     * @throws \OpenSpout\Common\Exception\IOException
     * @throws \OpenSpout\Common\Exception\UnsupportedTypeException
     * @throws \OpenSpout\Reader\Exception\ReaderNotOpenedException
     */
    private function importFeeNumberExcel($file): array
    {
        $crmBuildingSpaces = $this->crmBuildingCommonSpaceRepository->findByAll()
            ->groupBy(fn ($item) => $item->building_name . $item->staircase_name . $item->floor_name . $item->household_name);

        $errorMessages = [];
        $currentRowNumber = 2;
        $importedData = [
            'water' => [],
            'electric' => [],
        ];

        (new FastExcel())->import($file, function ($row) use (&$errorMessages, &$currentRowNumber, $crmBuildingSpaces, &$importedData) {
            $rowValues = array_values($row);
            $groupKey = implode('', array_slice($rowValues, 1, 4));

            if($rowValues[5] !== '') {
                if (!isset($crmBuildingSpaces[$groupKey])) {
                    $errorMessages[] = sprintf('行數(%d)空間資料不存在，請先至空間配置功能建立完成再重新匯入', $currentRowNumber);
                } else {
                    $spaceId = $crmBuildingSpaces[$groupKey][0]->space_id;

                    if (!isset($importedData['water'][$rowValues[7]])) {
                        $importedData['water'][$rowValues[7]] = [
                            'space_id' => $spaceId,
                            'value' => $rowValues[7],
                            'children' => [],
                        ];
                    }
                    $importedData['water'][$rowValues[7]]['children'][] = $rowValues[8];

                    if (!isset($importedData['electric'][$rowValues[5]])) {
                        $importedData['electric'][$rowValues[5]] = [
                            'space_id' => $spaceId,
                            'value'    => $rowValues[5],
                            'children' => [],
                        ];
                        $importedData['electric'][$rowValues[5]]['children'][] = $rowValues[6];
                    }
                }
            }

            $currentRowNumber++;
        });

        return [$importedData, $errorMessages];
    }

    /**
     * @param  array  $keys
     * @param  Collection  $fetchOptionGroupBy
     * @param  array  $rowValues
     * @param  int  $currentRowNumber
     * @param $errorMessages
     *
     * @return array
     */
    private function getMappedConfigurations(array $keys, Collection $fetchOptionGroupBy, array $rowValues, int $currentRowNumber, &$errorMessages): array
    {
        $mappedConfigurations = [];

        foreach ($keys as $i => $key) {
            $mappedConfigurations[$key] = $this->mapConfiguration($fetchOptionGroupBy, $key, $rowValues[$i]) ?? [];
            if (empty($mappedConfigurations[$key])) {
                $errorMessages[$currentRowNumber][$key] = $rowValues[$i];
            }
        }
        return $mappedConfigurations;
    }

    /**
     * @param  array  $errorMessages
     *
     * @return array
     */
    private function fetchErrorMessages(array $errorMessages): array
    {
        $errorList = [];

        foreach ($errorMessages as $index => $error) {
            $errorDetails = [];

            foreach (ConfigurationType::array() as $configKey => $configType) {
                if (!empty($error[$configKey])) {
                    $errorDetails[] = "($configType:{$error[$configKey]})";
                }
            }

            if ($errorDetails) {
                $lineNumber = $index + 1;
                $errorList[] = "第{$lineNumber}行未找到對應的空間名稱資料" . implode('', $errorDetails);
            }
        }

        return $errorList;
    }


    /**
     * @param  array  $rowValues
     *
     * @return array
     */
    private function mapCommonFields(array $rowValues): array
    {
        return [
            'comid'                     => crm('community_id'),
            'company_id'                => crm('company_id'),
            'space_id'                  => str()->uuid()->toString(),
            'doorplate'                 => $rowValues[5] ?? null,
            'tax_id'                    => $rowValues[6] ?? null,
            'block_id'                  => $rowValues[7] ?? null,
            'locate'                    => $rowValues[8] ?? null,
            'extent_of_ownership'       => $rowValues[9] ?? null,
            'building_build_licence_id' => $rowValues[10] ?? null,
            'use_license_id'            => $rowValues[11] ?? null,
            'main_application'          => array_flip(CrmHouseType::array())[$rowValues[12]],
            'land_use_zoning'           => array_flip(LandUseZoningType::array())[$rowValues[13]],
            'pre_sale_total_area'       => $rowValues[14] ?? null,
            'preserved_total_area'      => $rowValues[15] ?? null,
        ];
    }

    /**
     * @param  array  $data
     * @param  array  $keysToExtract
     *
     * @return array
     */
    private function extractParameters(array $data, array $keysToExtract): array
    {
        return array_map(
            fn ($item) => array_intersect_key($item, array_flip($keysToExtract)),
            $data
        );
    }

    /**
     * @param  array  $importData
     *
     * @return void
     */
    private function insertExcel(array $importData)
    {
        $now = now();
        $commonInfo = self::extractParameters($importData, self::fetchExtractCommonInfo());

        $commonInfo = self::mergeByBlockId($commonInfo, $now);

        $buildingCommonInfoIds = [];
        foreach ($commonInfo as $info) {
            $crmBuildingCommon = $this->crmBuildingCommonInfoRepository->updateOrCreate($info);
            $buildingCommonInfoIds[$crmBuildingCommon['block_id']] = $crmBuildingCommon->id;
        }

        $commonSpaces = self::extractParameters($importData, self::fetchExtractCommonSpace());

        $commonSpacesWithIds = array_map(fn($commonSpace) => [
            ...$this->removeKeys($commonSpace, ['block_id', 'public', 'public_name', 'public_natsort']),
            'created_at' => $now,
            'updated_at' => $now,
            'household' => $commonSpace['household'],
            'household_name' => $commonSpace['household_name'],
            'household_natsort' => $commonSpace['household_natsort'],
            'building_common_info_id' => $buildingCommonInfoIds[$commonSpace['block_id']],
        ], $commonSpaces);

        $this->crmBuildingCommonSpaceRepository->insert($commonSpacesWithIds);
    }

    /**
     * @param  array  $data
     * @param  Carbon  $now
     *
     * @return array
     */
    private function mergeByBlockId(array $data,Carbon $now): array
    {
        $mergedData = [];

        foreach ($data as $item) {
            $blockId = $item['block_id'];
            $item['created_at'] = $now;
            $item['updated_at'] = $now;
            $mergedData[$blockId] ??= $item;
        }

        return array_values($mergedData);
    }
}