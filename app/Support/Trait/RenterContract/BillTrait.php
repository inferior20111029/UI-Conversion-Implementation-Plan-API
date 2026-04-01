<?php

declare(strict_types=1);

namespace App\Support\Trait\RenterContract;

use App\Support\Enum\BillFeesType;

use App\Models\RenterContract;

trait BillTrait
{
    /**
     * 取得戶別預設帳單金額資料
     *
     * @param \App\Models\RenterContract $contract 合約資料
     *
     * @return array
     */
    public function fetchSpaceDefaultAmount(RenterContract $contract): array
    {
        return [
            [
                'lineItem' => BillFeesType::contractPrice->name,
                'price' => (int) $contract?->fees?->price
            ],
            [
                'lineItem' => BillFeesType::carparkPrice->name,
                'price' => (int) $contract?->attachedCarpark?->sum('price')
            ],
            [
                'lineItem' => BillFeesType::managementFee->name,
                'price' => (int) $contract?->fees?->management_fee
            ]
        ];
    }
}
