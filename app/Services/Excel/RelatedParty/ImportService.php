<?php

declare(strict_types=1);

namespace App\Services\Excel\RelatedParty;

use App\Support\Abstract\Service;

use App\Http\Requests\Excel\UploadExcelRequest;
use App\Repositories\Space\CrmBuildingSpaceRepository;
use App\Repositories\HouseholdType\CrmPropertyInfoRepository;
use App\Repositories\HouseholdType\CrmPropertyTransactionInfoRepository;
use App\Repositories\HouseholdType\CrmClientRepository;
use App\Repositories\HouseholdType\CrmClientHasCompanyRepository;

use Rap2hpoutre\FastExcel\FastExcel;

final class ImportService extends Service
{
    use \App\Support\Trait\Space\CrmBuildingSpaceTrait;
    use \App\Support\Trait\Excel\ExportTrait;
    use \App\Support\Trait\Excel\RelatedPartyImportTrait;
    use \App\Support\Trait\ContractingParty\ConvertDateTrait;

    public function __construct(
        private readonly CrmBuildingSpaceRepository           $crmBuildingSpaceRepository,
        private readonly CrmPropertyInfoRepository            $crmPropertyInfoRepository,
        private readonly CrmPropertyTransactionInfoRepository $crmPropertyTransactionInfoRepository,
        private readonly CrmClientRepository                  $crmClientRepository,
        private readonly CrmClientHasCompanyRepository        $crmClientHasCompanyRepository,
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
                // 匯入
            case 'import':
                $importSpaceExcel = self::ImportSpaceExcel($file);
                if (!empty($importSpaceExcel[1])) {
                    return $importSpaceExcel[1];
                } else {

                    $this->insertExcel($importSpaceExcel[0]);
                }
                break;

                // 清除
            case 'clear-import':
                $this->deleteExcel();

                $importSpaceExcel = self::ImportSpaceExcel($file);

                if (!empty($importSpaceExcel[1])) {
                    return $importSpaceExcel[1];
                } else {
                    $this->insertExcel($importSpaceExcel[0]);
                }
                break;
        }

        return [];
    }

    /**
     * @param $file
     *
     * @return array
     */
    private function importSpaceExcel($file): array
    {
        $crmBuildingGroupBySpaces = $this->crmBuildingSpaceRepository->findByAll()
            ->groupBy(fn($item) => $item->building_name . $item->staircase_name . $item->floor_name . $item->household_name);

        $propertyInfoByBuild = $this->crmPropertyInfoRepository->findByEdit()
            ->pluck('build_date', 'space_id');

        $errorMessages = [];
        $currentRowNumber = 1;

        $importedData = (new FastExcel())->import($file, function ($row) use (&$errorMessages, &$currentRowNumber, $crmBuildingGroupBySpaces, $propertyInfoByBuild) {
            $rowValues = array_values($row);
            $groupKey = implode('', array_slice($rowValues, 1, 4));

            if($rowValues[5] !== '') {
                if (!isset($crmBuildingGroupBySpaces[$groupKey])) {
                    $errorMessages[] = sprintf('行數(%d)空間資料不存在，請先至空間配置功能建立完成再重新匯入', $currentRowNumber);
                } else {
                    $spaceId = $crmBuildingGroupBySpaces[$groupKey][0]['space_id'];
                    $buildDate = $propertyInfoByBuild[$spaceId] ?? null;

                    if ($buildDate && $rowValues[5] == self::convertToRepublicDate($buildDate)) {
                        $crmBuilding = $crmBuildingGroupBySpaces[$groupKey][0];

                        $locationInfo = array_filter([
                            $crmBuilding['district_name'],
                            $crmBuilding['building_name'],
                            $crmBuilding['staircase_name'],
                            $crmBuilding['floor_name'],
                            $crmBuilding['household_name'],
                        ]);

                        $errorMessages[] = sprintf(
                            '行數(%d) 戶別: %s 重複建置日期',
                            $currentRowNumber,
                            implode('-', $locationInfo)
                        );
                    } else {
                        return [...[$spaceId], ...$rowValues];
                    }
                }
                $currentRowNumber++;
            }
        })->filter()->toArray();

        return [$importedData, $errorMessages];
    }

    /**
     * @return void
     */
    private function deleteExcel(): void
    {
       $spaceId = $this->crmBuildingSpaceRepository->findByAll()
            ->pluck('space_id')
            ->toArray();

        $this->crmPropertyInfoRepository->forceDeleteBySpaceId($spaceId);
        $this->crmPropertyTransactionInfoRepository->forceDeleteBySpaceId($spaceId);
    }

    /**
     * @param  array  $importSpaceExcel
     *
     * @return void
     */
    public function insertExcel(array $importSpaceExcel): void
    {
        $clients = collect($importSpaceExcel)->mapWithKeys(function ($row) {
            return [$row[40] => self::fetchClient($row)];
        });

        $clientIds = $this->crmClientRepository->updateOrCreateBatch($clients->toArray());

        $clientIdentityNumbers = collect($importSpaceExcel)->mapWithKeys(function ($row) {
            return [$row[20] => self::fetchClient($row)];
        });

        $clientIdentityIds = $this->crmClientRepository->updateOrCreateBatch($clientIdentityNumbers->toArray());

        $companyData = collect($importSpaceExcel)->map(function ($row) use ($clientIds) {
            if($row[10] !== '個人') {
                return self::fetchClientHasCompany($row, $clientIds);
            }
        })->filter()->all();

        $this->crmClientHasCompanyRepository->insert($companyData);

        $now = now();
        $propertyInfoData = collect($importSpaceExcel)
            ->map(function ($row) use ($now) {
                return [
                    'space_id'       => $row[0],
                    'sign_date'      => $row[7] === '' ? null : self::convertDate((string)$row[7]),
                    'transfer_date'  => $row[8] === '' ? null : self::convertDate((string)$row[8]),
                    'build_date'     => self::convertDate((string)$row[6]),
                    'transfer_cause' => $row[9] ?? null,
                    'transfer_item'  => json_encode([false, false, false]),
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ];
            })
            ->unique('space_id')
            ->values();

        $this->crmPropertyInfoRepository->updateSpacesEdit($propertyInfoData->pluck('space_id')->toArray());

        $this->crmPropertyInfoRepository->insertBatch($propertyInfoData->toArray());

        $propertyInfoIds = $this->crmPropertyInfoRepository->findByEdit(
            collect($propertyInfoData)->pluck('space_id')->toArray(),
        )->pluck('id', 'space_id');

        $transactionInfoData = collect($importSpaceExcel)->flatMap(function ($row) use ($propertyInfoIds, $clientIdentityIds) {
            return self::fetchTransactionInfo($row, $propertyInfoIds, $clientIdentityIds);
        })
            ->values()
            ->toArray();

        $this->crmPropertyTransactionInfoRepository->insert($transactionInfoData);
    }
}