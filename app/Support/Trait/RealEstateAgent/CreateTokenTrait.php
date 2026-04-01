<?php

declare(strict_types=1);

namespace App\Support\Trait\RealEstateAgent;

use App\Support\Data\RealEstateAgentTokenData;

use App\Models\RealEstateAgent;
use App\Models\RealEstateAgentToken;

trait CreateTokenTrait
{
    /**
     * 建立 Token 資料
     *
     * @param \App\Models\RealEstateAgent $realEstateAgent
     * @param \App\Support\Data\RealEstateAgentTokenData $tokenColumnData
     *
     * @return void
     */
    public function createTokenData(RealEstateAgent $realEstateAgent, RealEstateAgentTokenData $tokenColumnData): void
    {
        $insertData = new RealEstateAgentToken($tokenColumnData->toColumnArray());
        $realEstateAgent->token()->save($insertData);
    }
}
