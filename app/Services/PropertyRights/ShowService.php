<?php

declare(strict_types=1);

namespace App\Services\PropertyRights;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;
use App\Support\Tool\File\FileMagic;

use App\Support\Enum\Selected;
use App\Support\Enum\FetchMessage;
use App\Support\Enum\CrmHouseType;
use App\Support\Enum\PropertyType;
use App\Support\Enum\HouseLiveState;

use App\Models\RealEstateAgentEntrust;
use App\Models\CrmParkingSpace;
use App\Models\CrmBuildingSpace;
use App\Models\PublicHoldingArea;
use App\Models\ExclusiveArea;
use App\Models\SpaceEarnestPayment;
use App\Models\CrmLayoutSetting;
use App\Models\CrmBuildingSpaceState;
use App\Models\CrmBuildingSpacePlanning;
use App\Models\CrmBuildingSpaceDocument;
use App\Models\BuildingSpaceCertification;
use App\Models\BuildingSpaceCertificationFile;

use App\Repositories\Space\CrmBuildingSpaceRepository;

final class ShowService extends Service
{
    use \App\Support\Trait\RenterContract\ResponseTrait;

    /**
     * @param CrmBuildingSpaceRepository $crmBuildingSpaceRepository
     */
    public function __construct(
        private readonly CrmBuildingSpaceRepository $crmBuildingSpaceRepository
    ) {}

    /**
     * 取得戶別資料
     *
     * @param string|null $spaceId 戶別 ID
     *
     * @return array
     */
    public function execute(?string $spaceId = null): array
    {
        [$spaceData, $totalRentalCount] = $this->fetchData($spaceId);
        $response = $this->fetchResponse($spaceData->getCollection());


        return [
            ...$this->paginateResponseFormat($spaceData, $response),
            'rental_count' => $totalRentalCount ?? 0,
        ];
    }

    /**
     * 取得資料
     *
     * @param string|null $spaceId 戶別 ID
     *
     * @return array
     */
    public function fetchData(?string $spaceId = null): array
    {
        [$paginatedSpaces, $totalRentalCount] = $this->crmBuildingSpaceRepository->findPrivate(crm('company_id'), crm('community_id'), $spaceId);

        if ($paginatedSpaces->isNotEmpty()) {
            return [$paginatedSpaces, $totalRentalCount];
        }

        $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
    }

    /**
     * 取得回傳資料
     *
     * @param \Illuminate\Support\Collection $spaceData 戶別資料
     *
     * @return \Illuminate\Support\Collection
     */
    private function fetchResponse(Collection $spaceData): Collection
    {
        $crmHouseType = CrmHouseType::array();

        return $spaceData
            ->map(function (CrmBuildingSpace $space) use ($crmHouseType): array {
                return [
                    'spaceId' => $space->space_id,
                    'name' => $space->household_name ?? '',
                    'locate' => $space->locate ?? '',
                    'districtName' => $space->district_name ?? '',
                    'buildingName' => $space->building_name ?? '',
                    'staircaseName' => $space->staircase_name ?? '',
                    'floorName' => $space->floor_name ?? '',
                    'blockId' => $space->block_id ?? '',
                    'doorplate' => $space->doorplate ?? '',
                    'extentOfOwnership' => $space->extent_of_ownership ?? '',
                    'landUseZoning' => $space->land_use_zoning ?? '',
                    'handoverDate' => $space->handover_date ?? '',
                    'buildingBuildLicenceId' => $space->building_build_licence_id ?? '',
                    'useLicenseId' => $space->use_license_id ?? '',
                    'layoutSetting' => $this->responseLayoutSetting($space->layoutSetting),
                    'spaceLayout' => $this->responseSpaceLayout($space->spaceLayout),
                    'haveCertification' => $space->certification->isNotEmpty(),
                    'certification' => $this->responseCertification($space->certification),
                    'mainApplication' => [
                        'code' => $space->main_application,
                        'name' => data_get($crmHouseType, $space->main_application) ?? ''
                    ],
                    'priceData' => [
                        'price' => (int) $space->price?->price,
                        'rentPrice' => (int) $space->price?->rent_price,
                        'depositPayer' => (string) $space->price?->deposit_payer,
                        'deposit' => (int) $space->price?->deposit
                    ],
                    'area' => $this->responseArea($space),
                    'houseState' => $this->responseHouseState($space->houseState),
                    'planning' => $this->responsePlanning($space->planning),
                    'carParking' => $this->responseCarParking($space->houseCarParking),
                    'document' => $this->responseDocument($space->document),
                    'realEstateAgent' => $this->responseRealEstateAgent($space->houseState, $space->realEstateAgentEntrust),
                    'earnestPayment' => $this->responseEarnestPayment($space->spaceEarnestPayment),
                    'alreadyPublish' => [
                        'rent' => $space->property->where('type', PropertyType::rent->name)->isNotEmpty(),
                        'sell' => $space->property->where('type', PropertyType::sell->name)->isNotEmpty()
                    ]
                ];
            });
    }

    /**
     * 取得回傳的格局資料
     *
     * @param \App\Models\CrmLayoutSetting|null $layoutSetting
     *
     * @return array
     */
    private function responseLayoutSetting(?CrmLayoutSetting $layoutSetting): array
    {
        $dataKey = ['id', 'name'];

        if (!empty($layoutSetting)) {
            $layoutSetting->name = (string) $layoutSetting->name;
            return $layoutSetting->only($dataKey);
        }

        return array_fill_keys($dataKey, '');
    }

    /**
     * 取得回傳的自訂格局資料
     *
     * @param \Illuminate\Support\Collection $layoutSetting
     *
     * @return array|string
     */
    private function responseSpaceLayout(Collection $spaceLayout): Collection
    {
        return $spaceLayout
            ->select('type', 'quantity')
            ->map(function (array $item): array {
                $type = (string) data_get($item, 'type');
                $item['name'] = constant("\App\Support\Enum\LayoutSetting::{$type}")->value;

                return $item;
            });
    }

    /**
     * 取得回傳的標章資料
     *
     * @param \Illuminate\Support\Collection $certificationData
     *
     * @return \Illuminate\Support\Collection
     */
    private function responseCertification(Collection $certificationData): Collection
    {
        return $certificationData
            ->map(function (BuildingSpaceCertification $certification): array {
                return $certification->only('name', 'version', 'type') + [
                    'applicationAt' => $certification->application_at->toDateTimeString(),
                    'url' => $certification->buildingSpaceCertificationFile
                        ->map(function (BuildingSpaceCertificationFile $certificationFile): string {
                            return FileMagic::find($certificationFile->file)->url();
                        })
                ];
            });
    }

    /**
     * 取得回傳的面積資料
     *
     * @param \App\Models\CrmBuildingSpace $space 戶別面積資料
     *
     * @return array
     */
    private function responseArea(CrmBuildingSpace $space): array
    {
        // 面積設定
        $setting = ['decimalPlace' => $space->areaSetting?->decimal_place] ?? '';

        // 土地面積
        $land = $space->landArea?->only('dedicated', 'agreement') ?? '';

        // 專有面積
        $exclusive = $space->exclusiveArea
            ->map(function (ExclusiveArea $item): array {
                return $item->only('name', 'ping') + [
                    'allowCalculate' => (int) $item->allow_calculate
                ];
            });

        // 公設持分面積設定
        $publicHolding = $space->publicHoldingArea
            ->map(function (PublicHoldingArea $item): array {
                return [
                    'constructionNumber' => (string)  $item->construction_number,
                    'ownershipDenominator' => (int) $item->ownership_denominator,
                    'ownershipMolecular' => (int) $item->ownership_molecular,
                ] + $item->only('total');
            });

        // 約定專用面積設定-項目
        $agreedDedicated = $space->agreedDedicatedArea->select('name', 'ping');

        // 約定專用面積設定
        $agreedDedicatedSetting = $space->agreedDedicatedAreaSetting?->only('preservation') ?? '';

        $totalExclusive = $exclusive->sum('ping');
        $totalPublicHolding = $publicHolding->sum('total');

        // 面積小計
        $total = [
            'exclusive' => $totalExclusive,
            'publicHolding' => $totalPublicHolding,
            'register' => $totalExclusive + $totalPublicHolding,
            'agreedDedicated' => $agreedDedicated->sum('ping'),
        ];

        return compact(
            'setting',
            'land',
            'exclusive',
            'publicHolding',
            'agreedDedicated',
            'agreedDedicatedSetting',
            'total'
        );
    }

    /**
     * 取得回傳房屋概況
     *
     * @param \App\Models\CrmBuildingSpaceState|null $houseState
     *
     * @return array
     */
    private function responseHouseState(?CrmBuildingSpaceState $houseState): array
    {
        $dataKey = ['live', 'rentalAndSale', 'saleStage', 'house', 'old'];

        if (!empty($houseState)) {
            $houseState->rentalAndSale = (string) $houseState?->rental_and_sale;
            $houseState->saleStage = (string) $houseState?->sale_stage;

            return $houseState->only($dataKey);
        }

        return array_fill_keys($dataKey, '');
    }

    /**
     * 取得回傳房屋規劃型態
     *
     * @param \Illuminate\Support\Collection $planningData
     *
     * @return \Illuminate\Support\Collection
     */
    private function responsePlanning(Collection $planningData): Collection
    {
        return $planningData->map(fn(CrmBuildingSpacePlanning $planning): array => $planning->only('type', 'planning'));
    }

    /**
     * 取得回傳停車位
     *
     * @param \Illuminate\Support\Collection $houseCarParking
     *
     * @return \Illuminate\Support\Collection
     */
    private function responseCarParking(Collection $houseCarParking): Collection
    {
        return $houseCarParking
            ->map(function (CrmParkingSpace $carParking): array {
                $space = $carParking->CrmBuildingSpaceForCar;

                return [
                    'id' => (string) $carParking?->id,
                    'districtName' => $space->district_name ?? '',
                    'buildingName' => $space->building_name ?? '',
                    'staircaseName' => $space->staircase_name ?? '',
                    'floorName' => $space->floor_name ?? '',
                    'householdName' => $space->household_name ?? '',
                    'number' => $carParking->parking_number,
                    'carType' => $carParking->car_type,
                    'parkingType' => $carParking->parking_type,
                    'application' => $carParking->application,
                    'size' => (string) $carParking?->parking_size
                ];
            });
    }

    /**
     * 取得回傳文件資料
     *
     * @param \Illuminate\Support\Collection $documentData
     *
     * @return \Illuminate\Support\Collection
     */
    private function responseDocument(Collection $documentData): Collection
    {
        return $documentData
            ->map(function (CrmBuildingSpaceDocument $document): array {
                $file = $document->file;

                return $file->only('uuid', 'extension') + [
                    'originalName' => $file->original_name,
                    'mimeType' => $file->mime_type,
                    'url' => FileMagic::find($file)->url()
                ];
            });
    }

    /**
     * 回傳房屋仲介
     *
     * @param \App\Models\CrmBuildingSpaceState|null $houseState
     * @param \Illuminate\Support\Collection $realEstateAgentEntrust
     *
     * @return \Illuminate\Support\Collection
     */
    private function responseRealEstateAgent(?CrmBuildingSpaceState $houseState, Collection $realEstateAgentEntrust): Collection
    {
        $liveState = (string) $houseState?->live;

        return $realEstateAgentEntrust
            ->reject(function (RealEstateAgentEntrust $entrust): bool {
                return empty($entrust->start_time) || empty($entrust->end_time);
            })
            ->map(function (RealEstateAgentEntrust $entrust) use ($liveState): array {
                $agent = $entrust->agent;

                $hasEntrust =
                    ($entrust->start_time->isBefore(now()) && $entrust->end_time->isAfter(now()))
                    &&
                    false === (Selected::TRUE->value === $entrust->while_sold_out && HouseLiveState::rented->name === $liveState);

                return [
                    'name' => $agent->name,
                    'startTime' => $entrust->start_time->toDateString(),
                    'endTime' => $entrust->end_time->toDateString(),
                    'companyName' => $agent->company_name,
                    'whileSoldOut' => (int) $entrust->while_sold_out
                ] + compact('hasEntrust');
            });
    }

    /**
     * 回傳斡旋金資料
     *
     * @param \Illuminate\Support\Collection $earnestPaymentData
     *
     * @return \Illuminate\Support\Collection
     */
    private function responseEarnestPayment(Collection $earnestPaymentData): Collection
    {
        return $earnestPaymentData
            ->map(function (SpaceEarnestPayment $earnestPayment): array {
                $earnestPayment->amountOfMoney = (int) $earnestPayment->amount_of_money;
                return $earnestPayment->only('uuid', 'payer', 'amountOfMoney');
            });
    }
}
