<?php

declare(strict_types=1);

namespace App\Services\PropertyRights\Component;

use Illuminate\Support\Collection;

final class UpdateLayout
{
    use \App\Support\Trait\PropertyRights\ColumnTrait;

    /**
     * 更新格局設定
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $spaceLayout = $updateInstance->spaceData->spaceLayout;
        $layoutRequest = (array) $updateInstance->request->post('layout');

        if ($spaceLayout->isNotEmpty() && empty($layoutRequest)) {
            $updateInstance->spaceData->spaceLayout()->forceDelete();
            return;
        }

        if (empty($layoutRequest)) {
            return;
        }

        $spaceId = $updateInstance->fetchSpaceId();
        [$create, $update, $delete] = $this->fetchHandleData($spaceId, $spaceLayout, $layoutRequest);

        $updateInstance->spaceData->spaceLayout()->upsert([...$create, ...$update], ['id']);
        $updateInstance->spaceData->spaceLayout()->whereIn('id', $delete)->forceDelete();
    }

    /**
     * 取得處理資料
     *
     * @param string $spaceId 空間 ID
     * @param \Illuminate\Support\Collection $areaItem 當前擁有的面積資料
     * @param array $areaData 面積資料
     *
     * @return array
     */
    private function fetchHandleData(string $spaceId, Collection $spaceLayout, array $layoutRequest): array
    {
        $create = [];
        $update = [];

        foreach ($layoutRequest as $type => $quantity) {
            $target = $spaceLayout->where('type', $type);
            $id = $target->value('id');

            $column = $this->fetchLayoutColumnData($spaceId, $type, (int) $quantity)
                ->replace(compact('id'))
                ->toColumnArray();

            if ($target->isNotEmpty()) {
                $update[] = $column;
                $spaceLayout->forget($target->keys()->first());

                continue;
            }

            $create[] = $column;
        }

        return [$create, $update, $spaceLayout->pluck('id')->all()];
    }
}
