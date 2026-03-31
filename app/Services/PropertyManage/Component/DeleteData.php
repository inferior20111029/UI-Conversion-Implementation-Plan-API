<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Component;

use App\Support\Data\RenterContractData;

use App\Models\RenterContract;

final class DeleteData
{
    /**
     * @param RenterContract $contract 合約資料
     */
    public function __construct(
        private readonly RenterContract $contract
    ) {
    }

    /**
     * 刪除合約
     *
     * @return void
     */
    public function execute(): void
    {
        $column = (new RenterContractData(['deleteBy' => crm('user_id')]))->filterColumn()->toColumnArray();
        $this->contract->update($column);
    }
}
