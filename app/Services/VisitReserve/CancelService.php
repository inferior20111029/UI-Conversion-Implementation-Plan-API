<?php

declare(strict_types=1);

namespace App\Services\VisitReserve;

use App\Support\Abstract\Service;

use App\Models\VisitReserve;

use App\Repositories\VisitReserve\VisitReserveRepository;

final class CancelService extends Service
{
    use \App\Support\Trait\VisitReserve\VisitReserveTrait;

    /**
     * @param VisitReserveRepository $visitReserveRepository
     */
    public function __construct(
        private readonly VisitReserveRepository $visitReserveRepository
    ) {}

    /**
     * 進行取消
     * @param \App\Models\VisitReserve $visitReserveData
     * @return void
     */
    public function execute(VisitReserve $visitReserveData): void
    {
        $updateData = $this->fetchUpdateData();
        $visitReserveData->update($updateData);
    }

    /**
     * 取得更新資料
     * @return array
     */
    private function fetchUpdateData(): array
    {
        return [
            'cancel_by' => crm('user_id')
        ];
    }
}
