<?php

declare(strict_types=1);

namespace App\Repositories\Equipment;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\CrmEquipmentGroup;

class CrmEquipmentGroupRepository
{
    public function findAll(): Collection
    {
        return CrmEquipmentGroup::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->with('crmEquipmentGroupMap', function (Builder|HasMany $crmEquipmentGroupMap) {
                $crmEquipmentGroupMap
                    ->whereCompanyId(crm('company_id'))
                    ->where('comid', crm('community_id'))
                    ->withWhereHas('crmEquipment', function (Builder|belongsTo $spaceQuery) {
                        $spaceQuery
                            ->whereCompanyId(crm('company_id'))
                            ->where('comid', crm('community_id'))
                            ->with('crmTypeName', function (Builder|belongsTo $crmTypeName): void {
                                $crmTypeName
                                    ->whereCompanyId(crm('company_id'))
                                    ->where('comid', crm('community_id'));
                            })
                            ->with('crmSystemName', function (Builder|belongsTo $crmSystemName): void {
                                $crmSystemName
                                    ->whereCompanyId(crm('company_id'))
                                    ->where('comid', crm('community_id'));
                            });
                    });
            })
            ->get();
    }

    /**
     * @param  int|string  $id
     *
     * @return CrmEquipmentGroup|null
     */
    public function findById(int|string $id): ?CrmEquipmentGroup
    {
        return CrmEquipmentGroup::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->where('id', $id)
            ->with([
                'crmEquipmentGroupMap' => fn($query) => $query->where('company_id', crm('company_id'))
                    ->where('comid', crm('community_id'))
                    ->with([
                        'crmEquipment' => fn($equipmentQuery) => $equipmentQuery->where('company_id', crm('company_id'))
                            ->where('comid', crm('community_id'))
                            ->with([
                                'crmTypeName' => fn($typeQuery) => $typeQuery->where('company_id', crm('company_id'))
                                    ->where('comid', crm('community_id')),
                                'crmSystemName' => fn($systemQuery) => $systemQuery->where('company_id', crm('company_id'))
                                    ->where('comid', crm('community_id')),
                            ])
                    ])
            ])
            ->first();
    }

    /**
     * @return Collection|null
     */
    public function UnitPriceOption(): ?Collection
    {
        return CrmEquipmentGroup::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->get();
    }

    /**
     * @param array $data
     * @return CrmEquipmentGroup
     */
    public function create(array $data): CrmEquipmentGroup
    {
        return CrmEquipmentGroup::create($data);
    }

    /**
     * @param string $id
     * @param array $updateData
     * @return bool
     */
    public function update(string $id, array $updateData): bool
    {
        return CrmEquipmentGroup::find($id)->update($updateData);
    }

    /**
     *
     * @param array $ids
     *
     * @return int
     */
    public function destroy(array $ids): int
    {
        return CrmEquipmentGroup::destroy($ids);
    }
}
