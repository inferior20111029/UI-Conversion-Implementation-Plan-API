<?php

declare(strict_types=1);

namespace App\Services\Selected;

use Illuminate\Support\Collection;

use Symfony\Component\HttpFoundation\Response;

use App\Models\CrmBuildingSpace;

use App\Support\Abstract\Service;
use App\Support\Enum\FetchMessage;

use App\Repositories\Space\CrmBuildingSpaceRepository;

final class SpacePrivateService extends Service
{
    /**
     * 取得戶別資料
     *
     * @return \Illuminate\Support\Collection
     */
    public function execute(): Collection
    {
        $spaceData = $this->fetchData();
        return $this->fetchResponse($spaceData);
    }

    /**
     * 取得戶別資料
     *
     * @return \Illuminate\Support\Collection
     */
    public function fetchData(): Collection
    {
        $spaceData = (new CrmBuildingSpaceRepository())->findByAll();

        if ($spaceData->isNotEmpty()) {
            return $spaceData;
        }

        $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
    }

    /**
     * 取得回傳資料
     *
     * @param \Illuminate\Support\Collection $spaceData
     *
     * @return \Illuminate\Support\Collection
     */
    private function fetchResponse(Collection $spaceData): Collection
    {
        return $spaceData
            ->map(function (CrmBuildingSpace $space): array {
                return [
                    'spaceId' => (string) $space->space_id,
                    'householdName' => (string) $space->household_name,
                    'districtName' => (string) $space->district_name,
                    'buildingName' => (string)  $space->building_name,
                    'floorName' => (string) $space->floor_name
                ];
            });
    }
}
