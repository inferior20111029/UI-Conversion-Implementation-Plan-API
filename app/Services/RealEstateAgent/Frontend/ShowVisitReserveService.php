<?php

declare(strict_types=1);

namespace App\Services\RealEstateAgent\Frontend;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;
use App\Support\Enum\FetchMessage;

use App\Models\Login;
use App\Models\VisitReserve;
use App\Models\CrmBuildingSpace;

use App\Repositories\VisitReserve\VisitReserveRepository;

final class ShowVisitReserveService extends Service
{
    /**
     * @param VisitReserveRepository $visitReserveRepository
     */
    public function __construct(
        private readonly VisitReserveRepository $visitReserveRepository
    ) {}

    /**
     * 取得房仲預約看房資料
     * @param string|null $uuid 看房紀錄 UUID
     * @return array
     */
    public function execute(?string $uuid = null): array
    {
        /** @var Login */
        $user = auth()->user();

        $visitReserveData = $this->fetchData($user->getRealEstateAgentId(), $uuid);
        $response = $this->fetchResponse($visitReserveData->getCollection());

        return $this->paginateResponseFormat($visitReserveData, $response);
    }

    /**
     * 取得預約看房紀錄
     * @param int $realEstateAgentId 房仲 ID
     * @param string|null $uuid 看房紀錄 UUID
     * @throws \App\Exceptions\ApiException
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function fetchData(int $realEstateAgentId, ?string $uuid = null): LengthAwarePaginator
    {
        $visitReserveData = $this->visitReserveRepository
            ->findByRealEstateAgent($realEstateAgentId, $uuid);

        if ($visitReserveData->isNotEmpty()) {
            return $visitReserveData;
        }

        $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
    }

    /**
     * 取得回傳資料
     * @param \Illuminate\Support\Collection $visitReserveData
     * @return Collection
     */
    private function fetchResponse(Collection $visitReserveData): Collection
    {
        return $visitReserveData
            ->map(function (VisitReserve $visitReserve): array {
                $property = $visitReserve->property;

                return $visitReserve->only('uuid') + [
                    'appointmentTime' => (string) $visitReserve->appointment_time,
                    'appointmentUnixTime' => $visitReserve->appointment_time?->timestamp ?? '',
                    'arrivalTime' => (string) $visitReserve->arrival_time,
                    'arrivalTimeUnixTime' => $visitReserve->arrival_time?->timestamp ?? '',
                    'numberOfVisitors' => (int) $visitReserve->number_of_visitors,
                    'visitorsName' => (string) $visitReserve->visitors_name,
                    'visitorsCellphone' => (string) $visitReserve->visitors_cellphone,
                    'alreadyCheckIn' => !empty($visitReserve->arrival_time),
                    'cancel' => $visitReserve->cancel_by !== 0,
                    'space' => $this->responseSpace($property->crmBuildingSpace)
                ];
            });
    }

    /**
     * 取得戶別資料
     * @param \App\Models\CrmBuildingSpace $spaceData
     * @return \Illuminate\Support\Collection
     */
    private function responseSpace(CrmBuildingSpace $spaceData): Collection
    {
        return collect($spaceData)
            ->mapWithKeys(fn($value, string $key): array => [str($key)->camel()->value => (string) $value]);
    }
}
