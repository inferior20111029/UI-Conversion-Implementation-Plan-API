<?php

declare(strict_types=1);

namespace App\Services\Space\ParkingSpaceConfiguration;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmParkingSpaceRepository;
use App\Repositories\Space\CrmBuildingSpaceRepository;
use App\Repositories\Space\CrmBuildingCommonSpaceRepository;
use App\Repositories\Space\CrmParkingSpaceSelectRepository;

final class ShowService extends Service
{
    use \App\Support\Trait\Space\CrmParkingSpaceTrait;

    public function __construct(
        private readonly CrmParkingSpaceRepository       $crmParkingSpaceRepository,
        private readonly CrmBuildingSpaceRepository      $crmBuildingSpaceRepository,
        private readonly CrmParkingSpaceSelectRepository $crmParkingSpaceSelectRepository,
        private readonly CrmBuildingCommonSpaceRepository $crmBuildingCommonSpaceRepository,
    ) {
    }

    /**
     * 回傳空間組態
     *
     * @return array
     */
    public function execute(): array
    {
        $filterKey  = request()->get('filter_key');

        $filteredData = array_filter($filterKey, fn ($value): bool => !is_null($value));

        $application = $filteredData['main_application'] ?? null;
        $filteredData['application'] = $this->findApplicationType($application);

        $crmParkingSpace = $this->crmParkingSpaceRepository
            ->parkingConfigurationPage($filteredData);

        $transformedList = $crmParkingSpace
            ->getCollection()
            ->transform([$this, 'transformParkingSpace']);

        return $this->paginateResponseFormat($crmParkingSpace, $transformedList);
    }

    public function create(): array
    {
        return self::fetchApplicationType() + self::option();
    }

    private function fetchApplicationType(): array
    {
        $buildingSpaces         = $this->crmBuildingSpaceRepository->findByAll();
        $crmBuildingCommonSpace = $this->crmBuildingCommonSpaceRepository->findByAll();

        if ($buildingSpaces->isEmpty()) {
            return [];
        }

        $carSpace = $crmBuildingCommonSpace
            ->map(function ($space) {
                return self::fetchCrmBuildingSpace($space);
            })->values()->toArray();

        $householdSpace = $buildingSpaces
            ->whereIn('main_application', ['H001', 'H002', 'H004', 'H005', 'H006', 'H007', 'H014'])
            ->map(function ($item) {
                return [
                    'space_id'       => $item->space_id,
                    'household_name' => $item->household_name,
                ];
            })->values()->toArray();

        return [
            'car_space'       => $carSpace,
            'household_space' => $householdSpace
        ];
    }
}
