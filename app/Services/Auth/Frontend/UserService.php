<?php

declare(strict_types=1);

namespace App\Services\Auth\Frontend;

use App\Support\Abstract\Service;
use App\Support\Enum\LoginType;

use App\Models\Login;

final class UserService extends Service
{
    /**
     * 取得登入者資料
     * @return array
     */
    public function execute(): array
    {
        /** @var Login */
        $user = auth()->user();

        if ($user->loginRealEstateAgent()->exists()) {
            return $this->fetchRealEstateAgentUser($user);
        }

        return [];
    }

    /**
     * 取得仲介使用者資料
     * @param \App\Models\Login $user
     * @return array
     */
    private function fetchRealEstateAgentUser(Login $user): array
    {
        $realEstateAgent = $user->loginRealEstateAgent;

        return [
            'id' => $realEstateAgent->uuid,
            'account' => $user->account,
            'name' => (string) $realEstateAgent->name,
            'type' => LoginType::realEstateAgent->name
        ];
    }
}
