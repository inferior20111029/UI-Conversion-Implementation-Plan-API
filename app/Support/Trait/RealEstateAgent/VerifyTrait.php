<?php

declare(strict_types=1);

namespace App\Support\Trait\RealEstateAgent;

use Symfony\Component\HttpFoundation\Response;

use App\Models\RealEstateAgent;

use App\Support\Enum\VerifyState;
use App\Support\Enum\VerifyMessage;
use App\Support\Response\ApiMessage;

trait VerifyTrait
{
    /**
     * 檢查是否可以進行驗證
     *
     * @param \App\Models\RealEstateAgent $realEstateAgent 房仲資料
     *
     * @throws \App\Support\Response\ApiMessage
     *
     * @return void
     */
    public function canVerify(RealEstateAgent $realEstateAgent): void
    {
        if (VerifyState::NOT_YET->value === $realEstateAgent->verify_state) {
            return;
        }

        (new ApiMessage())->throwException(VerifyMessage::ALREADY_VERIFY->value, Response::HTTP_BAD_REQUEST);
    }
}
