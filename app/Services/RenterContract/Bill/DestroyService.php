<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Bill;

use App\Support\Abstract\Service;

use App\Models\ContractBill;

use App\Support\Data\ContractBillData;

final class DestroyService extends Service
{
    /**
     * 刪除帳單資料
     *
     * @param \App\Models\ContractBill $bill 帳單資料
     *
     * @return void
     */
    public function execute(ContractBill $bill): void
    {
        $deleteBy = crm('user_id');
        $column = (new ContractBillData(compact('deleteBy')))->filterColumn()->toColumnArray();

        $bill->update($column);
    }
}
