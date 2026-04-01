<?php

declare(strict_types=1);

namespace App\Services\Selected;

use Illuminate\Support\Collection;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;
use App\Support\Enum\FetchMessage;

use App\Models\RealEstateAgent;

use App\Repositories\RealEstateAgent\RealEstateAgentRepository;

final class EntrustRealEstateAgentService extends Service
{
    /**
     * 取得房屋仲介
     *
     * @return array
     */
    public function execute(string $spaceId): Collection
    {
        $realEstateAgentData = $this->fetchData($spaceId);
        return $this->fetchResponse($realEstateAgentData);
    }

    /**
     * 取得房仲資料
     * @param string $spaceId
     * @throws \App\Exceptions\ApiException
     * @return \Illuminate\Support\Collection
     */
    public function fetchData(string $spaceId): Collection
    {
        $realEstateAgentData = (new RealEstateAgentRepository())
            ->findEntrust(crm('company_id'), crm('community_id'), $spaceId)
            ->filter(function (RealEstateAgent $realEstateAgent): bool {
                return $realEstateAgent->entrust
                    ->where('start_time', '<=', now())
                    ->where('end_time', '>', now())
                    ->isNotEmpty();
            });

        if ($realEstateAgentData->isNotEmpty()) {
            return $realEstateAgentData;
        }

        $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
    }

    /**
     * 取得回傳資料
     * @param \Illuminate\Support\Collection $realEstateAgentData
     * @return \Illuminate\Support\Collection
     */
    private function fetchResponse(Collection $realEstateAgentData): Collection
    {
        return $realEstateAgentData
            ->map(fn(RealEstateAgent $realEstateAgent): array => $realEstateAgent->only('uuid', 'name'));
    }
}
