<?php

declare(strict_types=1);

namespace App\Services\PropertyRights\Component;

use Illuminate\Support\Collection;

use App\Support\Data\ExclusiveAreaData;

final class UpdateExclusiveArea
{
    use \App\Support\Trait\PropertyRights\ColumnTrait;

    /**
     * 更新公設持分面積
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $customizeAreaRequest = (array) $updateInstance->request->post('customExclusiveArea');

        $area = $this->fetchDefaultAreaData($updateInstance);
        $areaData = [...$area, ...$customizeAreaRequest];

        $spaceId = $updateInstance->fetchSpaceId();
        $spaceArea = $updateInstance->spaceData->exclusiveArea;
        [$create, $update, $delete] = $this->fetchHandleData($spaceId, $spaceArea, $areaData);

        $updateInstance->spaceData->exclusiveArea()->upsert([...$create, ...$update], ['id']);
        $updateInstance->spaceData->exclusiveArea()->whereIn('id', $delete)->forceDelete();
    }

    /**
     * 取得預設面積資料
     *
     * @param \App\Services\PropertyRights\Component\UpdateInstance $updateInstance
     *
     * @return array
     */
    private function fetchDefaultAreaData(UpdateInstance $updateInstance): array
    {
        $areaRequest = (array) $updateInstance->request->post('exclusiveArea');

        return collect($areaRequest)
            ->map(function (array $value, string $name): array {
                $ping = (int) data_get($value, 'ping');
                $allowCalculate = (int) data_get($value, 'allowCalculate');

                return compact('name', 'ping', 'allowCalculate');
            })
            ->values()
            ->toArray();
    }

    /**
     * 取得處理資料
     *
     * @param string $spaceId 空間 ID
     * @param \Illuminate\Support\Collection $spaceArea 空間目前的面積資料
     * @param array $areaData 面積資料
     *
     * @return array
     */
    private function fetchHandleData(string $spaceId, Collection $spaceArea, array $areaData): array
    {
        $create = [];
        $update = [];

        foreach ($areaData as $value) {
            $name = (string) data_get($value, 'name');
            $ping = (int) data_get($value, 'ping');
            $allowCalculate = (int) data_get($value, 'allowCalculate');

            $target = $spaceArea->where('name', $name);
            $id = $target->value('id');

            $column = (new ExclusiveAreaData(['space_id' => $spaceId] + compact('name', 'ping', 'allowCalculate')))
                ->replace(compact('id'))
                ->toColumnArray();

            if ($target->isNotEmpty()) {
                $update[] = $column;
                $spaceArea->forget($target->keys()->first());

                continue;
            }

            $create[] = $column;
        }

        return [$create, $update, $spaceArea->pluck('id')->all()];
    }
}
