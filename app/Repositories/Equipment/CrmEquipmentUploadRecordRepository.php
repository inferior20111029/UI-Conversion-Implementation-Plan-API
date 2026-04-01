<?php

declare(strict_types=1);

namespace App\Repositories\Equipment;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\CrmEquipmentUploadRecord;

class CrmEquipmentUploadRecordRepository
{
    /**
     * @param $id
     *
     * @return CrmEquipmentUploadRecord|null
     */
    public function findById($id): ?CrmEquipmentUploadRecord
    {
        return CrmEquipmentUploadRecord::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->where('id', $id)
            ->with('crmBuildingSpace', function (Builder|belongsTo $crmBuildingSpace): void {
                $crmBuildingSpace
                    ->whereCompanyId(crm('company_id'))
                    ->whereComid(crm('community_id'));
            })
            ->first();
    }

    public function findAll(): Collection
    {
        return CrmEquipmentUploadRecord::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->with('crmBuildingSpace', function (Builder|belongsTo $crmBuildingSpace) {
                $crmBuildingSpace
                    ->whereCompanyId(crm('company_id'))
                    ->whereComid(crm('community_id'));
            })
            ->get();
    }

    /**
     * @param $data
     *
     * @return Collection
     */
    public function findByExcel($data): Collection
    {
        return CrmEquipmentUploadRecord::where($data)->get();
    }

    /**
     * @param  array  $data
     *
     * @return bool
     */
    public function insert(array $data): bool
    {
        return CrmEquipmentUploadRecord::insert($data);
    }

    /**
     * @param string $id
     * @param array $updateData
     * @return bool
     */
    public function update(string $id, array $updateData): bool
    {
        return CrmEquipmentUploadRecord::find($id)->update($updateData);
    }

    /**
     * @param  array  $data
     *
     * @return int
     */
    public function upsert(array $data): int
    {
        return CrmEquipmentUploadRecord::upsert($data, ['id']);
    }

    public function delByAvatar(array $avatar): int
    {
        return CrmEquipmentUploadRecord::whereIn('avatar', $avatar)->delete();
    }

    public function delByEquipmentId(array $ids): int
    {
        return CrmEquipmentUploadRecord::whereIn('crm_equipment_id', $ids)->delete();
    }

    /**
     *
     * @param array $ids
     *
     * @return int
     */
    public function destroy(array $ids): int
    {
        return CrmEquipmentUploadRecord::destroy($ids);
    }
}
