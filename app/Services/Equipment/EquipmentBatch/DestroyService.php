<?php

declare(strict_types=1);

namespace App\Services\Equipment\EquipmentBatch;

use App\Support\Abstract\Service;

use App\Repositories\Equipment\CrmEquipmentRepository;

final class DestroyService extends Service
{
    public function __construct(
        private readonly CrmEquipmentRepository $crmEquipmentRepository,
    ) {
    }

    /**
     * 批次刪除元件
     *
     * @return void
     */
    public function execute(): void
    {
        $this->crmEquipmentRepository->forceDeleteIds(request()->get('ids'));
    }
}
