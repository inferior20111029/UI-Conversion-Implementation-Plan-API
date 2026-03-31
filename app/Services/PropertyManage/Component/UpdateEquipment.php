<?php

declare(strict_types=1);

namespace App\Services\PropertyManage\Component;

use App\Models\AttachedEquipment;

final class UpdateEquipment
{
    use \App\Support\Trait\PropertyManage\ColumnTrait;

    /**
     * 更新附設設備
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $equipmentRequest = (array) $updateInstance->request->post('equipment');

        $updateInstance->propertyData->attachedEquipments()->forceDelete();

        $crmEquipmentData = array_map(function (array $crmEquipment): AttachedEquipment {
            return new AttachedEquipment(
                $this->fetchEquipmentColumnData($crmEquipment)->toColumnArray()
            );
        }, $equipmentRequest);

        $updateInstance->propertyData->attachedEquipments()->saveMany($crmEquipmentData);
    }
}
