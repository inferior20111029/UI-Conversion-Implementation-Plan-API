<?php

declare(strict_types=1);

namespace App\Services\Excel\Equipment;

use Throwable;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

use App\Support\Abstract\Service;
use App\Jobs\ImportEquipmentsJob;
use App\Jobs\ImportDeleteEquipmentsJob;
use App\Jobs\ImportDetailEquipmentsJob;

use App\Http\Requests\Excel\UploadExcelRequest;
use App\Repositories\Equipment\CrmEquipmentRepository;
use App\Repositories\Equipment\JobProgressRepository;
use App\Repositories\Space\CrmBuildingSpaceRepository;

use Rap2hpoutre\FastExcel\FastExcel;

final class ImportService extends Service
{
    use \App\Support\Trait\Space\CrmBuildingSpaceTrait;
    use \App\Support\Trait\Excel\ExportTrait;

    public function __construct(
        private readonly CrmEquipmentRepository      $crmEquipmentRepository,
        private readonly CrmBuildingSpaceRepository  $crmBuildingSpaceRepository,
        private readonly JobProgressRepository       $jobProgressRepository,
    ) {
    }

    /**
     * Excel 匯入
     *
     * @return array
     */
    public function execute(UploadExcelRequest $request, $identity)
    {
        $creator = crm('user_id') . '_' . crm('username');
        $file = $request->file;
        switch ($identity) {
                // 總量匯入
            case 'example-empty-space':
                [$importedData, $errorMessages] = self::ImportEquipmentExcel($file);

                if (!empty($errorMessages)) {
                    return $errorMessages[0];
                } else {
                    self::ImportEquipmentsJob($importedData, $creator);
                }

                break;
                // 細項匯入
            case 'example-detail':
                $groupedSpaces = self::fetchGroupedSpaces();

                [$importedData, $errorMessages] = self::importEquipmentDetailExcel($groupedSpaces, $file);

                if (!empty($errorMessages)) {
                    return $errorMessages ?? [];
                } else {
                    self::ImportDetailEquipmentsJob($importedData, $creator);
                }
                break;
                // 總量匯入-清空
            case 'clear-example-empty-space':
                [$importedData, $errorMessages] = self::ImportEquipmentExcel($file);

                if (!empty($errorMessages)) {
                    return $errorMessages;
                } else {
                    self::importClearEquipmentExcel($importedData, $creator);
                }
                break;

                // 細項匯入-清空
            case 'clear-example-detail':

                $groupedSpaces = self::fetchGroupedSpaces();

                [$importedData, $errorMessages] = self::importEquipmentDetailExcel($groupedSpaces, $file);

                if (!empty($errorMessages)) {
                    return $errorMessages ?? [];
                } else {
                    self::importClearDetailEquipmentExcel($importedData, $creator);
                }
                break;
        }

        return [];
    }

    /**
     * 執行總量匯入 Job
     *
     * @param  array  $importedData
     * @param  string  $creator
     *
     * @return void
     */
    private function ImportEquipmentsJob(array $importedData, string $creator): void
    {
        foreach ($importedData as $rowValues) {
            ImportEquipmentsJob::dispatch(
                crm('company_id'),
                crm('community_id'),
                $creator,
                $rowValues
            )->onConnection('group_distribution')
                ->onQueue('default');

            $insert[] = [
                'company_id'   => crm('company_id'),
                'comid'        => crm('community_id'),
                'queue'        => 'import_equipment',
                'max'          => count($importedData),
            ];
        }

        $this->jobProgressRepository->insert($insert ?? []);
    }

    /**
     * 執行細目匯入 Job
     *
     * @param  array  $importedData
     * @param  string  $creator
     *
     * @return void
     */
    private function ImportDetailEquipmentsJob(array $importedData, string $creator)
    {
        $chunks = array_chunk($importedData, 1000);

        foreach ($chunks as $chunk) {
            ImportDetailEquipmentsJob::dispatch(
                crm('company_id'),
                crm('community_id'),
                $creator,
                $chunk
            )->onConnection('detail_distribution')
                ->onQueue('default');

            $insert[] = [
                'company_id'   => crm('company_id'),
                'comid'        => crm('community_id'),
                'queue'        => 'import_detail_equipment',
                'max'          => count($importedData),
            ];
        }
        $this->jobProgressRepository->insert($insert ?? []);
    }


    /**
     *
     * @return array
     */
    private function importEquipmentExcel($file): array
    {
        $errorMessages = [];
        $importedData = [];
        $currentRowNumber = 2;

        (new FastExcel())->import($file, function ($row) use (&$errorMessages, &$importedData, &$currentRowNumber) {
            $rowValues = array_values($row);
            $rowErrors = $this->validateRow($rowValues, $currentRowNumber, 43, 6);

            if (!empty($rowErrors)) {
                $errorMessages = array_merge($errorMessages, $rowErrors);
            } else {
                $importedData[] = $rowValues;
            }

            if (count($importedData) % 1000 == 0) {
                gc_collect_cycles();
            }

            $currentRowNumber++;
        });

        return [$importedData, $errorMessages];
    }

    /**
     * @param  Collection  $groupedSpaces
     * @param $file
     *
     * @return array
     */
    private function importEquipmentDetailExcel(Collection $groupedSpaces, $file): array
    {
        $errorMessages = [];
        $importedData = [];
        $currentRowNumber = 2;

        (new FastExcel())->import($file, function ($row) use (&$errorMessages, &$importedData, &$currentRowNumber, $groupedSpaces) {
            $rowValues = array_values($row);
            $rowErrors = $this->validateRow($rowValues, $currentRowNumber, 47, 11);

            if (!empty($rowErrors)) {
                $errorMessages = array_merge($errorMessages, $rowErrors);
                return;
            }

            $district  = $rowValues[3] ?? null;
            $building  = $rowValues[4] ?? null;
            $staircase = $rowValues[5] ?? null;
            $floor     = $rowValues[6] ?? null;
            $household = $rowValues[7] ?? null;

            $key = $district . $building . $staircase . $floor . $household;

            if (!isset($groupedSpaces[$key])) {
                $errorMessages[] = "第" . ($currentRowNumber + 1) . "列, 找不到相關空間位置(區名, 棟別, 梯間, 樓層, 戶別/公設, 空間屬性)";
                return;
            }

            $rowValues[47] = $groupedSpaces[$key]->first();
            $importedData[] = $rowValues;

            if (count($importedData) % 1000 == 0) {
                gc_collect_cycles();
            }

            $currentRowNumber++;
        });

        return [$importedData, $errorMessages];
    }

    /**
     * 驗證excel 資料
     * @param  array  $rowValues
     * @param  int  $rowNumber
     * @param  int  $total
     * @param  int  $specialNumber
     *
     * @return array
     */
    private function validateRow(array $rowValues, int $rowNumber, int $total, int $specialNumber): array
    {
        $errors = [];

        if (count($rowValues) < $total) {
            $errors[] = "Excel資料格式有誤，請使用匯出模板格式匯入。";
            return $errors;
        }

        if (empty($rowValues)) {
            $errors[] = "請確認資料是否有填寫。";
            return $errors;
        }

        if (count(array_filter($rowValues)) != 0 && (
            empty($rowValues[0]) || is_null($rowValues[0]) ||
            empty($rowValues[1]) || is_null($rowValues[1]) ||
            empty($rowValues[2]) || is_null($rowValues[2]) ||
            empty($rowValues[$specialNumber]) || is_null($rowValues[$specialNumber])
        )) {
            $errors[] = "Excel資料有誤，請確認第" . ($rowNumber + 1) . "行的必填欄位是否有填寫。";
        }

        return $errors;
    }

    /**
     * @param $importedData
     *
     * @return void
     * @throws \Throwable
     */
    private function importClearDetailEquipmentExcel(array $importedData, string $creator)
    {
        $companyId   = crm('company_id');
        $communityId = crm('community_id');

        $deleteJob = new ImportDeleteEquipmentsJob($companyId, $communityId);

        Bus::batch([$deleteJob])
            ->finally(function () use ($companyId, $communityId, $creator, $importedData) {
                $chunks = array_chunk($importedData, 1000);
                foreach ($chunks as $chunk) {
                    ImportDetailEquipmentsJob::dispatch(
                        $companyId,
                        $communityId,
                        $creator,
                        $chunk
                    )->onConnection('detail_distribution')
                        ->onQueue('default');
                }
            })
            ->onConnection('clear_group_distribution')
            ->onQueue('default')
            ->dispatch();
    }

    /**
     * @param  array  $importedData
     * @param  string  $creator
     *
     * @return void
     * @throws Throwable
     */
    private function importClearEquipmentExcel(array $importedData, string $creator)
    {
        $companyId   = crm('company_id');
        $communityId = crm('community_id');

        $deleteJob = new ImportDeleteEquipmentsJob($companyId, $communityId);

        Bus::batch([$deleteJob])
            ->then(function () use ($companyId, $communityId, $creator, $importedData) {
                foreach ($importedData as $rowValues) {
                    ImportEquipmentsJob::dispatch(
                        $companyId,
                        $communityId,
                        $creator,
                        $rowValues
                    )->onConnection('group_distribution')
                        ->onQueue('default');
                }
            })
            ->onConnection('clear_group_distribution')
            ->onQueue('default')
            ->dispatch();
    }

    /**
     * @return Collection|null
     */
    private function fetchGroupedSpaces(): ?Collection
    {
        return $this->crmBuildingSpaceRepository->findByAll()
            ->groupBy(function ($item) {
                return $item->district_name . $item->building_name .  $item->staircase_name . $item->floor_name . $item->household_name;
            })
            ->map(function ($groupedItems) {
                return $groupedItems->pluck('space_id');
            });
    }
}
