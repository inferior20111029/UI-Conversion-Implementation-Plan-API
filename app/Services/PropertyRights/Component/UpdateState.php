<?php

declare(strict_types=1);

namespace App\Services\PropertyRights\Component;

final class UpdateState
{
    use \App\Support\Trait\PropertyRights\ColumnTrait;

    /**
     * 更新房屋概況
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $stateRequest = (array) $updateInstance->request->post('state');

        $column = $this->fetchStateColumnData($stateRequest);
        $updateData = $column->excludeColumn('space_id')->toColumnArray();

        $updateInstance->spaceData
            ->houseState()
            ->updateOrCreate(
                ['space_id' => $updateInstance->spaceData->space_id],
                $updateData
            );
    }
}
