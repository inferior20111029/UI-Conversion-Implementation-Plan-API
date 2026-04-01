<?php

declare(strict_types=1);

namespace App\Services\PropertyRights\Component;

use Illuminate\Support\Arr;

use App\Support\Data\AgreedDedicatedAreaSettingData;

final class UpdateAgreedDedicatedAreaSetting
{
    use \App\Support\Trait\PropertyRights\ColumnTrait;

    /**
     * 更新約定專用面積設定
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
        $areaSetting = (array) $updateInstance->request->post('agreedDedicatedAreaSetting');
        $preservation = (int) data_get($areaSetting, 'preservation');

        return compact('preservation');
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
        return (new AgreedDedicatedAreaSettingData(compact('spaceId') + $areaSetting))->toColumnArray();
    }

    /**
     * 更新約定專用面積設定
     *
     * @param \App\Services\PropertyRights\Component\UpdateInstance $updateInstance
     * @param array $updateData
     *
     * @return void
     */
    private function update(UpdateInstance $updateInstance, array $updateData): void
    {
        $updateInstance->spaceData
            ->agreedDedicatedAreaSetting()
            ->updateOrCreate(
                Arr::only($updateData, 'space_id'),
                $updateData
            );
    }
}
