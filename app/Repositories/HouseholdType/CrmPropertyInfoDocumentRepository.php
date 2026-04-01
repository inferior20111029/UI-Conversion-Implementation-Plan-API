<?php

declare(strict_types=1);

namespace App\Repositories\HouseholdType;

use Illuminate\Support\Collection;
use App\Models\CrmPropertyInfoDocument;

class CrmPropertyInfoDocumentRepository
{
    /**
     * @param $data
     *
     * @return bool
     */
    public function insert($data): bool
    {
        return CrmPropertyInfoDocument::insert($data);
    }

    /**
     * @param  int  $propertyInfoId
     * @param  array  $fileId
     *
     * @return int
     */
    public function forceDelete(int $propertyInfoId, array $fileId): int
    {
        return CrmPropertyInfoDocument::where('property_info_id', $propertyInfoId)
            ->whereIn('file_id', $fileId)
            ->forceDelete();
    }

    /**
     * @param $propertyInfoId
     *
     * @return Collection
     */
    public function findByPropertyInfoId($propertyInfoId): Collection
    {
        return CrmPropertyInfoDocument::where('property_info_id', $propertyInfoId)
            ->withWhereHas('file')
            ->get();
    }
}
