<?php

declare(strict_types=1);

namespace App\Services\Equipment\Equipment;

use App\Support\Abstract\Service;

use App\Repositories\Equipment\CrmEquipmentRepository;

final class DestroyService extends Service
{
    public function __construct(
        private readonly CrmEquipmentRepository $crmEquipmentRepository,
    ) {
    }

    /**
     * 刪除元件
     *
     * @return void
     */
    public function execute($id): void
    {
        $this->crmEquipmentRepository->destroy([$id]);
    }
}
