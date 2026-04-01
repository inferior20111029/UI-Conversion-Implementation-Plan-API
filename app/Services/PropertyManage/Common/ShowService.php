<?php

declare(strict_types=1);

namespace App\Services\PropertyManage\Common;

use App\Models\ExclusiveArea;
use App\Models\CrmBuildingSpace;
use App\Models\PublicHoldingArea;
use Illuminate\Support\Collection;
use App\Support\Abstract\Service;

use App\Repositories\Space\CrmBuildingSpaceRepository;
use App\Repositories\Space\CrmBuildingCommonSpaceRepository;
use App\Repositories\Equipment\CrmEquipmentRepository;
use App\Repositories\PropertyManage\PropertyRepository;
use App\Models\Property;

final class ShowService extends Service
{
    use \App\Support\Trait\PropertyManage\EditResponseTrait;

    /**
     * @param  CrmBuildingSpaceRepository         $crmBuildingSpaceRepository
     * @param  CrmBuildingCommonSpaceRepository   $crmBuildingCommonSpaceRepository
     * @param  CrmEquipmentRepository             $crmEquipmentRepository
     * @param  PropertyRepository                 $propertyRepository
     */
    public function __construct(
        private readonly CrmBuildingSpaceRepository       $crmBuildingSpaceRepository,
        private readonly CrmBuildingCommonSpaceRepository $crmBuildingCommonSpaceRepository,
        private readonly CrmEquipmentRepository           $crmEquipmentRepository,
        private readonly PropertyRepository               $propertyRepository,
    ) {}

    /**
     * 取得尚未出租空間資料
     *
     * @return Collection
     */
    public function execute(): Collection
    {
        $type = request()->get('type');

        if ($type === 'equipment') {
            return $this->getAndTransformEquipments();
        }

        return $this->getAndTransformProperties($type);
    }

    /**
     * 取得登記面積
     *
     * @return array
     */
    public function getRegisterArea(): array
    {
        $spaceId     = request()->get('space_id');
        $companyId   = crm('company_id');
        $communityId = crm('community_id');

        $area = $this->crmBuildingSpaceRepository
            ->findArea($companyId, $communityId)
            ->where('space_id', $spaceId)
            ->first();

        $registerArea = $area
            ? ($area['exclusive_area_sum_ping'] ?? 0) + ($area['public_holding_area_sum_total'] ?? 0)
            : 1;

        return ['register_area' => $registerArea];
    }

    public function getCarType(string $type)
    {
        return $this->getAndTransformCarProperties($type);
    }

    /**
     * 取得物件列表
     *
     * @return array
     */
    public function fetchData(): array
    {
        $filterKey = request()->get('filter_key', []);
        $type      = request()->get('type');

        $filteredData = array_filter($filterKey, fn($value) => !is_null($value));

        $isCarSpace = $type !== 'space';

        $propertyRecord = $isCarSpace
            ? $this->propertyRepository->findPropertyCarPaginate(crm('company_id'), crm('community_id'), $filteredData)
            : $this->propertyRepository->findPropertyPaginate(crm('company_id'), crm('community_id'), $filteredData);

        $transformedCollection = $propertyRecord->getCollection()->transform(
            fn($property) => $this->fetchColumnData($property, $isCarSpace)
        );

        return $this->paginateResponseFormat($propertyRecord, $transformedCollection);
    }

    /**
     * @param  Property  $property
     * @param  bool  $isCarSpace
     *
     * @return array
     */
    private function fetchColumnData(Property $property, bool $isCarSpace = false): array
    {
        $fees = $property->fees;
        $crmBuildingSpace = $isCarSpace
            ? $property->crmParkingSpaceId?->CrmBuildingSpaceForCar
            : $property->crmBuildingSpace;
        $crmParkingSpace = $isCarSpace
            ? $property->crmParkingSpaceId
            : null;

        $carData = [];
        if($isCarSpace) {
            $carData = [
                'parking_number' => $crmParkingSpace?->parking_number,
            ];
        }

        $data = [
            'id'               => $property->id,
            'uuid'             => $property->uuid,
            'space_id'         => $property?->space_id ?? $property?->crm_parking_space_id,
            'title'            => $property->title,
            'type'             => $property->type,
            'creator'          => $property->creator,
            'price'            => $fees->price,
            'management_fee'   => $fees->management_fee ?? null,
            'is_car'           => $property->attachedCarparks->whereNotNull('crm_parking_space_id')->count() > 0,
            'enable_state'     => $property->enable_state === 1,
            'district_name'    => $crmBuildingSpace?->district_name,
            'building_name'    => $crmBuildingSpace?->building_name,
            'staircase_name'   => $crmBuildingSpace?->staircase_name,
            'floor_name'       => $crmBuildingSpace?->floor_name,
            'household_name'   => $crmBuildingSpace?->household_name,
            'car_name'         => $crmBuildingSpace?->household_name,
        ] + $carData;

        if ($isCarSpace && $crmParkingSpace) {
            $data['parking_type'] = $crmParkingSpace->parking_type;
            $data['parking_attribute'] = $crmParkingSpace->parking_attribute;
            $data['car_type'] = $crmParkingSpace->car_type;
        }

        return $data;
    }
}