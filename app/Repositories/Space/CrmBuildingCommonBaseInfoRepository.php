<?php

declare(strict_types=1);

namespace App\Repositories\Space;

use App\Models\CrmBuildingCommonBaseInfo;

class CrmBuildingCommonBaseInfoRepository
{
    use \App\Support\Trait\Paginate\PaginateTrait;

    /**
     * @param array $data
     * @return CrmBuildingCommonBaseInfo
     */
    public function insert(array $data): CrmBuildingCommonBaseInfo
    {
        return CrmBuildingCommonBaseInfo::create($data);
    }

    /**
     * @param  array  $data
     *
     * @return int
     */
    public function upsert(array $data): int
    {
        return CrmBuildingCommonBaseInfo::upsert($data, ['id']);
    }

    /**
     * @param  string  $spaceId
     *
     * @return mixed
     */
    public function findById(string $spaceId)
    {
        return CrmBuildingCommonBaseInfo::where('space_id', $spaceId)
            ->with([
                'managementMeasuresFile',
                'pictureAvatar'
            ])
            ->first();
    }
}