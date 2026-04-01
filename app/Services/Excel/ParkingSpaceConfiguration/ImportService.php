<?php

declare(strict_types=1);

namespace App\Services\Excel\ParkingSpaceConfiguration;

use Rap2hpoutre\FastExcel\FastExcel;

use App\Http\Requests\Excel\UploadExcelRequest;
use App\Support\Abstract\Service;
use App\Repositories\Space\CrmBuildingSpaceRepository;
use App\Repositories\Space\CrmParkingSpaceRepository;
use App\Repositories\Space\CrmBuildingCommonSpaceRepository;

final class ImportService extends Service
{
    use \App\Support\Trait\Excel\ConfigurationExportTrait;
    use \App\Support\Trait\Space\CrmBuildingSpaceTrait;
    use \App\Support\Trait\Excel\ExportTrait;

    public function __construct(
        private readonly CrmBuildingSpaceRepository       $crmBuildingSpaceRepository,
        private readonly CrmParkingSpaceRepository        $crmParkingSpaceRepository,
        private readonly CrmBuildingCommonSpaceRepository $crmBuildingCommonSpaceRepository,
    ) {
    }

    /**
     * Excel 匯入
     *
     * @return array
     */
    public function execute(UploadExcelRequest $request, $identity)
    {
        $file = $request->file;
        switch ($identity) {
                // 匯入資料
            case 'material':
                [$importedData, $errorMessages] = self::ImportValidateExcel($file);

                if ($errorMessages !== '') {
                    return $errorMessages;
                } else {
                    self::importedData($importedData);
                }

                break;

                // 清空匯入
            case 'clear':
                [$importedData, $errorMessages] = self::ImportValidateExcel($file);

                if ($errorMessages !== '') {
                    return $errorMessages;
                } else {
                    self::importedClearData();
                    self::importedData($importedData);
                }
                break;
        }
    }

    /**
     * @return array
     * @throws \OpenSpout\Common\Exception\IOException
     * @throws \OpenSpout\Common\Exception\UnsupportedTypeException
     * @throws \OpenSpout\Reader\Exception\ReaderNotOpenedException
     */
    private function ImportValidateExcel($file)
    {
        $crmCarSpaces = $this->crmBuildingCommonSpaceRepository->findByAll()
            ->mapWithKeys(fn ($item) => [$item->building_name . $item->staircase_name . $item->floor_name . $item->household_name => $item]);

        $crmBuildingSpaces = $this->crmBuildingSpaceRepository->findByAll()
            ->mapWithKeys(fn ($item) => [$item->building_name . $item->staircase_name . $item->floor_name . $item->household_name => $item]);

        $errorMessages = [];
        $array = (new FastExcel())->withoutHeaders()->import($file);

        $filteredArray = collect($array)
            ->filter(fn ($item) => $item[0] !== '車位號' && $item[1] !== '建號')
            ->filter(fn ($data) => !empty($data[2]) || !empty($data[3]) || !empty($data[4]) || !empty($data[5]) || !empty($data[6]));

        if ($filteredArray->isEmpty()) {
            return ['importedData' => [], 'errorMessages' => '匯入資料車位位置不得為空'];
        }

        $carListExcelData = $filteredArray->toArray();
        $errCarMsg = '';
        $errHouseholdMsg = '';

        foreach ($carListExcelData as $index => $carData) {
            $carName = implode('', array_slice($carData, 3, 4));
            $carSpaceId = isset($crmCarSpaces[$carName]) ? $crmCarSpaces[$carName]['space_id'] : null;
            if (is_null($carSpaceId)) {
                $errCarMsg .= ($index + 1) . ',';
            }

            // 所屬戶別空間位置
            $householdSpaceData = array_slice($carData, 28, 4);
            $spaceName = implode('', $householdSpaceData);

            $spaceId = isset($crmBuildingSpaces[$spaceName]) ? $crmBuildingSpaces[$spaceName]['space_id'] : null;
            if (is_null($spaceId) && !empty(array_filter($householdSpaceData))) {
                $errHouseholdMsg .= ($index + 1) . ',';
            }

            $carListExcelData[$index][33] = !is_null($spaceId);
            $carListExcelData[$index] = $this->mapCommonFields($carData, $carSpaceId, $spaceId);
        }

        if ($errHouseholdMsg) {
            $errorMessages[] = '行數(' . rtrim($errHouseholdMsg, ',') . ')所屬戶別空間資料不存在，請先至空間配置功能建立完成再重新匯入。';
        }

        if ($errCarMsg) {
            $errorMessages[] = '行數(' . rtrim($errCarMsg, ',') . ')車位配置空間資料不存在，請先至空間配置功能建立完成再重新匯入。';
        }

        return [array_values($carListExcelData), implode(' ', $errorMessages)];
    }

    private function importedData(array $carParkData)
    {
        $carParkDataMap = $this->crmParkingSpaceRepository->findByAll()
            ->mapWithKeys(fn ($parkingSpaceData) => [
                $parkingSpaceData->parking_number . $parkingSpaceData->car_space_id => $parkingSpaceData,
            ]);

        $upsert = [];
        $insert = [];

        foreach ($carParkData as $item) {
            $parkingSpaceKey = $item['parking_number'] . $item['car_space_id'];

            if (isset($carParkDataMap[$parkingSpaceKey])) {
                $item['id'] = $carParkDataMap[$parkingSpaceKey]['id'];
                $upsert[] = $item;
            } else {
                $insert[] = $item;
            }
        }

        if (!empty($upsert)) {
            $this->crmParkingSpaceRepository->upsert($upsert);
        }

        if (!empty($insert)) {
            $this->crmParkingSpaceRepository->insert($insert);
        }
    }

    /**
     * @param array $rowValues
     * @param string|null $carSpaceId
     * @param string|null $spaceId
     * @return array
     */
    private function mapCommonFields(array $rowValues, ?string $carSpaceId, ?string $spaceId): array
    {
        $carTypes = ['機車', '汽車', '電動車'];

        // 車位資訊
        $carInfo = [
            'id'                => str()->uuid()->toString(),
            'company_id'        => crm('company_id'),
            'comid'             => crm('community_id'),
            'parking_number'    => $rowValues[0], // 編號
            'application'       => 4, // 車位法定名稱
            'parking_attribute' => $rowValues[25], // 車位屬性
            'use_direction'     => $rowValues[26], // 使用方式
            'car_type'          => array_search('汽車', $carTypes) ?? 0, // 車位種類
            'parking_type'      => $rowValues[21], // 車位類型
            'parking_size'      => $rowValues[22], // 車位尺寸
            'sell_price'        => $rowValues[23] === '' ? 0 : $rowValues[23], // 車位售價
            'car_space_id'      => $carSpaceId ?? '', // 車位位置
            'space_id'          => $spaceId ?? '', // 戶別
            'updated_at'        => now(),
            'created_at'        => now(),

            // [預售]增設車位面積
            'default_extent_of_ownership_numerator'   => $rowValues[9] ?? 0, // 權利範圍(分子)
            'default_extent_of_ownership_denominator' => $rowValues[10] ?? 0, // 權利範圍(分母)
            'default_parking_meter'                   => $rowValues[11], // 車位坪數(平方公尺)
            'default_parking_area'                    => $rowValues[12], // 車位坪數(坪)
            'default_extent_of_ownership'             => ($rowValues[9] ?? 0) . '/' . ($rowValues[10] ?? 0),

            // [保存]增設車位面積
            'extent_of_ownership_numerator'          => $rowValues[15], // 權利範圍(分子)
            'extent_of_ownership_denominator'        => $rowValues[16], // 權利範圍(分母)
            'parking_square_meter'                   => $rowValues[17], // 車位坪數(平方公尺)
            'parking_area'                           => $rowValues[18], // 車位坪數(坪)
            'extent_of_ownership'                    => $rowValues[15] . '/' . $rowValues[16],

            // 土地持分
            'land_square_meter'   => $rowValues[19] == '' ? 0 : $rowValues[19],
            'land_area'           => $rowValues[20] == '' ? 0 : $rowValues[20],
        ];

        return $carInfo;
    }

    private function importedClearData()
    {
        $this->crmParkingSpaceRepository->forceDelete();
    }
}
