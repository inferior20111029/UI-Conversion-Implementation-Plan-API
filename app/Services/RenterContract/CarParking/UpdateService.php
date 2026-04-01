<?php

declare(strict_types=1);

namespace App\Services\RenterContract\CarParking;

use App\Support\Abstract\Service;
use App\Http\Requests\RenterContract\CarParkingRequest;

use App\Models\RenterContract;

use App\Services\RenterContract\Component\UpdateInstance;

final class UpdateService extends Service
{
    /**
     * 修改合約資料
     *
     * @param \App\Models\RenterContract $contract 合約資料
     * @param \App\Http\Requests\RenterContract\CarParkingRequest $request Request
     *
     * @return void
     */
    public function execute(RenterContract $contract, CarParkingRequest $request): void
    {
        $updateInstance = new UpdateInstance($contract, $request);
        $updateInstance->contract();
        $updateInstance->persons();
        $updateInstance->document();
        $updateInstance->paymentCycle();
        $updateInstance->notify();
        $updateInstance->fees();
        $updateInstance->bank();
        $updateInstance->cache();
    }
}
