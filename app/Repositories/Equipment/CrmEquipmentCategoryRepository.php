<?php

declare(strict_types=1);

namespace App\Repositories\Equipment;

use Illuminate\Support\Collection;

use App\Models\CrmEquipmentCategory;

class CrmEquipmentCategoryRepository
{
    public function findAll(): Collection
    {
        return CrmEquipmentCategory::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->get();
    }

    /**
     * @param  array  $data
     *
     * @return Collection
     */
    public function findByExcel(array $data): Collection
    {
        return CrmEquipmentCategory::where($data)
            ->get();
    }

    public function findParent(): Collection
    {
        return CrmEquipmentCategory::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->where('parent', 0)
            ->get();
    }

    /**
     * @param array $data
     * @return int
     */
    public function insert(array $data): CrmEquipmentCategory
    {
        return CrmEquipmentCategory::create($data);
    }

    /**
     * @param array $data
     * @return int
     */
    public function insertGetId($data): int
    {
        return CrmEquipmentCategory::insertGetId($data);
    }

    /**
     * @param string $id
     * @param array $updateData
     * @return bool
     */
    public function update(string $id, array $updateData): bool
    {
        return CrmEquipmentCategory::find($id)->update($updateData);
    }

    /**
     * @param  string  $originalId
     * @param  string  $targetId
     *
     * @return bool
     */
    public function merge(string $originalId, string $targetId): int
    {
        return CrmEquipmentCategory::where('parent', $originalId)->update(
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
        return CrmEquipmentCategory::destroy($ids);
    }
}
