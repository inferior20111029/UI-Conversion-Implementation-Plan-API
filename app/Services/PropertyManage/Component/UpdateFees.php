<?php

declare(strict_types=1);

namespace App\Services\PropertyManage\Component;

final class UpdateFees
{
    use \App\Support\Trait\PropertyManage\ColumnTrait;

    /**
     * 更新物件費用
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $feesRequest = $updateInstance->request->post('fees');

        $updateData = $this->fetchFeesColumnData($feesRequest)->noHaveMacro()->toColumnArray();

        $updateInstance->propertyData->fees()->update($updateData);
    }
}
