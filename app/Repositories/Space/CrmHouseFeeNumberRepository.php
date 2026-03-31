<?php

declare(strict_types=1);

namespace App\Repositories\Space;

use App\Models\CrmHouseFeeNumber;

class CrmHouseFeeNumberRepository
{
    /**
     * @param  string  $spaceId
     * @param  string  $type
     *
     * @return mixed
     */
    public function findByEnergy(string $spaceId, string $type)
    {
        return CrmHouseFeeNumber::where('space_id', $spaceId)
            ->where('type', $type)
            ->with([
                'crmBuildingSpace' => fn ($q) => $q->whereCompanyId(crm('company_id'))
                    ->where('comid', crm('community_id'))
                    ->when($spaceId, fn ($q, $spaceId) => $q->where('space_id', $spaceId))
                    ->whereNull('deleted_at')
            ])
            ->get();
    }

    /**
     * @param array $data
     * @return CrmHouseFeeNumber|null
     */
    public function create(array $data): ?CrmHouseFeeNumber
    {
        return CrmHouseFeeNumber::create($data);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insert(array $data)
    {
        return CrmHouseFeeNumber::insert($data);
    }

    /**
     * @param  array  $data
     *
     * @return int
     */
    public function upsert(array $data): int
    {
        return CrmHouseFeeNumber::upsert($data, ['id']);
    }

    /**
     * @param string $spaceId
     * @return int
     */
    public function deleteBySpaceId(string $spaceId): int
    {
        return CrmHouseFeeNumber::where('space_id', $spaceId)->delete();
    }

    /**
     * @return int
     */
    public function delete(): int
    {
        return CrmHouseFeeNumber::whereCompanyId(crm('company_id'))
            ->where('comid', crm('community_id'))
            ->delete();
    }

    /**
     * @param  array  $ids
     *
     * @return int
     */
    public function forceDeleteIds(array $ids): int
    {
        return CrmHouseFeeNumber::whereIn('id', $ids)
            ->orWhereIn('parent_id', $ids)
            ->forceDelete();
    }

    /**
     * @param  array  $ids
     *
     * @return int
     */
    public function forceDeleteChildrenIds(array $ids): int
    {
        return CrmHouseFeeNumber::whereIn('id', $ids)
            ->forceDelete();
    }
}
