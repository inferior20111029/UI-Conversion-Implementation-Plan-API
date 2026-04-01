<?php

declare(strict_types=1);

namespace App\Services\PropertyManage\Space;

use App\Support\Abstract\Service;

use App\Models\Property;

use App\Services\PropertyManage\Component\UpdateInstance;

final class UpdateService extends Service
{
    /**
     * 修改物件資料
     *
     * @param  Property  $property
     * @param $request
     *
     * @return void
     */
    public function execute(Property $property, $request): void
    {
        $updateInstance = new UpdateInstance($property, $request);
        $updateInstance->property();
        $updateInstance->carpark();
        $updateInstance->fees();
        $updateInstance->decoration();
        $updateInstance->itemsIncluded();
        $updateInstance->neighborhoodLivability();
        $updateInstance->neighborhoodTransportation();
        $updateInstance->propertyContactPerson();
        $updateInstance->propertyContactInfo();
        $updateInstance->checkInInfo();
        $updateInstance->document();
        $updateInstance->equipment();
    }
}
