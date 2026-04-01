<?php

declare(strict_types=1);

namespace App\Services\RentalSaleAmounts\UnitPrice;

use App\Support\Abstract\Service;

use App\Repositories\RentalSaleAmounts\UnitPriceRepository;

final class StoreService extends Service
{
    public function __construct(
        private readonly UnitPriceRepository $unitPriceRepository,
    ) {
    }

    public function create()
    {
        $updateData = array_map(function ($item) {
            return [
                'company_id'             => crm('company_id'),
                'comid'                  => crm('community_id'),
                'id'                     => $item['id'],
                'name'                   => $item['name'],
                'crm_equipment_group_id' => $item['equipment_group'],
                'crm_layout_setting_id'  => $item['layout_setting'],
                'default'                => $item['default'],
                'suggest'                => $item['suggest'],
            ];
        }, request()->post('data'));

        $this->unitPriceRepository->update($updateData);
        $this->unitPriceRepository->destroy(request()->post('del'));
    }
}
