<?php

declare(strict_types=1);

namespace App\Services\Equipment\Category;

use App\Support\Abstract\Service;

use App\Repositories\Equipment\CrmEquipmentCategoryRepository;

final class DestroyService extends Service
{
    public function __construct(
        private readonly CrmEquipmentCategoryRepository $crmEquipmentCategoryRepository,
    ) {
    }

    /**
     * 刪除元件類別
     *
     * @return void
     */
    public function execute($id): void
    {
        $this->crmEquipmentCategoryRepository->destroy([$id]);
    }
}
