<?php

declare(strict_types=1);

namespace App\Services\PropertyManage\Component;

use App\Models\RentItemsIncluded;

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
        $itemsIncludedRequest = $updateInstance->request->post('items_included');

        if (empty($itemsIncludedRequest)) {
            $updateInstance->propertyData->rentItemsIncluded()->forceDelete();
            return;
        }

        $updateInstance->propertyData->rentItemsIncluded()->forceDelete();

        $itemsIncluded = array_map(function (int $value): RentItemsIncluded {
            return new RentItemsIncluded(
                $this->fetchItemsIncludedColumnData($value)->noHaveMacro()
                    ->toColumnArray()
            );
        }, $itemsIncludedRequest);

        $updateInstance->propertyData->rentItemsIncluded()->saveMany($itemsIncluded);
    }
}