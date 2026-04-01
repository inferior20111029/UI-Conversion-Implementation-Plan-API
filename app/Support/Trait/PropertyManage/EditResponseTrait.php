<?php

declare(strict_types=1);

namespace App\Support\Trait\PropertyManage;

use Illuminate\Support\Collection;
use App\Support\Enum\FetchMessage;

use Symfony\Component\HttpFoundation\Response;

trait EditResponseTrait
{
    /**
     * @return Collection
     */
    private function getAndTransformEquipments(): Collection
    {
        $equipments = $this->findEquipmentByType();

        if ($equipments->isNotEmpty()) {
            return $this->transformEquipments($equipments);
        }

        $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param  string  $type
     *
     * @return Collection
     */
    private function getAndTransformProperties(string $type): Collection
    {
        $properties = $this->findPropertiesByType($type);

        if ($properties->isNotEmpty()) {
            return $this->transformProperties($properties);
        }

        $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param  string  $type
     *
     * @return Collection
     */
    private function getAndTransformCarProperties(string $type): Collection
    {
        $companyId = crm('company_id');
        $communityId = crm('community_id');
        $carTypes    = ['機車', '汽車', '電動車'];

       $crmBuildingCommonSpace = $this->crmBuildingCommonSpaceRepository->findRentalSale($companyId, $communityId, $type);

       if($crmBuildingCommonSpace->isNotEmpty()) {
           return $crmBuildingCommonSpace->flatMap(function ($buildingSpace) use ($carTypes) {
               $commonDetails = [
                   'district_name'  => $buildingSpace->district_name,
                   'building_name'  => $buildingSpace->building_name,
                   'staircase_name' => $buildingSpace->staircase_name,
                   'floor_name'     => $buildingSpace->floor_name,
                   'household_name' => $buildingSpace->household_name,
               ];

               return $buildingSpace->carSpace->map(function ($carSpace) use ($commonDetails, $carTypes) {
                   return $commonDetails + [
                           'type'           => $carSpace->propertyCarState->rental_and_sale ?? '',
                           'car_type'       => $carTypes[$carSpace->car_type] ?? '未知',
                           'parking_number' => $carSpace->parking_number ?? '',
                           'space_id'       => $carSpace->id ?? '',
                       ];
               })->filter(function ($space) {
                   return !empty($space['type']);
               });
           });
       }

        $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param  string  $type
     *
     * @return Collection
     */
    private function findPropertiesByType(string $type): Collection
    {
        $companyId = crm('company_id');
        $communityId = crm('community_id');
        $spaceId     = request()->get('space_id');
        $rentalSale  = request()->get('rental_sale_type');

        return match ($type) {
            'space'   => $this->crmBuildingSpaceRepository->findProperty($companyId, $communityId, $rentalSale),
            'scooter' => $this->crmBuildingCommonSpaceRepository->findProperty($companyId, $communityId, $spaceId, [0]),
            'car'     => $this->crmBuildingCommonSpaceRepository->findProperty($companyId, $communityId, $spaceId, [1, 2]),
            default   => collect(),
        };
    }

    /**
     * @param  Collection  $properties
     *
     * @return Collection
     */
    private function transformProperties(Collection $properties): Collection
    {
        return $properties->map(function ($item) {
            return [
                'space_id'       => $item->carSpace->first()?->id ?? $item->space_id,
                'district_name'  => $item->district_name,
                'building_name'  => $item->building_name,
                'staircase_name' => $item->staircase_name,
                'floor_name'     => $item->floor_name,
                'household_name' => $item->household_name,
                'parking_number' => $item->carSpace->first()->parking_number ?? '',
            ];
        });
    }

    /**
     * @return Collection
     */
    private function findEquipmentByType(): Collection
    {
        $spaceId = request()->get('space_id');

        return $this->crmEquipmentRepository->findPropertyEquipment(
            crm('company_id'),
            crm('community_id'),
            $spaceId
        );
    }

    /**
     * @param  Collection  $equipments
     *
     * @return Collection
     */
    private function transformEquipments(Collection $equipments): Collection
    {
        return $equipments->map(function ($item) {
            $crmTypeName = $item->crmTypeName;
            $crmSystemName = $item->crmSystemName;

            return [
                'id'          => $item->id,
                'name'        => $item->name,
                'area'        => $item->area,
                'space'       => $item->space,
                'location'    => $item->location,
                'from'        => '元件配置',
                'status'      => $item->status ?? 1,
                'public_type' => $item->public_type,
                'type_name'   => $crmTypeName->name,
                'system_name' => $crmSystemName->name,
            ];
        });
    }
}
