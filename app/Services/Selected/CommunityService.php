<?php

declare(strict_types=1);

namespace App\Services\Selected;

use App\Support\Abstract\Service;

final class CommunityService extends Service
{
    /**
     * 取得社區資料
     *
     * @return array
     */
    public function execute(): array
    {
        $canAccessCompany = crm()->canAccessCompany();
        $community = $this->fetchCommunityData();

        return compact('canAccessCompany', 'community');
    }

    /**
     * 取得建案 (社區) 資料
     *
     * @return array
     */
    private function fetchCommunityData(): array
    {
        return array_map(function (array $value): array {
            return [
                'id' => (int) data_get($value, 'community_id'),
                'name' => (string) data_get($value, 'comname')
            ];
        }, crm()->getCommunity());
    }
}
