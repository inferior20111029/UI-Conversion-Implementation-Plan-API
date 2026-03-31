<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Component;

use Illuminate\Support\Collection;

final class UpdateEquipment
{
    use \App\Support\Trait\RenterContract\ColumnTrait;

    /**
     * 更新附設設備
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $equipment = $updateInstance->contractData->attachedEquipment;
        $equipmentRequest = (array) $updateInstance->request->post('equipment');

        if ($equipment->isNotEmpty() && empty($equipmentRequest)) {
            $updateInstance->contractData->attachedEquipment()->forceDelete();
            return;
        }

        if (empty($equipmentRequest)) {
            return;
        }

        [$create, $update, $delete] = $this->fetchHandleData($updateInstance, $equipment, $equipmentRequest);

        $updateInstance->contractData->attachedEquipment()->upsert([...$create, ...$update], ['id']);
        $updateInstance->contractData->attachedEquipment()->whereIn('id', $delete)->forceDelete();
    }

    /**
     * 取得處理資料
     *
     * @param \App\Services\RenterContract\Component\UpdateInstance $updateInstance 更新實例
     * @param \Illuminate\Support\Collection $equipment 當前擁有的附設設備
     * @param array $equipmentRequest 附設設備資料
     *
     * @return array
     */
    private function fetchHandleData(UpdateInstance $updateInstance, Collection $equipment, array $equipmentRequest): array
    {
        $morph = $this->morphColumn($updateInstance->contractData);

        $create = [];
        $update = [];

        foreach ($equipmentRequest as $equipmentId) {
            $target = $equipment->where('crm_equipment_id', (int) $equipmentId);
            $id = $target->value('id');

            $columnData = $this->fetchEquipmentColumnData((int) $equipmentId)
                ->excludeColumn('display_state')
                ->replace(compact('id') + $morph)
                ->toColumnArray();

            if ($target->isNotEmpty()) {
                $update[] = $columnData;
                $equipment->forget($target->keys()->first());

                continue;
            }

            $create[] = $columnData;
        }

        return [$create, $update, $equipment->pluck('id')->all()];
    }
}
