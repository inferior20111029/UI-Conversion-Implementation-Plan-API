<?php

declare(strict_types=1);

namespace App\Services\PropertyRights\Component;

final class UpdatePrice
{
    use \App\Support\Trait\PropertyRights\ColumnTrait;

    /**
     * 更新戶別價格
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $column = $this->fetchPriceColumnData($updateInstance->request);
        $updateData = $column->toColumnArray();

        $updateInstance->spaceData->price()->updateOrCreate(
            $column->onlyColumn('space_id')->toColumnArray(),
            $updateData
        );
    }
}
