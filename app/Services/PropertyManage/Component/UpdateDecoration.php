<?php

declare(strict_types=1);

namespace App\Services\PropertyManage\Component;

final class UpdateDecoration
{
    use \App\Support\Trait\RenterContract\ColumnTrait;

    /**
     * 更新裝潢程度
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $decorationRequest = $updateInstance->request->post('decoration');

        $updateData = $this->fetchDecorationColumnData($decorationRequest)->noHaveMacro()->toColumnArray();

        $updateInstance->propertyData->decoration()->update($updateData);
    }
}
