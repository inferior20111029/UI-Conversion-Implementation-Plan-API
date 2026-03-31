<?php

declare(strict_types=1);

namespace App\Services\Equipment\Category;

use App\Support\Abstract\Service;

use App\Repositories\Equipment\CrmEquipmentCategoryRepository;

final class UpdateService extends Service
{
    public function __construct(
        private readonly CrmEquipmentCategoryRepository $crmEquipmentCategoryRepository,
    ) {
    }

    /**
     * 更新元件類別
     *
     * @param $id
     * @return void
     */
    public function update($id): void
    {
        $insertData = [
            'name' => request()->post('name'),
        ];

        $this->crmEquipmentCategoryRepository->update($id, $insertData);
    }

    public function merge(): void
    {
        $this->crmEquipmentCategoryRepository->merge(
            request()->post('original'),
            request()->post('target')
        );
    }
}
