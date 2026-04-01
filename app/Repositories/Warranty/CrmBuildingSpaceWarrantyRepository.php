<?php

declare(strict_types=1);

namespace App\Repositories\Warranty;

use App\Models\CrmBuildingSpaceWarranty;

class CrmBuildingSpaceWarrantyRepository
{
    /**
     * @param  array  $data
     *
     * @return bool
     */
    public function upsert(array $data): int
    {
       return CrmBuildingSpaceWarranty::upsert($data, ['id']);
    }

    /**
     * @param  array  $spaceIds
     *
     * @return int
     */
    public function forceDelete(array $spaceIds): int
    {
        return CrmBuildingSpaceWarranty::whereIn('space_id', $spaceIds)
            ->forceDelete();
    }
}