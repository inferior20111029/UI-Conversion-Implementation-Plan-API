<?php

declare(strict_types=1);

namespace App\Support\Trait\VisitReserve;

use App\Models\VisitReserve;

use App\Support\Enum\VisitReserveMessage;

trait VisitReserveTrait
{
    /**
     * 檢查是否可以執行 簽到或取消
     * @param \App\Models\VisitReserve $visitReserveData
     * @throws \App\Exceptions\ApiException
     * @return void
     */
    public function checkCanExecute(VisitReserve $visitReserveData): void
    {
        if (false === is_null($visitReserveData->arrival_time)) {
            $this->fails(VisitReserveMessage::ALREADY_CHECK_IN->value);
        }

        if (0 !== $visitReserveData->cancel_by) {
            $this->fails(VisitReserveMessage::ALREADY_CANCEL->value);
        }
    }
}
