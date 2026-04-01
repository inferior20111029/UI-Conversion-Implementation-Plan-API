<?php

declare(strict_types=1);

namespace App\Services\Space\WarrantySelect;

use App\Support\Abstract\Service;

use App\Repositories\Warranty\CrmWarrantySelectRepository;

final class StoreService extends Service
{
    use \App\Support\Trait\Certification\ColumnTrait;
    public function __construct(
        private readonly CrmWarrantySelectRepository $crmWarrantySelectRepository,
    ) {
    }

    /**
     * 新增保固選項
     *
     * @return void
     */
    public function execute($request): void
    {
        $this->crmWarrantySelectRepository->insert([
            'comid' => crm('community_id'),
            'value' => $request->post('value'),
        ]);
    }
}