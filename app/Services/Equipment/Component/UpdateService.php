<?php

declare(strict_types=1);

namespace App\Services\Equipment\Component;

use App\Support\Abstract\Service;
use App\Repositories\Equipment\CrmEquipmentComponentRepository;

final class UpdateService extends Service
{
    public function __construct(
        private readonly CrmEquipmentComponentRepository $crmEquipmentComponentRepository,
    ) {
    }

    /**
     * @param  int  $id
     *
     * @return array
     */
    public function execute(int $id): int
    {
        $data = [
            ...['id' => $id, 'crm_equipment_id' => 0],
            ...request()->except(['id', 'crm_equipment_id'])
        ];

        return $this->crmEquipmentComponentRepository->upsert($data);
    }
}