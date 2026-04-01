<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Component;

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
        $decoration = $updateInstance->contractData->decoration;
        $decorationRequest = (array) $updateInstance->request->post('decoration');

        if (!empty($decoration) && empty($decorationRequest)) {
            $updateInstance->contractData->decoration()->forceDelete();
            return;
        }

        if (empty($decorationRequest)) {
            return;
        }

        $updateData = $this->fetchDecorationColumnData($decorationRequest)->noHaveMacro()->toColumnArray();
        $updateInstance->contractData->decoration()->update($updateData);
    }
}
