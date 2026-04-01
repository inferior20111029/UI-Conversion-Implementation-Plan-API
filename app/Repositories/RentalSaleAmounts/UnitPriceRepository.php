<?php

declare(strict_types=1);

namespace App\Repositories\RentalSaleAmounts;

use App\Models\UnitPriceSetting;
use Illuminate\Support\Collection;

class UnitPriceRepository
{
    public function findAll(): Collection
    {
        return UnitPriceSetting::where('company_id', crm('company_id'))
              ->where('comid', crm('community_id'))
              ->get();
    }

    /**
     * @param  array  $equipmentGroupId
     *
     * @return mixed
     */
    public function findCalculate(array $equipmentGroupId): Collection
    {
        return UnitPriceSetting::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->whereIn('crm_equipment_group_id', $equipmentGroupId)
            ->with([
                'crmEquipmentGroup' => fn ($query) => $query
                    ->where('company_id', crm('company_id'))
                    ->where('comid', crm('community_id')),
                'crmLayoutSetting' => fn ($query) => $query
                    ->where('company_id', crm('company_id'))
                    ->where('comid', crm('community_id')),
            ])
            ->whereHas('crmEquipmentGroup', function ($query) {
                $query->whereNotNull('id');
            })
            ->get();
    }

    /**
     * @param  array  $data
     *
     * @return int
     */
    public function update(array $data): int
    {
        return UnitPriceSetting::upsert($data, ['id']);
    }

    /**
     * @return Collection|null
     */
    public function findByUnitPrice(): ?Collection
    {
        return UnitPriceSetting::where('company_id', crm('company_id'))
             ->where('comid', crm('community_id'))
             ->with([
                 'crmEquipmentGroup' => fn ($query) => $query
                     ->where('company_id', crm('company_id'))
                     ->where('comid', crm('community_id')),
                 'crmLayoutSetting' => fn ($query) => $query
                     ->where('company_id', crm('company_id'))
                     ->where('comid', crm('community_id')),
             ])
             ->whereHas('crmEquipmentGroup', function ($query) {
                 $query->whereNotNull('id');
             })
             ->get();
    }

    /**
     *
     * @param array $ids
     *
     * @return int
     */
    public function destroy(array $ids): int
    {
        return UnitPriceSetting::destroy($ids);
    }
}