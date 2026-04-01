<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Component;

use App\Models\ContractPaymentCycle;

final class UpdatePaymentCycle
{
    use \App\Support\Trait\RenterContract\ColumnTrait;

    /**
     * 更新合約付款週期
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $paymentCycle = $updateInstance->contractData->paymentCycle;
        $paymentCycleRequest = (array) $updateInstance->request->post('paymentCycle');

        if (!empty($paymentCycle) && empty($paymentCycleRequest)) {
            $updateInstance->contractData->paymentCycle()->forceDelete();
            return;
        }

        if (empty($paymentCycleRequest)) {
            return;
        }

        $relationKey = $this->fetchContractRelationKey(ContractPaymentCycle::class);
        $updateData = $this->fetchPaymentCycleColumnData($paymentCycleRequest)
            ->excludeColumn($relationKey)
            ->toColumnArray();

        $updateInstance->contractData->paymentCycle()->update($updateData);
    }
}
