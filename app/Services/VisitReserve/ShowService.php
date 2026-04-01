<?php

declare(strict_types=1);

namespace App\Services\VisitReserve;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;
use App\Support\Enum\FetchMessage;
use App\Support\Tool\File\FileMagic;

use App\Models\VisitReserve;
use App\Models\RealEstateAgent;
use App\Models\CrmBuildingSpace;
use App\Models\Login;
use App\Models\Login\LoginUser;

use App\Repositories\VisitReserve\VisitReserveRepository;

final class ShowService extends Service
{
    /**
     * @param VisitReserveRepository $visitReserveRepository
     */
    public function __construct(
        private readonly VisitReserveRepository $visitReserveRepository
    ) {}

    /**
     * 取得房屋預約
     *
     * @param string|null $uuid 房屋預約 UUID
     *
     * @return array
     */
    public function execute(?string $uuid = null): array
    {
        $visitReserveData = $this->fetchData($uuid);
        $response = $this->fetchResponse($visitReserveData->getCollection());

        return $this->paginateResponseFormat($visitReserveData, $response);
    }

    /**
     * 取得預約資料
     * @param string|null $uuid 預約 UUID
     * @throws \App\Exceptions\ApiException
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function fetchData(?string $uuid = null): LengthAwarePaginator
    {
        $companyId = crm('company_id');
        $communityId = crm('community_id');

        $visitReserveData = str($uuid)->isUuid()
            ? $this->visitReserveRepository->findByUuid($companyId, $communityId, $uuid)
            : $this->visitReserveRepository->findAll($companyId, $communityId);

        if ($visitReserveData->isNotEmpty()) {
            return $visitReserveData;
        }

        $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
    }

    /**
     * 取得回傳資料
     *
     * @param \Illuminate\Support\Collection $visitReserveData
     *
     * @return \Illuminate\Support\Collection
     */
    private function fetchResponse(Collection $visitReserveData): Collection
    {
        return $visitReserveData
            ->map(function (VisitReserve $visitReserve): array {
                $property = $visitReserve->property;
                $signatureFile = $visitReserve->signatureFile;

                return $visitReserve->only('uuid') + [
                    'propertyUuid' => $property->uuid,
                    'founder' => $this->responseFounderName($visitReserve),
                    'appointmentTime' => (string) $visitReserve->appointment_time,
                    'appointmentUnixTime' => $visitReserve->appointment_time?->timestamp ?? '',
                    'arrivalTime' => (string) $visitReserve->arrival_time,
                    'arrivalTimeUnixTime' => $visitReserve->arrival_time?->timestamp ?? '',
                    'numberOfVisitors' => (int) $visitReserve->number_of_visitors,
                    'visitorsName' => (string) $visitReserve->visitors_name,
                    'visitorsCellphone' => (string) $visitReserve->visitors_cellphone,
                    'alreadyCheckIn' => !empty($visitReserve->arrival_time),
                    'cancel' => $visitReserve->cancel_by !== 0,
                    'signature' => [
                        'uuid' => $signatureFile?->uuid ?? '',
                        'url' => FileMagic::find($signatureFile)->url()
                    ],
                    'space' => $this->responseSpace($property->crmBuildingSpace),
                    'realEstateAgent' => $this->responseRealEstateAgent($visitReserve->realEstateAgent)
                ];
            });
    }

    /**
     * 回傳戶別資料
     *
     * @param \App\Models\CrmBuildingSpace $spaceData
     *
     * @return \Illuminate\Support\Collection
     */
    private function responseSpace(CrmBuildingSpace $spaceData): Collection
    {
        return collect($spaceData)
            ->mapWithKeys(fn($value, string $key): array => [str($key)->camel()->value => (string) $value]);
    }

    /**
     * 回傳房屋仲介資料
     *
     * @param \App\Models\RealEstateAgent $realEstateAgent
     *
     * @return \Illuminate\Support\Collection
     */
    private function responseRealEstateAgent(RealEstateAgent $realEstateAgent): Collection
    {
        return collect($realEstateAgent)
            ->forget('id')
            ->mapWithKeys(fn($value, string $key): array => [str($key)->camel()->value => (string) $value]);
    }

    /**
     * 取得建立者名字
     * @param \App\Models\VisitReserve $visitReserve
     * @return string
     */
    private function responseFounderName(VisitReserve $visitReserve): string
    {
        return match (get_class($visitReserve->visitReserveTable)) {
            LoginUser::class => (string) $visitReserve->visitReserveTable?->username,
            Login::class => (string) $visitReserve->visitReserveTable?->loginRealEstateAgent?->name,
            default => ''
        };
    }
}
