<?php

declare(strict_types=1);

namespace App\Repositories\Space;

use App\Models\CrmParkingSpaceSelect;

class CrmParkingSpaceSelectRepository
{
    /**
     * @param string $uuid
     * @param int $companyId
     * @return CrmParkingSpaceSelect|null
     */
    public function findByUuid(string $uuid, int $companyId): ?CrmParkingSpaceSelect
    {
        return CrmParkingSpaceSelect::whereCompanyId($companyId)
            ->where('space_id', $uuid)
            ->with('crmHouseFeeNumber')
            ->first();
    }

    public function findAll()
    {
        return CrmParkingSpaceSelect::whereIn(
            'company_id',
            [crm('company_id'), 0]
        )->get();
    }

    public function option()
    {
        return CrmParkingSpaceSelect::whereIn('company_id', [crm('company_id'), 0])->get(['type', 'value']);
    }

    /**
     * @param array $data
     * @return int
     */
    public function insert(array $data): CrmParkingSpaceSelect
    {
        return CrmParkingSpaceSelect::create($data);
    }

    /**
     * @param string $id
     * @param array $updateData
     * @return bool
     */
    public function update(string $id, array $updateData): bool
    {
        return CrmParkingSpaceSelect::find($id)->update($updateData);
    }

    /**
     *
     * @param array $ids
     *
     * @return int
     */
    public function destroy(array $ids): int
    {
        return CrmParkingSpaceSelect::destroy($ids);
    }

    /**
     * @param  string  $spaceId
     *
     * @return int
     */
    public function cancel(string $spaceId): int
    {
        return CrmParkingSpaceSelect::where('space_id', $spaceId)->update(
            ['space_id' => null]
        );
    }

    /**
     * @param  string  $id
     * @param  string  $spaceId
     *
     * @return int
     */
    public function configuration(string $id, string $spaceId): int
    {
        return CrmParkingSpaceSelect::where('id', $id)->update(
            ['space_id' => $spaceId]
        );
    }
}
