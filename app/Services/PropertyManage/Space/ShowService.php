<?php

declare(strict_types=1);

namespace App\Services\PropertyManage\Space;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;

use App\Support\Enum\FetchMessage;

use App\Models\Property;

use App\Repositories\Space\CrmBuildingSpaceRepository;
use App\Repositories\PropertyManage\PropertyRepository;

final class ShowService extends Service
{
    use \App\Support\Trait\PropertyManage\ResponseTrait;

    /**
     * @param CrmBuildingSpaceRepository $crmBuildingSpaceRepository
     */
    public function __construct(
        private readonly CrmBuildingSpaceRepository $crmBuildingSpaceRepository,
        private readonly PropertyRepository $propertyRepository,
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
        $result = $this->propertyRepository->findPropertyUUID(
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
     * 取得單筆物件資料
     *
     * @param string|null $uuid
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
    private function fetchResponse(Property $propertyData): array
    {
       $householdName = $this->crmBuildingSpaceRepository
           ->findByUuid($propertyData->space_id, crm('company_id'))
           ->household_name ?? '';

        return [
            'uuid'                  => $propertyData->uuid,
            'space_id'              => $propertyData->space_id,
            'title'                 => $propertyData->title,
            'description'           => $propertyData->description ?? '',
            'state'                 => $propertyData->enable_state,
            'house_age'             => $propertyData->house_age,
            'have_lease'            => (string) $propertyData->have_lease ?? '',
            'household_name'        => $householdName,
            'decoration'            => $this->getDecorationData($propertyData),
            'fees'                  => $this->getFeesData($propertyData),
            'carpark'               => $this->getAttachedCarparks($propertyData),
            'items_included'        => $propertyData->rentItemsIncluded->pluck('rent_items_options_id')->map(fn($id) => (string) $id)->values()->toArray(),
            'checkInInfo'           => $this->getItemCheckInData($propertyData),
            'livability'            => $propertyData->neighborhoodLivability->pluck('neighborhood_livability_id')->map(fn($id) => (string) $id)->values()->toArray(),
            'transportation'        => $this->getNeighborhoodTransportation($propertyData),
            'contactInfo'           => $this->getPropertyContactInfo($propertyData),
            'contactPerson'         => $this->getPropertyContactPerson($propertyData),
            'is_url'                => $propertyData?->document->where('type', 'url')->count() > 0 ? 0 : 1,
            'document'              => $this->getDocuments($propertyData),
            'equipment'             => $this->getAttachedEquipments($propertyData),
        ];
    }
}