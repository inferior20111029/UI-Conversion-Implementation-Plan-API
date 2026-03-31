<?php

declare(strict_types=1);

namespace App\Services\Selected;

use Illuminate\Support\Collection;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;
use App\Support\Enum\FetchMessage;

use App\Models\CrmParkingSpace;

use App\Repositories\Space\CrmParkingSpaceRepository;

final class DistributeCarParkingService extends Service
{
    /**
     * @param \App\Repositories\Space\CrmParkingSpaceRepository $crmParkingSpaceRepository
     */
    public function __construct(
        private readonly CrmParkingSpaceRepository $crmParkingSpaceRepository
    ) {}

    /**
     * 取得車位資料
     * @return \Illuminate\Support\Collection
     */
    public function execute(): Collection
    {
        $carParkingData = $this->fetchData();
        return $this->fetchResponse($carParkingData);
    }

    /**
     * 取得可分配的車位資料
     * @throws \App\Exceptions\ApiException
     * @return \Illuminate\Support\Collection
     */
    private function fetchData(): Collection
    {
        $carParkingData = $this->crmParkingSpaceRepository
            ->fetchCanDistributeSpaceOfParking(crm('company_id'), crm('community_id'));

        if ($carParkingData->isNotEmpty()) {
            return $carParkingData;
        }

        $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
    }

    /**
     * 取得車位資料
     * @param \Illuminate\Support\Collection $carParkingData
     * @return \Illuminate\Support\Collection
     */
    private function fetchResponse(Collection $carParkingData): Collection
    {
        return $carParkingData
            ->map(function (CrmParkingSpace $carParking): array {
                $space = $carParking->CrmBuildingSpaceForCar;

                return [
                    'id' => $carParking->id,
                    'districtName' => $space->district_name ?? '',
                    'buildingName' => $space->building_name ?? '',
                    'staircaseName' => $space->staircase_name ?? '',
                    'floorName' => $space->floor_name ?? '',
                    'householdName' => $space->household_name ?? '',
                    'number' => $carParking->parking_number,
                    'carType' => $carParking->car_type,
                    'parkingType' => $carParking->parking_type,
                    'application' => $carParking->application,
                    'size' => (int) $carParking->parking_size
                ];
            });
    }
}
