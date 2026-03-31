<?php

declare(strict_types=1);

namespace App\Services\PropertyRights;

use App\Support\Abstract\Service;

use App\Services\PropertyRights\Component\UpdateInstance;

use App\Http\Requests\PropertyRights\StoreRequest;

use App\Models\CrmBuildingSpace;

final class UpdateService extends Service
{
    /**
     * 更新產權資料
     *
     * @param CrmBuildingSpace $spaceData 戶別資料
     * @param StoreRequest $request Request
     *
     * @return void
     */
    public function execute(CrmBuildingSpace $spaceData, StoreRequest $request): void
    {
        $updateInstance = new UpdateInstance($spaceData, $request);
        $updateInstance->space();
        $updateInstance->document();
        $updateInstance->price();
        $updateInstance->planning();
        $updateInstance->state();
        $updateInstance->areaSetting();
        $updateInstance->landArea();
        $updateInstance->exclusiveArea();
        $updateInstance->publicHoldingArea();
        $updateInstance->agreedDedicatedArea();
        $updateInstance->agreedDedicatedAreaSetting();
        $updateInstance->carParking();
        $updateInstance->earnestPayment();

        if (0 === $request->integer('crmLayoutSettingId')) {
            $updateInstance->layout();
        }
    }
}
