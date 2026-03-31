<?php

declare(strict_types=1);

namespace App\Services\PropertyManage\CarParking;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;

use App\Support\Enum\FetchMessage;

use App\Models\Property;

use App\Repositories\Space\CrmBuildingSpaceRepository;
use App\Repositories\Space\CrmParkingSpaceRepository;
use App\Repositories\PropertyManage\PropertyRepository;

final class PreviewShowService extends Service
{
    use \App\Support\Trait\PropertyManage\PreviewResponseTrait;

    /**
     * @param  CrmBuildingSpaceRepository  $crmBuildingSpaceRepository
     * @param  CrmParkingSpaceRepository   $crmParkingSpaceRepository
     * @param  PropertyRepository          $propertyRepository
     */
    public function __construct(
        private readonly CrmBuildingSpaceRepository $crmBuildingSpaceRepository,
        private readonly CrmParkingSpaceRepository  $crmParkingSpaceRepository,
        private readonly PropertyRepository         $propertyRepository,
    ) {}

    /**
     * 取得單筆物件資料
     *
     * @param  int  $id
     *
     * @return array
     */
    public function execute(int $id): array
    {
        $result = $this->propertyRepository->findCarParking(
            crm('company_id'),
            crm('community_id'),
            $id,
        );

        if(is_null($result)) {
            $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
        }

        return  self::fetchResponse($result);
    }

    /**
     * 取得回傳資料
     *
     * @param \App\Models\Property $propertyData 物件資料
     *
     * @return array
     */
    private function fetchResponse(Property $propertyData): array
    {
        return [
            ...[
            'contactInfo'   => $this->getPropertyContactInfo($propertyData),
            'contactPerson' => $this->getPropertyContactPerson($propertyData),
            ],
            ...self::fetchBuildingSpace($propertyData)];
    }

    /**
     * @param  Property  $propertyData
     *
     * @return array
     */
    private function fetchBuildingSpace(Property $propertyData): array
    {
        $parkingSpace = $this->crmParkingSpaceRepository
            ->findByCarSpace($propertyData->crm_parking_space_id)
            ->first();

        if (!$parkingSpace) {
            return [
                'district_name'     => '',
                'building_name'     => '',
                'staircase_name'    => '',
                'floor_name'        => '',
                'car_name'          => '',
                'parking_attribute' => '',
                'parking_number'    => '',
                'exclusive'         => '',
                'area'              => '',
            ];
        }

        $buildingSpace = $parkingSpace->CrmBuildingSpaceForCar;
        $commonInfo = $buildingSpace?->crmBuildingCommonInfo;

        $denominator = $parkingSpace->default_extent_of_ownership_denominator ?? 1;

        $exclusive = ($denominator === 0)
            ? 0 : ($commonInfo
                ? $commonInfo->pre_sale_total_area * ($parkingSpace->default_extent_of_ownership_numerator ?? 1) / $denominator
                : 0);

        return [
            'district_name'     => $buildingSpace->district_name ?? '',
            'building_name'     => $buildingSpace->building_name ?? '',
            'staircase_name'    => $buildingSpace->staircase_name ?? '',
            'floor_name'        => $buildingSpace->floor_name ?? '',
            'car_name'          => $buildingSpace->household_name ?? '',
            'parking_attribute' => $parkingSpace->parking_type ?? '',
            'parking_number'    => $parkingSpace->parking_number ?? '',
            'exclusive'         => $exclusive ?? '',
            'area'              => $commonInfo->doorplate ?? '',
        ];
    }
}