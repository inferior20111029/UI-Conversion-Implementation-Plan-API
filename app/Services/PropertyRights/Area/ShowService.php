<?php

declare(strict_types=1);

namespace App\Services\PropertyRights\Area;

use Illuminate\Support\Collection;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;
use App\Support\Enum\FetchMessage;

use App\Repositories\Space\CrmBuildingSpaceRepository;

final class ShowService extends Service
{
    /**
     * @param CrmBuildingSpaceRepository $crmBuildingSpaceRepository
     */
    public function __construct(
        private readonly CrmBuildingSpaceRepository $crmBuildingSpaceRepository
    ) {}

    /**
     * 取得面積總覽資料
     *
     * @return array
     */
    public function execute(): array
    {
        $areaData = $this->fetchData();
        return $this->fetchResponse($areaData);
    }

    /**
     * 取得資料
     *
     * @return \Illuminate\Support\Collection
     */
    public function fetchData(): Collection
    {
        $result = $this->crmBuildingSpaceRepository->findArea(crm('company_id'), crm('community_id'));

        if ($result->isNotEmpty()) {
            return $result;
        }

        $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
    }

    /**
     * 取得回傳資料
     *
     * @param \Illuminate\Support\Collection $areaData 面積資料
     *
     * @return array
     */
    private function fetchResponse(Collection $areaData): array
    {
        $exclusive = $areaData->sum('exclusive_area_sum_ping');
        $publicHolding = $areaData->sum('public_holding_area_sum_total');
        $agreedDedicated = $areaData->sum('agreed_dedicated_area_sum_ping');

        $register = $exclusive + $publicHolding;

        if ($register === 0) {
            $exclusiveProportion     = 0;
            $publicHoldingProportion = 0;
        } else {
            $exclusiveProportion     = (int) round(($exclusive / $register) * 100);
            $publicHoldingProportion = (int) round(($publicHolding / $register) * 100);
        }

        return compact(
            'exclusive',
            'exclusiveProportion',
            'publicHolding',
            'publicHoldingProportion',
            'register',
            'agreedDedicated'
        );
    }
}
