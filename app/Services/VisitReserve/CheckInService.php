<?php

declare(strict_types=1);

namespace App\Services\VisitReserve;

use App\Support\Abstract\Service;
use App\Support\Tool\File\FileMagic;

use App\Http\Requests\VisitReserve\CheckInRequest;

use App\Models\VisitReserve;

use App\Repositories\VisitReserve\VisitReserveRepository;

final class CheckInService extends Service
{
    use \App\Support\Trait\VisitReserve\VisitReserveTrait;

    /**
     * @param VisitReserveRepository $visitReserveRepository
     */
    public function __construct(
        private readonly VisitReserveRepository $visitReserveRepository
    ) {}

    /**
     * 進行簽到
     * @param \App\Models\VisitReserve $visitReserveData
     * @param \App\Http\Requests\VisitReserve\CheckInRequest $request
     * @return void
     */
    public function execute(VisitReserve $visitReserveData, CheckInRequest $request): void
    {
        $updateData = $this->fetchUpdateData($request);
        $visitReserveData->update($updateData);
    }

    /**
     * 取得更新資料
     * @param \App\Http\Requests\VisitReserve\CheckInRequest $request
     * @return array
     */
    private function fetchUpdateData(CheckInRequest $request): array
    {
        $signature = (string) $request->post('signature');

        return [
            'arrival_time' => now(),
            'signature' => (int) FileMagic::parse($signature)->disk('s3')->save()?->id
        ];
    }
}
