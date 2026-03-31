<?php

declare(strict_types=1);

namespace App\Services\PropertyManage\Space;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;

use App\Support\Enum\FetchMessage;

use App\Models\Property;

use App\Repositories\Space\CrmBuildingSpaceRepository;
use App\Repositories\PropertyManage\PropertyRepository;

final class PreviewShowService extends Service
{
    use \App\Support\Trait\PropertyManage\PreviewResponseTrait;

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
            'equipment'     => $this->getAttachedEquipments($propertyData),
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
        return $this->crmBuildingSpaceRepository->findPatternArea(
            crm('company_id'),
            crm('community_id'),
            $propertyData->space_id
        )->map(function ($item) {
            $exclusiveArea = $item->exclusiveArea
                ->map(function ($area): array {
                    return $area->only('name', 'ping') + [
                            'allowCalculate' => (int) $area->allow_calculate,
                        ];
                })->sum('ping');

            $layoutSetting = $item?->spaceLayout?->map(function ($detail) {
                return [
                    'type'     => $detail->type,
                    'quantity' => (string) $detail->quantity,
                ];
            })->all();

            if(empty($layoutSetting)) {
                $layoutSetting = $item?->layoutSetting?->crmLayoutSettingDetail?->map(function ($detail) {
                    return [
                        'type'     => $detail->type,
                        'quantity' => (string) $detail->quantity,
                    ];
                })->all();
            }

            return [
                'space_id'  => $item->space_id ?? '',
                'district_name'  => $item->district_name ?? '',
                'building_name'  => $item->building_name ?? '',
                'staircase_name' => $item->staircase_name ?? '',
                'floor_name'     => $item->floor_name ?? '',
                'household_name' => $item->household_name ?? '',
                'area'           => $item->doorplate,
                'exclusive'      => $exclusiveArea,
                'layoutSetting'  => $layoutSetting ?? [],
            ];
        })->first();
    }
}