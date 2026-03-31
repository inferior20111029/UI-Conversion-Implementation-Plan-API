<?php

declare(strict_types=1);

namespace App\Services\PropertyRights\Component;

use Illuminate\Support\Arr;

use App\Support\Data\AreaSettingData;

final class UpdateAreaSetting
{
    use \App\Support\Trait\PropertyRights\ColumnTrait;

    /**
     * 更新土地面積
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $areaSetting = $this->fetchAreaSettingData($updateInstance);
        $updateData = $this->fetchUpdateData($updateInstance->fetchSpaceId(), $areaSetting);
        $this->update($updateInstance, $updateData);
    }

    /**
     * 取得 Request 資料
     *
     * @param \App\Services\PropertyRights\Component\UpdateInstance $updateInstance
     *
     * @return array
     */
    private function fetchAreaSettingData(UpdateInstance $updateInstance): array
    {
        $areaSetting = (array) $updateInstance->request->post('areaSetting');
        $decimalPlace = (int) data_get($areaSetting, 'decimalPlace');

        return compact('decimalPlace');
    }

    /**
     * 取得更新資料
     *
     * @param string $spaceId
     * @param array $areaSetting
     *
     * @return array
     */
    private function fetchUpdateData(string $spaceId, array $areaSetting): array
    {
        return (new AreaSettingData(compact('spaceId') + $areaSetting))->toColumnArray();
    }

    /**
     * 更新面積設定
     *
     * @param \App\Services\PropertyRights\Component\UpdateInstance $updateInstance
     * @param array $updateData
     *
     * @return void
     */
    private function update(UpdateInstance $updateInstance, array $updateData): void
    {
        $updateInstance->spaceData
            ->areaSetting()
            ->updateOrCreate(
                Arr::only($updateData, 'space_id'),
                $updateData
            );
    }
}
