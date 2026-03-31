<?php

declare(strict_types=1);

namespace App\Services\PropertyManage\Component;

final class UpdateCheckInInfo
{
    use \App\Support\Trait\PropertyManage\ColumnTrait;

    /**
     * 更新租售物件入住
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $checkInInfo = (array) $updateInstance->request->post('checkInInfo');

        $checkInDate             = data_get($checkInInfo, 'date');
        $minimumPeriod           = (int) data_get($checkInInfo, 'lease_term');
        $minimumRentalPeriodType = data_get($checkInInfo, 'lease_term_type');

        $updateInstance->propertyData->itemCheckIn()->update([
            'check_in_date'  => $checkInDate,
            'minimum_period' => $minimumPeriod,
            'minimum_rental_period_type' => $minimumRentalPeriodType,
        ]);
    }
}
