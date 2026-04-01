<?php

declare(strict_types=1);

namespace App\Services\RealEstateAgent\Verify;

use Illuminate\Support\Facades\Mail;

use App\Support\Abstract\Service;
use App\Support\Enum\VerifyState;
use App\Support\EzPlus\RealEstateAgentVerifyNotify;

use App\Models\RealEstateAgent;

use App\Repositories\RealEstateAgent\RealEstateAgentRepository;

use App\Mail\RealEstateAgentIdentificationCode;

final class VerifyService extends Service
{
    use \App\Support\Trait\RealEstateAgent\TokenTrait;
    use \App\Support\Trait\RealEstateAgent\VerifyTrait;

    /**
     * Undocumented function
     *
     * @param RealEstateAgentRepository $realEstateAgentRepository
     */
    public function __construct(
        private readonly RealEstateAgentRepository $realEstateAgentRepository
    ) {}

    /**
     * 驗證房仲帳號
     * @param \App\Models\RealEstateAgent $realEstateAgent 房仲資料
     * @return void
     */
    public function execute(RealEstateAgent $realEstateAgent): void
    {
        $this->canVerify($realEstateAgent);

        $this->update($realEstateAgent);
        $this->recordTokenUsed($realEstateAgent);

        Mail::to($realEstateAgent->email)->send(new RealEstateAgentIdentificationCode($realEstateAgent));
        RealEstateAgentVerifyNotify::execute($realEstateAgent->uuid);
    }

    /**
     * 更新房仲資料
     * @param \App\Models\RealEstateAgent $realEstateAgent 房仲資料
     * @return void
     */
    private function update(RealEstateAgent $realEstateAgent): void
    {
        $updateData = [
            'verify_state' => VerifyState::ALREADY->value
        ];

        $this->realEstateAgentRepository->update($realEstateAgent->id, $updateData);
    }
}
