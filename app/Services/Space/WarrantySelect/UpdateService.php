<?php

declare(strict_types=1);

namespace App\Services\Space\WarrantySelect;

use App\Support\Abstract\Service;

use App\Repositories\Warranty\CrmWarrantySelectRepository;

final class UpdateService extends Service
{
    public function __construct(
        private readonly CrmWarrantySelectRepository $crmWarrantySelectRepository,
    ) {
    }

    /**
     * @param  int  $id
     *
     * @return void
     */
    public function execute(int $id)
    {
        $value = request()->post('value');

        $this->crmWarrantySelectRepository->upsert([
            'id'    => $id,
            'value' => $value,
            'comid' => crm('community_id'),
        ]);
    }
}