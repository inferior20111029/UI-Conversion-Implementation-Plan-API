<?php

declare(strict_types=1);

namespace App\Services\PropertyRights\Component;

use Illuminate\Support\Collection;

use App\Support\Data\CrmBuildingSpacePlanningData;

final class UpdatePlanning
{
    /**
     * 更新戶別規劃型態
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $spacePlanning = $updateInstance->spaceData->planning;
        $planningRequest = (array) $updateInstance->request->post('planning');

        if ($spacePlanning->isNotEmpty() && empty($planningRequest)) {
            $updateInstance->spaceData->planning()->forceDelete();
            return;
        }

        if (empty($planningRequest)) {
            return;
        }

        [$create, $update, $delete] = $this->fetchHandleData($updateInstance, $spacePlanning, $planningRequest);

        $updateInstance->spaceData->planning()->upsert([...$create, ...$update], ['id']);
        $updateInstance->spaceData->planning()->whereIn('id', $delete)->forceDelete();
    }

    /**
     * 取得處理資料
     *
     * @param \App\Services\PropertyRights\Component\UpdateInstance $updateInstance 更新實例
     * @param \Illuminate\Support\Collection $spacePlanning 當前擁有的規劃型態
     * @param array $planningRequest 規劃型態資料
     *
     * @return array
     */
    private function fetchHandleData(UpdateInstance $updateInstance, Collection $spacePlanning, array $planningRequest): array
    {
        $create = [];
        $update = [];

        foreach ($planningRequest as $value) {
            $value += $updateInstance->request->all('spaceId');
            $type = (string) data_get($value, 'type');
            $planning = (string) data_get($value, 'planning');

            $target = $spacePlanning->where('type', $type)->where('planning', $planning);
            $id = $target->value('id');

            $column = (new CrmBuildingSpacePlanningData($value))
                ->replace(compact('id'))
                ->toColumnArray();

            if ($target->isNotEmpty()) {
                $update[] = $column;
                $spacePlanning->forget($target->keys()->first());

                continue;
            }

            $create[] = $column;
        }

        return [$create, $update, $spacePlanning->pluck('id')->all()];
    }
}
