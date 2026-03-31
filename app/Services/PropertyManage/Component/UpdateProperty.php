<?php

declare(strict_types=1);

namespace App\Services\PropertyManage\Component;

final class UpdateProperty
{
    use \App\Support\Trait\PropertyManage\ColumnTrait;

    /**
     * 更新物件資料
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $columnData = $this->fetchContractColumnData($updateInstance->request);
        $updateData = $columnData->excludeColumn('uuid')->noHaveMacro()->toColumnArray();
        $updateInstance->propertyData->update($updateData);
    }
}
