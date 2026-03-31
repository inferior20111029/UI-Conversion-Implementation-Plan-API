<?php

declare(strict_types=1);

namespace App\Services\PropertyRights\Component;

use App\Repositories\Space\CrmParkingSpaceRepository;

final class UpdateCarParking
{
    /**
     * 更新戶別車位
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $houseCarParking = $updateInstance->spaceData->houseCarParking;
        $parkingSpace = (array) $updateInstance->request->post('parkingSpace');

        if ($houseCarParking->isNotEmpty() && empty($parkingSpace)) {
            $updateInstance->spaceData->houseCarParking()->update(['space_id' => '']);
            return;
        }

        if (empty($parkingSpace)) {
            return;
        }

        $ids = collect($parkingSpace)
            ->filter(function (string $parkingSpaceId) use (&$houseCarParking): bool {
                $target = $houseCarParking->where('id', $parkingSpaceId);

                if ($target->isNotEmpty()) {
                    $houseCarParking->forget($target->keys()->first());

                    return false;
                }

                return true;
            })
            ->values()
            ->all();

        (new CrmParkingSpaceRepository())
            ->updateHouseCarParking(
                crm('company_id'),
                crm('community_id'),
                $ids,
                ['space_id' => $updateInstance->fetchSpaceId()]
            );

        $updateInstance->spaceData
            ->houseCarParking()
            ->whereIn('id', $houseCarParking->pluck('id')->all())
            ->update(['space_id' => '']);
    }
}
