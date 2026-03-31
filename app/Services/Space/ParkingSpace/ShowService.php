<?php

declare(strict_types=1);

namespace App\Services\Space\ParkingSpace;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmParkingSpaceRepository;

final class ShowService extends Service
{

    public function __construct(
        private readonly CrmParkingSpaceRepository $crmParkingSpaceRepository,
    ) {
    }

    /**
     * 回傳戶別下的車位資料
     *
     * @param  string  $spaceId
     *
     * @return array
     */
    public function execute(string $spaceId)
    {
        $carTypes = ['機車', '汽車', '電動車'];
        $applicationTypes = ['法定車位', '增設車位', '獎勵車位', '殘障車位', '訪客貴賓專用車位'];

        return $this->crmParkingSpaceRepository->findBySpace($spaceId)
            ->map(function ($crmParkingSpace) use ($carTypes, $applicationTypes) {
                $commonSpace = $crmParkingSpace->CrmBuildingSpaceForCar;

                return [
                    'id'                => $crmParkingSpace->id,
                    'district_name'     => $commonSpace->district_name,
                    'building_name'     => $commonSpace->building_name,
                    'staircase_name'    => $commonSpace->staircase_name,
                    'floor_name'        => $commonSpace->floor_name,
                    'household_name'    => $commonSpace->household_name,
                    'parking_type'      => $crmParkingSpace->parking_type,
                    'parking_number'    => $crmParkingSpace->parking_number,
                    'car_type'          => $carTypes[$crmParkingSpace->car_type] ?? '',
                    'parking_size'      => $crmParkingSpace->parking_size,
                    'parking_attribute' => $crmParkingSpace->parking_attribute,
                    'application'       => $applicationTypes[$crmParkingSpace->application] ?? '',
                ];
            });
    }
}