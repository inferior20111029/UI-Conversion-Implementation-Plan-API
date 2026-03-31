<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Component;

final class UpdateFees
{
    use \App\Support\Trait\RenterContract\ColumnTrait;

    /**
     * 更新合約費用
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $fees = $updateInstance->contractData->fees;
        $feesRequest = (array) $updateInstance->request->post('fees');

        if (!empty($fees) && empty($feesRequest)) {
            $updateInstance->contractData->fees()->forceDelete();
            return;
        }

        if (empty($feesRequest)) {
            return;
        }

        $updateData = $this->fetchFeesColumnData($feesRequest)->noHaveMacro()->toColumnArray();
        $updateInstance->contractData->fees()->update($updateData);
    }
}
