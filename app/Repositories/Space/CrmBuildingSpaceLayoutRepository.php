<?php

declare(strict_types=1);

namespace App\Repositories\Space;

use Illuminate\Support\Collection;

use App\Models\CrmBuildingSpaceLayout;

class CrmBuildingSpaceLayoutRepository
{
    /**
     * @param  array|null  $spaceId
     *
     * @return int
     */
    public function forceDelete(?array $spaceId): int
    {
        return CrmBuildingSpaceLayout::when($spaceId, fn ($query) => $query->whereIn('space_id', $spaceId))
            ->forceDelete();
    }

    /**
     * @param array $updateData
     * @return int
     */
    public function upsert(array $updateData): int
    {
        return  CrmBuildingSpaceLayout::upsert($updateData, ['id', 'space_id']);
    }
}
