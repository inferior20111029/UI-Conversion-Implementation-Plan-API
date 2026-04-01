<?php

declare(strict_types=1);

namespace App\Repositories\RenterContract;

use App\Models\RenterContractCache;

class RenterContractCacheRepository
{
    /**
     * @param  array  $updateData
     *
     * @return bool
     */
    public function update(array $updateData): bool
    {
       return RenterContractCache::updateOrCreate([
            'space_id' => $updateData['space_id'],
        ])->update([
            'renter_contract_id' => $updateData['renter_contract_id'],
        ]);
    }

    /**
     * @param  string  $spaceId
     *
     * @return RenterContractCache|null
     */
    public function findSpace(string $spaceId): ?RenterContractCache
    {
        return RenterContractCache::where('space_id', $spaceId)
            ->first();
    }
}