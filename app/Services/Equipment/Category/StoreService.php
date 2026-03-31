<?php

declare(strict_types=1);

namespace App\Services\Equipment\Category;

use App\Support\Abstract\Service;

use App\Repositories\Equipment\CrmEquipmentCategoryRepository;

final class StoreService extends Service
{
    public function __construct(
        private readonly CrmEquipmentCategoryRepository $crmEquipmentCategoryRepository,
    ) {
    }

    public function create()
    {
        $insertData = [
            'company_id' => crm('company_id'),
            'comid'      => crm('community_id'),
            'name'       => request()->post('name'),
            'level'      => request()->post('identity') === 'type' ? 1 : 2,
            'parent'     => request()->post('identity') === 'type' ? 0 : request()->post('parent_id'),
        ];

        $this->crmEquipmentCategoryRepository->insert($insertData);
    }
}
