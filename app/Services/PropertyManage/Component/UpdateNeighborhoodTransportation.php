<?php

declare(strict_types=1);

namespace App\Services\PropertyManage\Component;

use App\Models\NeighborhoodTransportation;

final class UpdateNeighborhoodTransportation
{
    use \App\Support\Trait\PropertyManage\ColumnTrait;

    /**
     * 更新附近交通資料
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $transportationRequest = (array) $updateInstance->request->post('transportation');

        if (empty($transportationRequest)) {
            $updateInstance->propertyData->neighborhoodTransportation()->forceDelete();
            return;
        }

        $updateInstance->propertyData->neighborhoodTransportation()->forceDelete();

        $transportationData = array_map(function (array $values): NeighborhoodTransportation {
            return new NeighborhoodTransportation(
                $this->fetchTransportationColumnData($values)->noHaveMacro()->toColumnArray()
            );
        }, $transportationRequest);

        $updateInstance->propertyData->neighborhoodTransportation()->saveMany($transportationData);
    }
}
