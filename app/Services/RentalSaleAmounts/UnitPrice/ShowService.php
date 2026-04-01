<?php

declare(strict_types=1);

namespace App\Services\RentalSaleAmounts\UnitPrice;

use App\Support\Abstract\Service;
use App\Repositories\Equipment\CrmEquipmentGroupRepository;
use App\Repositories\Space\CrmLayoutSettingRepository;
use App\Repositories\RentalSaleAmounts\UnitPriceRepository;

final class ShowService extends Service
{
    public function __construct(
        private readonly CrmEquipmentGroupRepository $crmEquipmentGroupRepository,
        private readonly CrmLayoutSettingRepository  $crmLayoutSettingRepository,
        private readonly UnitPriceRepository         $unitPriceRepository,
    ) {
    }

    /**
     * 回傳單價設定資料
     *
     * @return array
     */
    public function execute(): array
    {
        return [
            'community'       => '',
            'equipment_group' => $this->crmEquipmentGroupRepository->UnitPriceOption()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                ];
            }),
            'layout_setting' => $this->crmLayoutSettingRepository->UnitPriceOption()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                    ];
                }),
            'unit_price'    => $this->unitPriceRepository->findAll()
        ];
    }
}
