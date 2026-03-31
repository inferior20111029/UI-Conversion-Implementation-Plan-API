<?php

declare(strict_types=1);

namespace App\Repositories\Equipment;

use Illuminate\Support\Collection;

use App\Models\CrmEquipmentScrap;

class CrmEquipmentScrapRepository
{
    /**
     * @param  array  $data
     *
     * @return bool
     */
    public function insert(array $data): bool
    {
        return CrmEquipmentScrap::insert($data);
    }
}
