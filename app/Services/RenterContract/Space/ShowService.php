<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Space;

use Illuminate\Support\Collection;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;

use App\Support\Enum\FetchMessage;
use App\Support\Tool\File\FileMagic;

use App\Models\RenterContract;
use App\Models\CrmBuildingSpace;

use App\Repositories\Space\CrmBuildingSpaceRepository;

final class ShowService extends Service
{
    use \App\Support\Trait\Space\CheckTrait;
    use \App\Support\Trait\RenterContract\ResponseTrait;

    /**
     * @param CrmBuildingSpaceRepository $crmBuildingSpaceRepository
     */
    public function __construct(
        private readonly CrmBuildingSpaceRepository $crmBuildingSpaceRepository
    ) {
    }

    /**
     * 取得合約資料
     *
     * @param string spaceId 戶別 ID
     * @param string|null $uuid UUID
     *
     * @return \Illuminate\Support\Collection
     */
    public function execute(string $spaceId, ?string $uuid = null): Collection
    {
        $this->spaceExists($spaceId);

        $spaceData = $this->fetchData($spaceId, $uuid);
        return $this->fetchResponse($spaceData);
    }

    /**
     * 取得合約資料
     *
     * @param string spaceId 戶別 ID
     * @param string|null $uuid 合約 UUID
     *
     * @throws \App\Exceptions\ApiException
     *
     * @return \App\Models\CrmBuildingSpace
     */
    public function fetchData(string $spaceId, ?string $uuid): CrmBuildingSpace
    {
        $result = $this->crmBuildingSpaceRepository->fetchContract(
            crm('company_id'),
            crm('community_id'),
            $spaceId,
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
     * @param \App\Models\CrmBuildingSpace $contractData 戶別資料
     *
     * @return \Illuminate\Support\Collection
     */
    private function fetchResponse(CrmBuildingSpace $spaceData): Collection
    {
        return $spaceData->renterContract
            ->map(function (RenterContract $contract) use ($spaceData): array {
                $signature = $contract->getRelation('signature');

                $equipment = $contract->attachedEquipment->isNotEmpty()
                    ? $contract->attachedEquipment
                    : $spaceData->equipment;

                $customer = (int) $contract->attachedEquipment->isNotEmpty();
                $attachedEquipment = compact('customer') + ['detail' => $this->responseEquipment($equipment)->toArray()];

                return $contract->only(
                    'uuid',
                    'name',
                    'cellphone',
                    'birthday',
                    'restore',
                    'remark'
                ) + [
                    'buildingName' => $spaceData->building_name,
                    'householdName' => $spaceData->household_name,
                    'nationalIdNumber' => $contract->national_id_number,
                    'startTime' => (string) $contract->start_time?->toDateString(),
                    'endTime' => (string) $contract->end_time?->toDateString(),
                    'startTimeUnixTime' => $contract->start_time?->timestamp ?? '',
                    'endTimeUnixTime' => $contract->end_time?->timestamp ?? '',
                    'allowDeclare' => $contract->allow_declare,
                    'allowEarlyTermination' => $contract->allow_early_termination,
                    'allowSublease' => $contract->allow_sublease,
                    'decoration' => $contract?->decoration?->only('degree', 'time') ?? '',
                    'rentItemsIncluded' => $contract->rentItemsIncluded->pluck('rent_items_options_id')->all(),
                    'signature' => [
                        'uuid' => (string) $signature?->uuid,
                        'url' => FileMagic::find($signature)->url()
                    ],
                    'fees' => $this->responseFees($contract->fees),
                    'attachedCarpark' => $this->responseCarpark($contract->attachedCarpark),
                    'attachedEquipment' => $attachedEquipment,
                    'paymentCycle' => $this->responsePaymentCycle($contract->paymentCycle),
                    'person' => $this->responsePerson($contract->persons),
                    'notify' => $this->responseNotify($contract->notify),
                    'document' => $this->responseDocument($contract->document),
                    'contractURL' => $this->responseContract($contract?->file_id),
                    'terminationState' => $this->responseTerminationState($contract->end_time, $contract->termination_state),
                    'terminationReason' => (string) $contract->termination_reason,
                    'terminationDate' => (string) $contract->termination_at?->toDateString(),
                    'terminationUnixTime' => $contract->termination_at?->timestamp ?? '',
                    'bill' => $this->responseBill($contract->bill),
                    'bank' => $this->responseBank($contract->bank),
                    'isCache'    => !($contract->file_id !== 0) && !empty($contract->cache),
                    'inspection' => $this->responseInspection($contract->renterInspectionReturn, 0),
                    'rejection'  => $this->responseInspection($contract->renterInspectionReturn, 1),
                ];
            });
    }
}
