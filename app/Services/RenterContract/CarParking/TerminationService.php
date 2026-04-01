<?php

declare(strict_types=1);

namespace App\Services\RenterContract\CarParking;

use App\Support\Abstract\Service;

use App\Http\Requests\RenterContract\TerminationRequest;

use App\Models\RenterContract;

use App\Services\RenterContract\Component\Termination;

final class TerminationService extends Service
{
    /**
     * 終止合約
     *
     * @param \App\Models\RenterContract $contract 合約資料
     * @param \App\Http\Requests\RenterContract\TerminationRequest $request Request
     *
     * @return void
     */
    public function execute(RenterContract $contract, TerminationRequest $request): void
    {
        (new Termination($contract, $request))->execute();
    }
}
