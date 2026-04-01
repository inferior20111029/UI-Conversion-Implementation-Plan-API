<?php

declare(strict_types=1);

namespace App\Services\RenterContract\AcceptanceRejection;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use App\Models\CrmEquipment;
use App\Models\RenterContract;
use App\Models\AttachedEquipment;

use App\Support\Abstract\Service;

use App\Services\RenterContract\Component\InsertData;

use App\Repositories\RenterContract\RenterInspectionReturnRepository;
use App\Repositories\RenterContract\InspectionReturnEquipmentRepository;

final class StoreService extends Service
{
    /**
     * @param  RenterInspectionReturnRepository  $renterInspectionReturnRepository
     * @param  InspectionReturnEquipmentRepository  $inspectionReturnEquipmentRepository
     */
    public function __construct(
        private readonly RenterInspectionReturnRepository    $renterInspectionReturnRepository,
        private readonly InspectionReturnEquipmentRepository $inspectionReturnEquipmentRepository,
    ) {}

    /**
     * 驗收驗退
     *
     * @param Request $request
     * @return void
     */
    public function execute(Request $request, RenterContract $contract): void
    {
        $signatureBase64 = (string) $request->post('signature');
        $signature = (new InsertData($request))->fetchSignatureFileId($signatureBase64);

        if ($contract->attachedEquipment->isEmpty()){
            $attachedEquipmentIds = $this->responseEquipment($contract->equipment)->pluck('id');
        } else {
            $attachedEquipmentIds = $this->responseEquipment($contract->attachedEquipment)->pluck('id');
        }

        $postedEquipmentIds   = collect($request->post('id'));

        $renterInspectionReturnId = $this->renterInspectionReturnRepository->insertGetId([
            'uuid'               => str()->uuid()->toString(),
            'signature'          => $signature,
            'renter_contract_id' => $contract->id,
            'remark'             => $request->post('remark'),
            'type'               => $request->post('type'),
        ]);

        $equipmentData = self::fetchEquipmentData($attachedEquipmentIds, $postedEquipmentIds, $renterInspectionReturnId);

        $this->inspectionReturnEquipmentRepository->insert($equipmentData);
    }

    /**
     * @param  $contract
     *
     * @return Collection
     */
    public function responseEquipment($contract): Collection
    {
        return $contract
            ->map(function ($attachedEquipment): array {

                $equipment = $attachedEquipment instanceof CrmEquipment
                    ? $attachedEquipment
                    : $attachedEquipment->equipment;

                return [
                    'id' => (int) $equipment->id,
                ];
            });
    }

    /**
     * @param  Collection  $attachedEquipmentIds
     * @param  Collection  $postedEquipmentIds
     * @param  int  $renterInspectionReturnId
     * @return array
     */
    public function fetchEquipmentData(
        Collection $attachedEquipmentIds,
        Collection $postedEquipmentIds,
        int $renterInspectionReturnId
    ): array {
        $now = now();

        $formatEquipmentData = fn($equipmentId, $state) => [
            'renter_inspection_return_id' => $renterInspectionReturnId,
            'crm_equipment_id'            => (int) $equipmentId,
            'state'                       => $state,
            'created_at'                  => $now,
            'updated_at'                  => $now,
        ];

        $diffEquipments = $attachedEquipmentIds->diff($postedEquipmentIds)
            ->map(fn($equipmentId) => $formatEquipmentData($equipmentId, 0))
            ->toArray();

        $postedEquipments = $postedEquipmentIds
            ->map(fn($equipmentId) => $formatEquipmentData($equipmentId, 1))
            ->toArray();

        return [...$diffEquipments, ...$postedEquipments];
    }
}