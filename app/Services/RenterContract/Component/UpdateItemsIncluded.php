<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Component;

use Illuminate\Support\Collection;

final class UpdateItemsIncluded
{
    use \App\Support\Trait\RenterContract\ColumnTrait;

    /**
     * 更新包含項目
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $itemsIncluded = $updateInstance->contractData->rentItemsIncluded;
        $itemsIncludedRequest = (array) $updateInstance->request->post('itemsIncluded');

        if ($itemsIncluded->isNotEmpty() && empty($itemsIncludedRequest)) {
            $updateInstance->contractData->rentItemsIncluded()->forceDelete();
            return;
        }

        if (empty($itemsIncludedRequest)) {
            return;
        }

        [$create, $update, $delete] = $this->fetchHandleData($updateInstance, $itemsIncluded, $itemsIncludedRequest);

        $updateInstance->contractData->rentItemsIncluded()->upsert([...$create, ...$update], ['id']);
        $updateInstance->contractData->rentItemsIncluded()->whereIn('id', $delete)->forceDelete();
    }

    /**
     * 取得處理資料
     * @param \App\Services\RenterContract\Component\UpdateInstance $updateInstance 更新實例
     * @param \Illuminate\Support\Collection $itemsIncluded 當前擁有的項目資料
     * @param array $itemsIncludedRequest 項目資料
     *
     * @return array
     */
    private function fetchHandleData(UpdateInstance $updateInstance, Collection $itemsIncluded, array $itemsIncludedRequest): array
    {
        $morph = $this->morphColumn($updateInstance->contractData);

        $create = [];
        $update = [];

        foreach ($itemsIncludedRequest as $options) {
            $target = $itemsIncluded->where('rent_items_options_id', (int) $options);
            $id = $target->value('id');

            $columnData = $this->fetchItemsIncludedColumnData((int) $options)
                ->replace(compact('id') + $morph)
                ->toColumnArray();

            if ($target->isNotEmpty()) {
                $update[] = $columnData;
                $itemsIncluded->forget($target->keys()->first());

                continue;
            }

            $create[] = $columnData;
        }

        return [$create, $update, $itemsIncluded->pluck('id')->all()];
    }
}
