<?php

declare(strict_types=1);

namespace App\Services\PropertyRights\Component;

use Illuminate\Support\Collection;

use App\Support\Data\AgreedDedicatedAreaData;

final class UpdateAgreedDedicatedArea
{
    /**
     * 約定專用面積-項目
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $spaceId = $updateInstance->fetchSpaceId();
        $areaItem = $updateInstance->spaceData->agreedDedicatedArea;
        $areaData = (array) $updateInstance->request->post('agreedDedicatedArea');
        [$create, $update, $delete] = $this->fetchHandleData($spaceId, $areaItem, $areaData);

        $updateInstance->spaceData->agreedDedicatedArea()->upsert([...$create, ...$update], ['id']);
        $updateInstance->spaceData->agreedDedicatedArea()->whereIn('id', $delete)->forceDelete();
    }

    /**
     * 取得處理資料
     *
     * @param string $spaceId 空間 ID
     * @param \Illuminate\Support\Collection $areaItem 空間目前的約定專用面積-項目資料
     * @param array $areaData 面積資料
     *
     * @return array
     */
    private function fetchHandleData(string $spaceId, Collection $areaItem, array $areaData): array
    {
        $create = [];
        $update = [];

        foreach ($areaData as $value) {
            $name = (string) data_get($value, 'name');
            $ping = (int) data_get($value, 'ping');

            $target = $areaItem->where('name', $name);
            $id = $target->value('id');

            $column = (new AgreedDedicatedAreaData(['space_id' => $spaceId] + compact('name', 'ping')))
                ->replace(compact('id'))
                ->toColumnArray();

            if ($target->isNotEmpty()) {
                $update[] = $column;
                $areaItem->forget($target->keys()->first());

                continue;
            }

            $create[] = $column;
        }

        return [$create, $update, $areaItem->pluck('id')->all()];
    }
}
