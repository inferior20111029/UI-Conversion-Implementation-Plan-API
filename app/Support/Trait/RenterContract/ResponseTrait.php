<?php

declare(strict_types=1);

namespace App\Support\Trait\RenterContract;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

use App\Models\Fees;
use App\Models\BillAmount;
use App\Models\CrmEquipment;
use App\Models\ContractBill;
use App\Models\ContractBank;
use App\Models\ContractNotify;
use App\Models\AttachedCarpark;
use App\Models\ContractDocument;
use App\Models\AttachedEquipment;
use App\Models\ContractPaymentCycle;
use App\Models\ContractAssociatedPersons;

use App\Support\Tool\File\FileMagic;
use App\Support\Enum\TerminationState;

trait ResponseTrait
{
    /**
     * 回傳費用資料
     *
     * @param \App\Models\Fees|null $fees
     *
     * @return array
     */
    public function responseFees(?Fees $fees): array
    {
        $dataKey = ['price', 'deposit', 'depositTotalMonth', 'managementFee'];

        if (!empty($fees)) {
            $fees->depositTotalMonth = $fees->deposit_total_month;
            $fees->managementFee = $fees->management_fee;

            return $fees->only($dataKey);
        }

        return array_fill_keys($dataKey, '');
    }

    /**
     * 回傳停車位資料
     *
     * @param \Illuminate\Support\Collection $attachedCarpark
     *
     * @return \Illuminate\Support\Collection
     */
    public function responseCarpark(Collection $attachedCarpark): Collection
    {
        return $attachedCarpark
            ->map(function (AttachedCarpark $carpark): array {
                $parkingSpace = $carpark->crmParkingSpace;

                return $carpark
                    ->only('type', 'price') + [
                        'crmParkingSpaceId' => $carpark->crm_parking_space_id,
                        'licensePlateNumber' => $carpark->license_plate_number,
                        'parkingNumber' => (string) $parkingSpace->parking_number,
                        'application' => (string) $parkingSpace->application,
                    ];
            });
    }

    /**
     * 回傳設備資料
     *
     * @param \Illuminate\Support\Collection $attachedEquipment
     *
     * @return \Illuminate\Support\Collection
     */
    public function responseEquipment(Collection $attachedEquipment): Collection
    {
        return $attachedEquipment
            ->map(function (AttachedEquipment|CrmEquipment $attachedEquipment): array {
                $equipment = $attachedEquipment instanceof CrmEquipment
                    ? $attachedEquipment
                    : $attachedEquipment->equipment;

                return [
                    'id' => (int) $equipment->id,
                    'name' => (string) $equipment->name,
                    'type' => (string) $equipment?->crmTypeName?->name,
                    'system' => (string) $equipment?->crmSystemName?->name,
                    'area' => (string) $equipment->area,
                    'location' => (string) $equipment->location,
                    'space' => (string) $equipment->space,
                    'publicType' => (string) $equipment->public_type,
                    'status' => (int) $equipment->status,
                    'from' => (string) $equipment->from,
                    'isScrap' => isset($equipment->crmEquipmentScrap),
                    'scrapAt' => (string) optional($equipment->crmEquipmentScrap)->updated_at?->toDateString(),
                    'purchaseAt' => (string) $equipment->updated_at?->toDateString(),
                    'updateAt' => (string) $equipment->updated_at?->toDateString()
                ];
            });
    }

    /**
     * 回傳付款週期資料
     *
     * @param \App\Models\ContractPaymentCycle|null $paymentCycle
     *
     * @return array
     */
    public function responsePaymentCycle(?ContractPaymentCycle $paymentCycle): array
    {
        $dataKey = ['type', 'month', 'dayOfWeek', 'dayOfMonth'];

        if (!empty($paymentCycle)) {
            $paymentCycle->dayOfWeek = $paymentCycle->day_of_week;
            $paymentCycle->dayOfMonth = $paymentCycle->day_of_month;

            return $paymentCycle->only($dataKey);
        }

        return array_fill_keys($dataKey, '');
    }

    /**
     * 回傳合約相關人員資料
     *
     * @param \Illuminate\Support\Collection $associatedPersons
     *
     * @return \Illuminate\Support\Collection
     */
    public function responsePerson(Collection $associatedPersons): Collection
    {
        return $associatedPersons
            ->map(function (ContractAssociatedPersons $person): array {
                return $person
                    ->only('uuid', 'type', 'name', 'cellphone', 'birthday') + [
                        'nationalIdNumber' => $person->national_id_number
                    ];
            });
    }

    /**
     * 回傳合約通知資料
     *
     * @param \Illuminate\Support\Collection $ContractNotify
     *
     * @return \Illuminate\Support\Collection
     */
    public function responseNotify(Collection $ContractNotify): Collection
    {
        return $ContractNotify
            ->map(function (ContractNotify $notify): array {
                return $notify
                    ->only('uuid', 'type') + [
                        'triggerUnixTime' => !empty($notify->trigger_time) ? strtotime($notify->trigger_time) : ''
                    ];
            });
    }

    /**
     * 回傳合約文件
     *
     * @param \Illuminate\Support\Collection $document
     *
     * @return \Illuminate\Support\Collection
     */
    public function responseDocument(Collection $document): Collection
    {
        return $document
            ->map(function (ContractDocument $document): array {
                $file = $document->file;

                return $file
                    ->only('uuid', 'extension') + [
                        'fileOriginalName' => $file->original_name,
                        'fileName' => $file->name,
                        'mimeType' => $file->mime_type,
                        'url' => FileMagic::find($file)->url()
                    ];
            });
    }

    /**
     * 回傳合約文檔
     *
     * @param  string  $fileId
     *
     * @return int|null
     */
    public function responseContract(int $fileId): ?string
    {
        return FileMagic::find($fileId)->url();
    }

    /**
     * 回傳合約終止狀態
     *
     * @param \Illuminate\Support\Carbon $endTime
     * @param integer $terminationState
     *
     * @return integer
     */
    public function responseTerminationState(Carbon $endTime, int $terminationState): int
    {
        return intval(now()->greaterThan($endTime) || (int) $terminationState === TerminationState::ALREADY->value);
    }

    /**
     * 回傳帳單資料
     *
     * @param \Illuminate\Support\Collection $billData 帳單資料
     *
     * @return \Illuminate\Support\Collection
     */
    public function responseBill(Collection $billData): Collection
    {
        return $billData
            ->map(function (ContractBill $bill): array {
                return $bill
                    ->only('uuid', 'paid') + [
                        'startTime' => (string) $bill->start_time,
                        'endTime' => (string) $bill->end_time,
                        'createAt' => $bill->created_at->toDateString(),
                        'createAtUnixTime' => $bill->created_at->timestamp,
                        'includeTax' => $bill->include_tax,
                        'amount' => $bill->amount
                            ->map(function (BillAmount $amount): array {
                                return $amount
                                    ->only('price', 'customization') + [
                                        'lineItem' => $amount->line_item
                                    ];
                            })
                    ];
            });
    }

    /**
     * 回傳銀行帳戶資料
     *
     * @param \App\Models\ContractBank|null $bankData
     *
     * @return array
     */
    public function responseBank(?ContractBank $bankData): array
    {
        $dataKey = ['type', 'code', 'account'];

        if (!empty($bankData)) {
            return $bankData->only($dataKey);
        }

        return array_fill_keys($dataKey, '');
    }

    /**
     * 回傳驗收/驗退資料
     *
     * @param  Collection  $renterInspectionReturn
     * @param $type
     *
     * @return array
     */
    public function responseInspection(Collection $renterInspectionReturn, $type): array
    {
        $inspection = $renterInspectionReturn->where('type', $type)->last();

        return [
            'isChecked' => $inspection !== null ? true : false,
            'online'    => $inspection && $inspection->signature !== 0,
            'upload'    => $inspection && $inspection->file_id !== 0,
            'fileURL'   => FileMagic::find($inspection?->file?->uuid)->url() ?? null
        ];
    }
}
