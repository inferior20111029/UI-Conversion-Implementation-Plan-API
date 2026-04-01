<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Space;

use App\Support\Abstract\Service;

use App\Http\Requests\RenterContract\SpaceRequest;

use App\Models\RenterContract;

use App\Services\RenterContract\Component\UpdateInstance;

final class UpdateService extends Service
{
    /**
     * 修改合約資料
     *
     * @param \App\Models\RenterContract $contract 合約資料
     * @param \App\Http\Requests\RenterContract\SpaceRequest $request Request
     *
     * @return void
     */
    public function execute(RenterContract $contract, SpaceRequest $request): void
    {
        $updateInstance = new UpdateInstance($contract, $request);
        $updateInstance->contract();
        $updateInstance->itemsIncluded();
        $updateInstance->persons();
        $updateInstance->document();
        $updateInstance->paymentCycle();
        $updateInstance->notify();
        $updateInstance->decoration();
        $updateInstance->fees();
        $updateInstance->carpark();
        $updateInstance->equipment();
        $updateInstance->bank();
        $updateInstance->cache();
    }
}
