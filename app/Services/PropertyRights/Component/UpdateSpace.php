<?php

declare(strict_types=1);

namespace App\Services\PropertyRights\Component;

final class UpdateSpace
{
    use \App\Support\Trait\PropertyRights\ColumnTrait;

    /**
     * 更新戶別資料
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $column = $this->fetchSpaceColumnData($updateInstance->request);
        $updateData = $column->onlyColumn('crm_layout_setting_id', 'remark')->toColumnArray();

        $updateInstance->spaceData->update($updateData);
        $this->removeCustomLayout($updateInstance, $updateData);
    }

    /**
     * 移除自訂格局資料
     *
     * @param \App\Services\PropertyRights\Component\UpdateInstance $updateInstance
     * @param array $updateData
     *
     * @return void
     */
    private function removeCustomLayout(UpdateInstance $updateInstance, array $updateData): void
    {
        $settingId = (int) data_get($updateData, 'crm_layout_setting_id');

        if ($updateInstance->spaceData->spaceLayout->isNotEmpty() && 0 !== $settingId) {
            $updateInstance->spaceData->spaceLayout()->forceDelete();
        }
    }
}
