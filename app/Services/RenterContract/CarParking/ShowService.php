<?php

declare(strict_types=1);

namespace App\Services\RenterContract\CarParking;

use Illuminate\Support\Collection;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;

use App\Support\Enum\FetchMessage;
use App\Support\Tool\File\FileMagic;

use App\Models\RenterContract;
use App\Models\CrmParkingSpace;

use App\Repositories\Space\CrmParkingSpaceRepository;

final class ShowService extends Service
{
    use \App\Support\Trait\Space\CheckTrait;
    use \App\Support\Trait\RenterContract\ResponseTrait;

    /**
     * @param CrmParkingSpaceRepository $crmParkingSpaceRepository
     */
    public function __construct(
        private readonly CrmParkingSpaceRepository $crmParkingSpaceRepository
    ) {}

    /**
     * 取得合約資料
     *
     * @param string carParkingId 車位 ID
     * @param string|null $uuid UUID
     *
     * @return \Illuminate\Support\Collection
     */
    public function execute(string $carParkingId, ?string $uuid = null): Collection
    {
        $carParkingData = $this->fetchData($carParkingId, $uuid);
        return $this->fetchResponse($carParkingData);
    }

    /**
     * 取得合約資料
     *
     * @param string carParkingId 車位 ID
     * @param string|null $uuid 合約 UUID
     *
     * @throws \App\Exceptions\ApiException
     *
     * @return \App\Models\CrmParkingSpace
     */
    public function fetchData(string $carParkingId, ?string $uuid): CrmParkingSpace
    {
        $result = $this->crmParkingSpaceRepository->fetchContract(
            crm('company_id'),
            crm('community_id'),
            $carParkingId,
            $uuid
        );

        if (!empty($result)) {
            return $result;
        }

        $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
    }

    /**
     * 取得回傳資料
     *
     * @param \App\Models\CrmParkingSpace $carParkingData 車位資料
     *
     * @return \Illuminate\Support\Collection
     */
    private function fetchResponse(CrmParkingSpace $carParkingData): Collection
    {
        return $carParkingData->renterContract
            ->map(function (RenterContract $contract) use ($carParkingData): array {
                $signature = $contract->getRelation('signature');
                $fromMutual = $contract->fromMutual;

                $fees = $this->responseFees($contract->fees);

                /** 車位合約不需有管理費資料 */
                data_forget($fees, 'managementFee');

                return $contract->only(
                    'uuid',
                    'name',
                    'cellphone',
                    'birthday',
                    'remark'
                ) + [
                    'parkingNumber' => (string) $carParkingData->parking_number,
                    'application' => (string) $carParkingData->application,
                    'nationalIdNumber' => (string) $contract->national_id_number,
                    'startTime' => (string) $contract->start_time?->toDateString(),
                    'endTime' => (string) $contract->end_time?->toDateString(),
                    'startTimeUnixTime' => $contract->start_time?->timestamp ?? '',
                    'endTimeUnixTime' => $contract->end_time?->timestamp ?? '',
                    'allowDeclare' => $contract->allow_declare,
                    'allowEarlyTermination' => $contract->allow_early_termination,
                    'allowSublease' => $contract->allow_sublease,
                    'signature' => [
                        'uuid' => (string) $signature?->uuid,
                        'url' => FileMagic::find($signature)->url()
                    ],
                    'fees' => $fees,
                    'paymentCycle' => $this->responsePaymentCycle($contract->paymentCycle),
                    'person' => $this->responsePerson($contract->persons),
                    'notify' => $this->responseNotify($contract->notify),
                    'document' => $this->responseDocument($contract->document),
                    'terminationState' => $this->responseTerminationState($contract->end_time, $contract->termination_state),
                    'terminationReason' => (string) $contract->termination_reason,
                    'terminationDate' => (string) $contract->termination_at?->toDateString(),
                    'terminationUnixTime' => $contract->termination_at?->timestamp ?? '',
                    'bill' => $this->responseBill($contract->bill),
                    'bank' => $this->responseBank($contract->bank),
                    'contractURL' => $this->responseContract($contract?->file_id),
                    'fromMutual' => !empty($fromMutual),
                    'isCache'    => !($contract->file_id !== 0) && !empty($contract->cache),
                    'mutualSourceContract' => [
                        'uuid' => (string) $fromMutual?->sourceContract?->uuid,
                        'spaceId' => (string) $fromMutual?->sourceContract?->taggable_id
                    ]
                ];
            });
    }
}
