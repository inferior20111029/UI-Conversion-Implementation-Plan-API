<?php

declare(strict_types=1);

namespace App\Repositories\HouseholdType;

use Illuminate\Support\Collection;

use App\Models\CrmPropertyTransactionInfo;

class CrmPropertyTransactionInfoRepository
{
    /**
     * @param array $data
     * @return int
     */
    public function insertGetId($data): int
    {
        return CrmPropertyTransactionInfo::insertGetId($data);
    }

    /**
     * @param $data
     *
     * @return bool
     */
    public function insert($data): bool
    {
        return CrmPropertyTransactionInfo::insert($data);
    }

    /**
     * @param  string  $spaceId
     * @param  int  $id
     * @param  array  $clientIds
     *
     * @return int
     */
    public function forceDelete(string $spaceId, int $id, array $clientIds): int
    {
        return CrmPropertyTransactionInfo::where('space_id', $spaceId)
            ->where('property_info_id', $id)
            ->whereIn('client_id', $clientIds)
            ->forceDelete();
    }

    /**
     * @param  array  $spaceIds
     *
     * @return int
     */
    public function forceDeleteBySpaceId(array $spaceIds): int
    {
        return CrmPropertyTransactionInfo::whereIn('space_id', $spaceIds)
            ->forceDelete();
    }

    /**
     * @param  string  $spaceId
     * @param  int  $propertyInfoId
     *
     * @return Collection
     */
    public function findBySpaceId(string $spaceId, int $propertyInfoId): Collection
    {
        return CrmPropertyTransactionInfo::where('space_id', $spaceId)
            ->where('property_info_id', $propertyInfoId)
            ->get();
    }

    /**
     * @param  array  $spaceId
     * @param  array  $propertyInfoId
     *
     * @return Collection
     */
    public function findBySpaceIds(array $spaceIds, array $propertyInfoIds): Collection
    {
        return CrmPropertyTransactionInfo::whereIn('space_id', $spaceIds)
            ->whereIn('property_info_id', $propertyInfoIds)
            ->where(function ($query) {
                $query->where('mode', 'inhabitant')
                    ->orWhere('mode', 'related_promiser');
            })
            ->get();
    }
}