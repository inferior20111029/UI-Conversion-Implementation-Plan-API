<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Bill;

use App\Support\Abstract\Service;

use App\Http\Requests\RenterContract\BillRequest;

use App\Models\ContractBill;
use App\Models\RenterContract;

use App\Services\RenterContract\Component\UpdateBill;
use App\Services\RenterContract\Component\UpdateAmount;

final class UpdateService extends Service
{
    use \App\Support\Trait\RenterContract\BillTrait;

    /**
     * 更新帳單資料
     *
     * @param \App\Models\RenterContract $contract 合約資料
     * @param \App\Models\ContractBill $bill 帳單資料
     * @param \App\Http\Requests\RenterContract\BillRequest $request Request
     *
     * @return void
     */
    public function execute(RenterContract $contract, ContractBill $bill, BillRequest $request): void
    {
        (new UpdateBill())->execute($bill, $request);
        (new UpdateAmount())->execute($bill, $request, $this->fetchSpaceDefaultAmount($contract));
    }
}
