<?php

declare(strict_types=1);

namespace App\Services\PropertyManage\Component;

final class UpdatePropertyContactPerson
{
    use \App\Support\Trait\PropertyManage\ColumnTrait;

    /**
     * 更新附近交通資料
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $transportationRequest = (array) $updateInstance->request->post('contactPerson');

        $updateInstance->propertyData->propertyContactPerson()->update($transportationRequest);
    }
}
