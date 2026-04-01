<?php

declare(strict_types=1);

namespace App\Services\PropertyRights\Component;

use Illuminate\Support\Arr;

use App\Support\Data\LandAreaData;

final class UpdateLandArea
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
        $landArea = $this->fetchLandAreaData($updateInstance);
        $updateData = $this->fetchUpdateData($updateInstance->fetchSpaceId(), $landArea);
        $this->update($updateInstance, $updateData);
    }

    /**
     * 取得 Request 資料
     *
     * @param \App\Services\PropertyRights\Component\UpdateInstance $updateInstance
     *
     * @return array
     */
    private function fetchLandAreaData(UpdateInstance $updateInstance): array
    {
        $landAre = (array) $updateInstance->request->post('landArea');
        $dedicated = (int) data_get($landAre, 'dedicated');
        $agreement = (int) data_get($landAre, 'agreement');

        return compact('dedicated', 'agreement');
    }

    /**
     * 取得更新資料
     *
     * @param string $spaceId
     * @param array $landArea
     *
     * @return array
     */
    private function fetchUpdateData(string $spaceId, array $landArea): array
    {
        return (new LandAreaData(compact('spaceId') + $landArea))->toColumnArray();
    }

    /**
     * 更新土地面積
     *
     * @param \App\Services\PropertyRights\Component\UpdateInstance $updateInstance
     * @param array $updateData
     *
     * @return void
     */
    private function update(UpdateInstance $updateInstance, array $updateData): void
    {
        $updateInstance->spaceData
            ->landArea()
            ->updateOrCreate(
                Arr::only($updateData, 'space_id'),
                $updateData
            );
    }
}
