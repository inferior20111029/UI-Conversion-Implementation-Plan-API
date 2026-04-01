<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Component;

final class UpdateBank
{
    use \App\Support\Trait\RenterContract\ColumnTrait;

    /**
     * 更新合約銀行帳戶
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $bank = $updateInstance->contractData->bank;
        $bankRequest = (array) $updateInstance->request->post('bank');

        if (!empty($bank) && empty($bankRequest)) {
            $updateInstance->contractData->bank()->forceDelete();
            return;
        }

        if (empty($bankRequest)) {
            return;
        }

        $updateData = $this->fetchContractBankColumnData($bankRequest)->excludeColumn('renter_contract_id')->toColumnArray();
        $updateInstance->contractData->bank()->update($updateData);
    }
}
