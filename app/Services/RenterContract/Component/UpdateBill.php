<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Component;

use Illuminate\Http\Request;

use App\Models\ContractBill;

final class UpdateBill
{
    use \App\Support\Trait\RenterContract\ColumnTrait;

    /**
     * 更新帳單資料
     *
     * @param ContractBill $bill
     * @param Request $request
     *
     * @return void
     */
    public function execute(ContractBill $bill, Request $request): void
    {
        $column = $this->fetchContractBillColumnData($request)
            ->onlyColumn('start_time', 'end_time', 'include_tax', 'paid')
            ->toColumnArray();

        $bill->update($column);
    }
}
