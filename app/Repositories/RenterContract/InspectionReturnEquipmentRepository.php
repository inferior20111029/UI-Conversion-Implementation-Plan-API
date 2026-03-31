<?php

declare(strict_types=1);

namespace App\Repositories\RenterContract;

use App\Models\InspectionReturnEquipment;

class InspectionReturnEquipmentRepository
{
    /**
     * @param $data
     *
     * @return bool
     */
    public function insert($data): bool
    {
        return InspectionReturnEquipment::insert($data);
    }
}
