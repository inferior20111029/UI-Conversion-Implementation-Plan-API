<?php

declare(strict_types=1);

namespace App\Services\RealEstateAgent\Password;

use Illuminate\Support\Facades\Hash;

use App\Support\Abstract\Service;
use App\Support\Enum\PasswordMessage;

use App\Http\Requests\RealEstateAgent\ChangePasswordRequest;

use App\Models\RealEstateAgent;

use App\Repositories\RealEstateAgent\RealEstateAgentRepository;

final class ChangeService extends Service
{
    use \App\Support\Trait\RealEstateAgent\TokenTrait;
    use \App\Support\Trait\RealEstateAgent\ColumnTrait;

    /**
     * @param RealEstateAgentRepository $realEstateAgentRepository
     */
    public function __construct(
        private readonly RealEstateAgentRepository $realEstateAgentRepository
    ) {}

    /**
     * 修改房仲密碼
     *
     * @param \App\Models\RealEstateAgent $realEstateAgent 房仲資料
     * @param \App\Http\Requests\RealEstateAgent\ChangePasswordRequest $request Request
     *
     * @return void
     */
    public function execute(RealEstateAgent $realEstateAgent, ChangePasswordRequest $request): void
    {
        $this->checkOriginalPassword($realEstateAgent, $request);
        $this->checkNewPassword($realEstateAgent, $request);

        $updateData = $this->fetchPasswordColumn($request);

        $this->update($realEstateAgent, $updateData);
        $this->recordTokenUsed($realEstateAgent);
    }

    /**
     * 檢查原密碼是否正確
     *
     * @param \App\Models\RealEstateAgent $realEstateAgent
     * @param \App\Http\Requests\RealEstateAgent\ChangePasswordRequest $request
     * @throws \App\Exceptions\ApiException
     *
     * @return void
     */
    private function checkOriginalPassword(RealEstateAgent $realEstateAgent, ChangePasswordRequest $request): void
    {
        $originalPassword = (string) $request->post('original_password');
        $check = Hash::check($originalPassword, (string) $realEstateAgent->login?->password);

        if (false === $check) {
            $this->fails(PasswordMessage::MATCH_FAILS->value);
        }
    }

    /**
     * 檢查新密碼是否正確
     *
     * @param \App\Models\RealEstateAgent $realEstateAgent
     * @param \App\Http\Requests\RealEstateAgent\ChangePasswordRequest $request
     * @throws \App\Exceptions\ApiException
     *
     * @return void
     */
    private function checkNewPassword(RealEstateAgent $realEstateAgent, ChangePasswordRequest $request): void
    {
        $password = (string) $request->post('password');
        $check = Hash::check($password, (string) $realEstateAgent->login?->password);

        if (true === $check) {
            $this->fails(PasswordMessage::CAN_NOT_MATCH_ORIGINAL->value);
        }
    }

    /**
     * 更新房仲資料
     *
     * @param \App\Models\RealEstateAgent $realEstateAgent 房仲資料
     * @param array $updateData
     *
     * @return void
     */
    private function update(RealEstateAgent $realEstateAgent, array $updateData): void
    {
        $realEstateAgent->login()->update($updateData);
    }
}
