<?php

declare(strict_types=1);

namespace App\Services\Equipment\Group;

use App\Support\Abstract\Service;

use App\Repositories\Equipment\CrmEquipmentGroupRepository;

final class DestroyService extends Service
{
    public function __construct(
        private readonly CrmEquipmentGroupRepository $crmEquipmentGroupRepository,
    ) {
    }

    /**
     * 刪除元件類別
     *
     * @return void
     */
    public function execute($id): void
    {
        $this->crmEquipmentGroupRepository->destroy([$id]);
    }
}
