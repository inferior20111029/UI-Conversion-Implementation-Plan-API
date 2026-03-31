<?php

declare(strict_types=1);

namespace App\Services\RenterContract\AcceptanceRejection;

use App\Models\CrmEquipment;
use App\Models\RenterContract;
use App\Models\inspectionReturnEquipment;

use Illuminate\Support\Collection;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;
use App\Support\Tool\File\FileMagic;
use App\Support\Enum\FetchMessage;

use App\Repositories\RenterContract\RenterContractRepository;
use App\Repositories\RenterContract\RenterInspectionReturnRepository;


final class ShowService extends Service
{
    /**
     * @param  RenterContractRepository  $renterContractRepository
     * @param  RenterInspectionReturnRepository  $renterInspectionReturnRepository
     */
    public function __construct(
        private readonly RenterContractRepository          $renterContractRepository,
        private readonly RenterInspectionReturnRepository  $renterInspectionReturnRepository,
    ) {
    }

    /**
     * 取得合約資料
     *
     * @param string|null $uuid UUID
     *
     * @return \Illuminate\Support\Collection
     */
    public function execute(?string $uuid = null): Collection
    {
        $companyId = crm('company_id');
        $comid     = crm('community_id');

        $result = $this->renterContractRepository->findByUuid($companyId, $comid, $uuid);

        if (!empty($result)) {
            return $result;
        }

        $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
    }

    /**
     * 檢視驗收/驗退單
     *
     * @param  RenterContract  $contract
     *
     * @return Collection
     */
    public function review(RenterContract $contract): array
    {
        $companyId = crm('company_id');
        $comid     = crm('community_id');

        $result = $this->renterInspectionReturnRepository
            ->findByContractId($companyId, $comid, $contract->id, (int) request()->type);

        if (empty($result)) {
            $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
        }

        return [
            'signature'         => FileMagic::find($result->signature)->url(),
            'remark'            => $result->remark,
            'attachedEquipment' => self::responseEquipment($result->inspectionReturnEquipment)
        ];

    }

    /**
     * @param  Collection  $attachedEquipment
     *
     * @return Collection
     */
    public function responseEquipment(Collection $attachedEquipment): Collection
    {
        return $attachedEquipment
            ->map(function (inspectionReturnEquipment|CrmEquipment $inspectionReturn): array {
                $equipment = $inspectionReturn instanceof CrmEquipment
                    ? $inspectionReturn
                    : $inspectionReturn->equipment;

                return [
                    'id'         => (int) $equipment->id,
                    'name'       => (string) $equipment->name,
                    'state'      => (int) $inspectionReturn->state,
                    'isScrap'    => isset($equipment->crmEquipmentScrap),
                    'scrapAt'    => (string) optional($equipment->crmEquipmentScrap)->updated_at?->toDateString(),
                    'purchaseAt' => (string) $equipment->updated_at?->toDateString(),
                ];
            });
    }
}