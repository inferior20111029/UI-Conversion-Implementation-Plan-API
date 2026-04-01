<?php

declare(strict_types=1);

namespace App\Services\VisitCheckIn;


use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;
use App\Support\Enum\FetchMessage;
use App\Support\Tool\File\FileMagic;

use App\Models\VisitReserve;

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
     * @return array
     */
    public function execute(): array
    {
        $uuid = (string)request()->uuid;
        $visitReserveData = $this->fetchData($uuid);

        return $this->fetchResponse($visitReserveData);
    }

    /**
     * 取得預約資料
     *
     * @param  string|null  $uuid  預約 UUID
     *
     * @return VisitReserve|null
     */
    public function fetchData(?string $uuid = null): ?VisitReserve
    {
        $visitReserveData = $this->visitReserveRepository->fetchByUuid($uuid);

        if ($visitReserveData) {
            return $visitReserveData;
        }

        $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
    }

    /**
     * 取得回傳資料
     *
     * @param  VisitReserve  $visitReserve
     *
     * @return array
     */
    private function fetchResponse(VisitReserve $visitReserve): array
    {
        $crmBuildingSpace = $visitReserve?->property->crmBuildingSpace;

        return [
            'propertyUuid'        => $visitReserve->uuid,
            'appointmentTime'     => (string) $visitReserve->appointment_time,
            'appointmentUnixTime' => $visitReserve->appointment_time?->timestamp ?? '',
            'arrivalTime'         => (string) $visitReserve->arrival_time,
            'arrivalTimeUnixTime' => $visitReserve->arrival_time?->timestamp ?? '',
            'numberOfVisitors'    => (int) $visitReserve->number_of_visitors,
            'visitorsName'        => (string) $visitReserve->visitors_name,
            'visitorsCellphone'   => (string) $visitReserve->visitors_cellphone,
            'alreadyCheckIn'      => !empty($visitReserve->arrival_time),
            'cancel'              => $visitReserve->cancel_by !== 0,
            'household_name'      => $crmBuildingSpace->household_name ?? null,
            'comname'             => $crmBuildingSpace->community->comname ?? null,
            'signature'           => FileMagic::find($visitReserve->signature)->url(),
            ];
    }
}