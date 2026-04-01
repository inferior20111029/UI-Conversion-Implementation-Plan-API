<?php

declare(strict_types=1);

namespace App\Services\Equipment\Space;

use App\Support\Abstract\Service;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

use Symfony\Component\HttpFoundation\Response;

use App\Repositories\Equipment\CrmEquipmentRepository;
use App\Repositories\Equipment\CrmEquipmentGroupMapRepository;

final class StoreService extends Service
{
    public function __construct(
        private readonly CrmEquipmentRepository         $crmEquipmentRepository,
        private readonly CrmEquipmentGroupMapRepository $crmEquipmentGroupMapRepository,
    ) {
    }

    /**
     * 判斷綁定戶別設備
     *
     * @return array
     */
    public function execute(): array
    {
        $equipmentRequests = request()->post('data');
        $equipmentGroupId  = (int) request()->post('equipment_group_id');
        $spaceIds          = is_array(request()->post('space_id')) ? request()->post('space_id') : [request()->post('space_id')];

        if($equipmentRequests === null) {
            $equipmentRequests = self::fetchEquipmentGroupResults($equipmentGroupId);
        }

        $equipmentResults = $this->fetchEquipmentResults($equipmentRequests);
        $equipmentToUpsert = $this->filterAndPrepareEquipment($equipmentRequests, $equipmentResults, count($spaceIds));

        if (!empty($equipmentToUpsert['failed'])) {
            $this->handleFailedEquipment($equipmentToUpsert['failed']);
        }

        return $equipmentToUpsert['toUpsert'];
    }

    /**
     * 新增綁定戶別設備
     *
     * @param  array  $equipmentToUpsert
     * @param  string  $spaceId
     *
     * @return void
     */
    public function create(array $equipmentToUpsert, string $spaceId): void
    {
        $upsertData = $this->prepareUpsertData($equipmentToUpsert, $spaceId);
        $this->crmEquipmentRepository->upsert($upsertData);
    }

    /**
     * 判斷批次綁定多戶戶別設備
     *
     * @return void
     */
    public function createMultiSpaceExecute(): Collection
    {
        $equipmentGroupId = (int)request()->post('equipment_group_id');
        $spaceIds  = request()->post('space_id');

        $equipmentRequests = self::fetchEquipmentGroupResults($equipmentGroupId, count($spaceIds));

        $equipmentResults = $this->fetchEquipmentResults($equipmentRequests);
        $equipmentToUpsert = $this->filterAndPrepareEquipment($equipmentRequests, $equipmentResults);

        if (!empty($equipmentToUpsert['failed'])) {
            $this->handleFailedEquipment($equipmentToUpsert['failed']);
        }

        return $equipmentResults;
    }

    /**
     * 新增批次綁定多戶戶別設備
     *
     * @return void
     */
    public function createMultiSpace(array $equipmentToUpsert)
    {
        $spaceIds  = request()->post('space_id');

        $upsertData =  collect($equipmentToUpsert)->flatMap(function ($equipmentGroup) use ($spaceIds) {
            $equipmentChunks = $equipmentGroup->chunk(ceil($equipmentGroup->count() / count($spaceIds)));

            return collect($spaceIds)->flatMap(function ($spaceId, $index) use ($equipmentChunks, $spaceIds) {

                if (isset($equipmentChunks[$index])) {
                    return $equipmentChunks[$index]
                        ->take(count($spaceIds))
                        ->map(function ($equipmentItem) use ($spaceId) {
                            return [
                                'id'         => $equipmentItem['id'],
                                'company_id' => crm('company_id'),
                                'comid'      => crm('community_id'),
                                'space_id'   => $spaceId,
                                'status'     => 1,
                            ];
                        });
                }

                return [];
            });
        })->toArray();

        $this->crmEquipmentRepository->upsert($upsertData);
    }

    /**
     * 單一設備資料
     *
     * @param  array  $equipmentRequests
     *
     * @return Collection
     */
    private function fetchEquipmentResults(array $equipmentRequests): Collection
    {
        return collect($equipmentRequests)->map(function ($request) {
            return $this->crmEquipmentRepository->fetchEquipmentByConditions(
                Arr::where(Arr::except($request, ['count']), function ($value) {
                    return !is_null($value);
                }) + ['status' => 0]
            );
        })->filter();
    }

    /**
     * 群組資料
     *
     * @param  int  $equipmentGroupId
     *
     * @return array
     */
    private function fetchEquipmentGroupResults(int $equipmentGroupId,int $fold = 1): array
    {
        $equipmentCounts = $this->crmEquipmentGroupMapRepository
            ->fetchByEquipmentGroupId($equipmentGroupId)
            ->pluck('count', 'equipment_id')
            ->toArray();

        $equipmentIds = array_keys($equipmentCounts);
        $equipmentRequests = $this->crmEquipmentRepository
            ->findByIds($equipmentIds)
            ->map(function ($equipment) use ($equipmentCounts, $fold) {
                return Arr::only(
                        $equipment->toArray(),['name', 'area', 'space', 'location', 'brand', 'model']) +
                         ['count' => $equipmentCounts[$equipment->id] * $fold];
            })->toArray();

        return $equipmentRequests;
    }

    /**
     * @param  array  $equipmentRequests
     * @param  Collection  $equipmentResults
     * @param  int  $count
     *
     * @return array[]
     */
    private function filterAndPrepareEquipment(array $equipmentRequests, Collection $equipmentResults, int $count = 1): array
    {
        $failedEquipmentNames = [];
        $equipmentToUpsert = [];

        foreach ($equipmentRequests as $index => $request) {
            $requestedCount = (int) $request['count'] ?? 0;

            if ($equipmentResults->get($index, collect())->count() < $requestedCount * $count) {
                $failedEquipmentNames[] = $request['name'];
            } else {
                $equipmentToUpsert[]    = $equipmentResults[$index]->take($requestedCount * $count);
            }
        }

        return [
            'failed'  => $failedEquipmentNames,
            'toUpsert' => $equipmentToUpsert,
        ];
    }

    /**
     * @param  array  $failedEquipmentNames
     *
     * @return void
     */
    private function handleFailedEquipment(array $failedEquipmentNames): void
    {
        $failedMessage = Arr::join($failedEquipmentNames, ', ', ' 和 ') . ' 設備不足';
        $this->fails($failedMessage, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param  Collection  $equipmentToUpsert
     * @param  string  $spaceId
     *
     * @return array
     */
    private function prepareUpsertData(array $equipmentToUpsert, string $spaceId): array
    {
        return collect($equipmentToUpsert)->flatMap(function ($equipment) use ($spaceId) {
            return $equipment->map(function ($item) use ($spaceId) {
                return [
                    'id'         => $item['id'],
                    'company_id' => crm('company_id'),
                    'comid'      => crm('community_id'),
                    'space_id'   => $spaceId,
                    'status'     => 1,
                ];
            });
        })->toArray();
    }
}