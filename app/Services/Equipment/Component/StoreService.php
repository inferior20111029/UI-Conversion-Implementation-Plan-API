<?php

declare(strict_types=1);

namespace App\Services\Equipment\Component;

use App\Support\Abstract\Service;

use App\Repositories\Equipment\CrmEquipmentComponentRepository;

final class StoreService extends Service
{
    public function __construct(
        private readonly CrmEquipmentComponentRepository $crmEquipmentComponentRepository,
    ) {
    }

    /**
     * @return int
     */
    public function create(): int
    {
        $data = [
            ...['crm_equipment_id' => 0],
            ...request()->except('id'),
        ];

        return $this->crmEquipmentComponentRepository
            ->insert($data)
            ->id;
    }
}