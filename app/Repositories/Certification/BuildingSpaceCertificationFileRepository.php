<?php

declare(strict_types=1);

namespace App\Repositories\Certification;

use App\Models\BuildingSpaceCertificationFile;

class BuildingSpaceCertificationFileRepository
{
    /**
     * @param  array  $data
     *
     * @return bool|null
     */
    public function insert(array $data): ?bool
    {
        return BuildingSpaceCertificationFile::insert($data);
    }

    /**
     *
     * @param array $ids
     *
     * @return int
     */
    public function forceDelete(array $ids): int
    {
        return BuildingSpaceCertificationFile::whereIn('certification_id', $ids)->forceDelete();
    }
}
