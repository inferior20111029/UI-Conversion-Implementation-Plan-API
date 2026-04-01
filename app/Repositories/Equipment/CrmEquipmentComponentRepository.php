<?php

declare(strict_types=1);

namespace App\Repositories\Equipment;

use Illuminate\Support\Collection;

use App\Models\CrmEquipmentComponent;

class CrmEquipmentComponentRepository
{

    /**
     * @param array $data
     * @return int
     */
    public function insert(array $data): CrmEquipmentComponent
    {
        return CrmEquipmentComponent::create($data);
    }

    /**
     * @param  array  $data
     *
     * @return int
     */
    public function upsert(array $data): int
    {
        return CrmEquipmentComponent::upsert($data, ['id']);
    }

    /**
     * 元件構件資料
     *
     * @param  int  $crmEquipmentId
     *
     * @return Collection
     */
    public function fetchEquipmentById(int $crmEquipmentId): Collection
    {
        return CrmEquipmentComponent::where('crm_equipment_id', $crmEquipmentId)->get();
    }

    /**
     * @param  array  $id
     *
     * @return int
     */
    public function forceDeleteById(array $id): int
    {
        return CrmEquipmentComponent::whereIn('id', $id)
            ->forceDelete();
    }
}