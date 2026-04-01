<?php

declare(strict_types=1);

namespace App\Services\PropertyManage\Component;

use App\Models\AttachedCarpark;

final class UpdateCarpark
{
    use \App\Support\Trait\PropertyManage\ColumnTrait;

    /**
     * 更新附設車位
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $carparkData = $updateInstance->request->post('carpark');

        $updateInstance->propertyData->attachedCarparks()->forceDelete();

        $carpark = array_map(function (array $value): AttachedCarpark {
            return new AttachedCarpark(
                $this->fetchCarparkColumnData($value)->noHaveMacro()
                    ->toColumnArray()
            );
        }, $carparkData);

        $updateInstance->propertyData->attachedCarparks()->saveMany($carpark);
    }
}
