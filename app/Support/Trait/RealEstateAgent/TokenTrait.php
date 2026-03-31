<?php

declare(strict_types=1);

namespace App\Support\Trait\RealEstateAgent;

use App\Models\RealEstateAgent;

trait TokenTrait
{
    /**
     * 紀錄 Token 使用時間
     *
     * @param \App\Models\RealEstateAgent $realEstateAgent
     *
     * @return void
     */
    public function recordTokenUsed(RealEstateAgent $realEstateAgent): void
    {
        $token = $realEstateAgent->token->first();
        $token->last_used_at = now();
        $token->save();
    }
}
