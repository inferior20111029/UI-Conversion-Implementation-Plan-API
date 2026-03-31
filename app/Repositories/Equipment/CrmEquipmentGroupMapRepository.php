<?php

declare(strict_types=1);

namespace App\Repositories\Equipment;

use App\Models\CrmEquipmentGroupMap;
use Illuminate\Support\Collection;

class CrmEquipmentGroupMapRepository
{
    public function findAll(): Collection
    {
        return CrmEquipmentGroupMap::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->get();
    }

    public function findParent(): Collection
    {
        return CrmEquipmentGroupMap::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->where('parent', 0)
            ->get();
    }

    /**
     * @param  int  $id
     *
     * @return Collection|null
     */
    public function fetchByEquipmentGroupId(int $id): ?Collection
    {
        return CrmEquipmentGroupMap::where('equipment_group_id' , $id)->get();
    }


    /**
     * @param  array  $data
     *
     * @return bool
     */
    public function insert(array $data): bool
    {
        return CrmEquipmentGroupMap::insert($data);
    }

    /**
     * @param string $id
     * @param array $updateData
     * @return bool
     */
    public function update(string $id, array $updateData): bool
    {
        return CrmEquipmentGroupMap::find($id)->update($updateData);
    }

    public function updateOrCreate(array $updateData)
    {
        return CrmEquipmentGroupMap::updateOrCreate(
            [
                'company_id'         => $updateData['company_id'],
                'comid'              => $updateData['comid'],
                'equipment_id'       => $updateData['equipment_id'],
                'equipment_group_id' => $updateData['equipment_group_id'],
            ],
            [
                'count'      => $updateData['count'],
                'updated_at' => now(),
            ],
        );
    }

    /**
     * @param  string  $originalId
     * @param  string  $targetId
     *
     * @return bool
     */
    public function merge(string $originalId, string $targetId): int
    {
        return CrmEquipmentGroupMap::where('parent', $originalId)->update(
            ['parent' => $targetId]
        );
    }

    /**
     *
     * @param array $ids
     *
     * @return int
     */
    public function destroy(array $ids): int
    {
        return CrmEquipmentGroupMap::destroy($ids);
    }

    /**
     * @param  int  $companyId
     * @param  int  $communityId
     *
     * @return int
     */
    public function forceDelete(int $companyId, int $communityId): int
    {
        return CrmEquipmentGroupMap::where('company_id', $companyId)
            ->where('comid', $communityId)
            ->forceDelete();
    }

    /**
     * @param  array       $equipmentDel
     * @param  string|int  $id
     *
     * @return int
     */
    public function delGroupByEquipment(array $equipmentDel, string|int $id): int
    {
        return CrmEquipmentGroupMap::where('equipment_group_id', $id)
            ->whereIn('equipment_id', $equipmentDel)
            ->delete();
    }
}
