<?php

declare(strict_types=1);

namespace App\Repositories\RealEstateAgent;

use Illuminate\Support\Arr;

use App\Models\RealEstateAgentEntrust;

class EntrustRepository
{
    /**
     * 更新或建立委託資料
     *
     * @param array $updateData
     * @return RealEstateAgentEntrust|null
     */
    public function updateOrCreate(array $updateData): ?RealEstateAgentEntrust
    {
        return RealEstateAgentEntrust::updateOrCreate(
            Arr::only($updateData, ['real_estate_agent_id', 'company_id', 'community_id', 'space_id']),
            $updateData
        );
    }
}
