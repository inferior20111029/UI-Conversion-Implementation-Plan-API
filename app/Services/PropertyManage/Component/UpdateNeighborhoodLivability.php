<?php

declare(strict_types=1);

namespace App\Services\PropertyManage\Component;

use App\Models\NeighborhoodLivability;

final class UpdateNeighborhoodLivability
{
    use \App\Support\Trait\PropertyManage\ColumnTrait;

    /**
     * 更新附近生活機能資料
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $livabilityRequest = (array) $updateInstance->request->post('livability');

        if (empty($livabilityRequest)) {
            $updateInstance->propertyData->neighborhoodLivability()->forceDelete();
            return;
        }

        $updateInstance->propertyData->neighborhoodLivability()->forceDelete();

        $livabilityData = array_map(function (int $values): NeighborhoodLivability {
            return new NeighborhoodLivability(
                $this->fetchLivabilityColumnData($values)->noHaveMacro()->toColumnArray()
            );
        }, $livabilityRequest);

        $updateInstance->propertyData->neighborhoodLivability()->saveMany($livabilityData);
    }
}
