<?php

declare(strict_types=1);

namespace App\Services\PropertyManage\CarParking;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;

use App\Support\Enum\FetchMessage;

use App\Models\Property;

use App\Repositories\Space\CrmBuildingSpaceRepository;
use App\Repositories\Space\CrmParkingSpaceRepository;
use App\Repositories\Space\CrmBuildingCommonSpaceRepository;
use App\Repositories\PropertyManage\PropertyRepository;

final class ShowService extends Service
{
    use \App\Support\Trait\PropertyManage\ResponseTrait;

    /**
     * @param  CrmBuildingSpaceRepository  $crmBuildingSpaceRepository
     * @param  CrmBuildingCommonSpaceRepository  $crmBuildingCommonSpaceRepository
     * @param  PropertyRepository  $propertyRepository
     */
    public function __construct(
        private readonly CrmBuildingSpaceRepository       $crmBuildingSpaceRepository,
        private readonly CrmBuildingCommonSpaceRepository $crmBuildingCommonSpaceRepository,
        private readonly PropertyRepository               $propertyRepository,
        private readonly CrmParkingSpaceRepository        $crmParkingSpaceRepository,
    ) {
    }

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

        if (!is_null($result)) {
            return self::fetchResponse($result);
        }

        $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
    }

    /**
     * 取得單筆物件資料
     *
     * @param string|null $uuid 物件 UUID
     *
     * @throws \App\Exceptions\ApiException
     *
     * @return \App\Models\Property
     */
    public function fetchData(?string $uuid): Property
    {
        $result = $this->propertyRepository->findPropertyUUID(
            crm('company_id'),
            crm('community_id'),
            null,
            [
                'uuid' => $uuid,
            ]
        );

        if (!empty($result)) {
            return $result;
        }

        $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
    }

    /**
     * 取得回傳資料
     *
     * @param \App\Models\Property $propertyData 物件資料
     *
     * @return array
     */
    private function fetchResponse(?Property $propertyData): array
    {
        $carName = $this->crmBuildingCommonSpaceRepository
            ->findByUuid($propertyData?->crm_parking_space_id)
            ->household_name ?? null;

        $crmParkingSpace = $this->crmParkingSpaceRepository->findBySpaceUuid($propertyData?->crm_parking_space_id);

        return [
            'uuid'                  => $propertyData?->uuid,
            'space_id'              => $propertyData?->crm_parking_space_id,
            'parking_number'        => $crmParkingSpace?->parking_number,
            'household_name'        => $carName ?? '',
            'title'                 => $propertyData?->title,
            'description'           => $propertyData?->description ?? '',
            'state'                 => $propertyData?->enable_state,
            'fees'                  => $this?->getFeesData($propertyData),
            'checkInInfo'           => $this?->getItemCheckInData($propertyData),
            'document'              => $this?->getDocuments($propertyData),
            'contactInfo'           => $this->getPropertyContactInfo($propertyData),
            'contactPerson'         => $this->getPropertyContactPerson($propertyData),
        ];
    }
}
