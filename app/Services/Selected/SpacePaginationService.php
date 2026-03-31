<?php

declare(strict_types=1);

namespace App\Services\Selected;

use Illuminate\Support\Collection;


use App\Models\CrmBuildingSpace;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmBuildingSpaceRepository;

final class SpacePaginationService extends Service
{
    /**
     * 取得戶別資料
     *
     * @return \Illuminate\Support\Collection
     */
    public function execute(): array
    {
        $filterKey = request()->get('filter_key', []);
        $companyId = crm('company_id');
        $comid     = crm('community_id');

        $filteredData = array_filter($filterKey, fn ($value) => !is_null($value));

        $crmBuildingSpace = (new CrmBuildingSpaceRepository)
            ->spaceConfigurationPage($companyId, $comid, $filteredData, 1);

        return $this->paginateResponseFormat(
            $crmBuildingSpace,
            $this->fetchResponse($crmBuildingSpace->getCollection()),
        );
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
                    'spaceId'        => (string) $space->space_id,
                    'householdName'  => (string) $space->household_name,
                    'districtName'   => (string) $space->district_name,
                    'buildingName'   => (string) $space->building_name,
                    'floorName'      => (string) $space->floor_name,
                    'staircaseName'  => (string) $space->staircase_name
                ];
            });
    }
}