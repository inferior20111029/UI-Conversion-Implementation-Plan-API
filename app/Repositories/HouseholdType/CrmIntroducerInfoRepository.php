<?php

declare(strict_types=1);

namespace App\Repositories\HouseholdType;

use Illuminate\Support\Collection;

use App\Models\CrmIntroducerInfo;

class CrmIntroducerInfoRepository
{
    /**
     * @param  int  $propertyInfoId
     *
     * @return Collection
     */
    public function findByPropertyInfoId(int $propertyInfoId): Collection
    {
        return CrmIntroducerInfo::where('property_info_id', $propertyInfoId)
            ->with([
                'crmClient' => fn ($query) => $query->whereCompanyId(crm('company_id')),
                'crmClient.crmClientContact'
            ])->get();

    }

    /**
     * @param  array  $updateData
     *
     * @return CrmIntroducerInfo|null
     */
    public function updateOrCreate(array $updateData): ?CrmIntroducerInfo
    {
        return CrmIntroducerInfo::updateOrCreate(
            [
                'client_id'          => $updateData['client_id'],
                'property_info_id'   => $updateData['property_info_id'],
            ],
            [
                'construction_company' => $updateData['construction_company'],
                'community'            => $updateData['community'],
                'housing_situation'    => $updateData['housing_situation'],
                'updated_at'           => now(),
            ],
        );
    }

    /**
     * @param $data
     *
     * @return bool
     */
    public function insert($data): bool
    {
        return CrmIntroducerInfo::insert($data);
    }

    /**
     * @param  array  $ids
     *
     * @return int
     */
    public function forceDelete(array $ids): int
    {
        return CrmIntroducerInfo::whereIn('id', $ids)
            ->forceDelete();
    }
}
