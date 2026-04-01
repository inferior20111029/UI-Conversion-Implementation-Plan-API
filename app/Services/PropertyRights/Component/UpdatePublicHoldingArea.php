<?php

declare(strict_types=1);

namespace App\Services\PropertyRights\Component;

use Illuminate\Support\Collection;

use App\Support\Data\PublicHoldingAreaData;

final class UpdatePublicHoldingArea
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
        $spaceId = $updateInstance->fetchSpaceId();
        $areaItem = $updateInstance->spaceData->publicHoldingArea;
        $areaData = (array) $updateInstance->request->post('publicHoldingArea');

        [$create, $update, $delete] = $this->fetchHandleData($spaceId, $areaItem, $areaData);

        $updateInstance->spaceData->publicHoldingArea()->upsert([...$create, ...$update], ['id']);
        $updateInstance->spaceData->publicHoldingArea()->whereIn('id', $delete)->forceDelete();
    }

    /**
     * 取得處理資料
     * @param string $spaceId 空間 ID
     * @param \Illuminate\Support\Collection $areaItem 當前擁有的面積資料
     * @param array $areaData 面積資料
     *
     * @return array
     */
    private function fetchHandleData(string $spaceId, Collection $areaItem, array $areaData): array
    {
        $create = [];
        $update = [];

        foreach ($areaData as $value) {
            $constructionNumber = (string) data_get($value, 'constructionNumber');

            $target = $areaItem->where('construction_number', $constructionNumber);
            $id = $target->value('id');

            $column = (new PublicHoldingAreaData(compact('spaceId', 'constructionNumber') + array_map('intval', $value)))
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
