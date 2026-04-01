<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Component;

use Illuminate\Http\Request;

use App\Support\Response\ApiMessage;
use App\Support\Tool\File\FileMagic;
use App\Support\Enum\Customization;
use App\Support\Enum\SignatureMessage;

use App\Models\Fees;
use App\Models\BillAmount;
use App\Models\Decoration;
use App\Models\ContractNotify;
use App\Models\RenterContract;
use App\Models\AttachedCarpark;
use App\Models\ContractDocument;
use App\Models\AttachedEquipment;
use App\Models\RentItemsIncluded;
use App\Models\ContractBank;
use App\Models\ContractPaymentCycle;
use App\Models\ContractAssociatedPersons;
use Symfony\Component\HttpFoundation\Response;

final class InsertData
{
    use \App\Support\Trait\RenterContract\ColumnTrait;

    /**
     * @param Request $request Request
     */
    public function __construct(
        private readonly Request $request
    ) {}

    /**
     * 取得合約建立資料
     *
     * @return RenterContract
     */
    public function contract(): RenterContract
    {
        $signature = (string) $this->request->post('signature');

        $this->request->merge([
            'signature' => $signature === "" ? 0 : ($this->fetchSignatureFileId($signature) ?? 0)
        ]);

        return new RenterContract($this->fetchContractColumnData($this->request)->noHaveMacro()->toColumnArray());
    }

    /**
     * 取得包含項目建立資料
     *
     * @return array
     */
    public function itemsIncluded(): array
    {
        $itemsIncluded = (array) $this->request->post('itemsIncluded');

        return array_map(function (int $value): RentItemsIncluded {
            return new RentItemsIncluded(
                $this->fetchItemsIncludedColumnData($value)
                    ->noHaveMacro()
                    ->toColumnArray()
            );
        }, $itemsIncluded);
    }

    /**
     * 取得合約相關人員建立資料
     *
     * @return array
     */
    public function persons(): array
    {
        $associatedPersons = (array) $this->request->post('associatedPersons');
        $relationKey = $this->fetchContractRelationKey(ContractAssociatedPersons::class);

        return array_map(function (array $person) use ($relationKey): ContractAssociatedPersons {
            return new ContractAssociatedPersons(
                $this->fetchAssociatedPersonsColumnData($person)
                    ->excludeColumn($relationKey)
                    ->toColumnArray()
            );
        }, $associatedPersons);
    }

    /**
     * 取得合約文件建立資料
     *
     * @return array
     */
    public function document(): array
    {
        $document = array_unique((array) $this->request->post('document'));
        $file = (array) FileMagic::find($document)->get();
        $relationKey = $this->fetchContractRelationKey(ContractDocument::class);

        return array_map(function (array $value) use ($relationKey): ContractDocument {
            $fileId = (int) data_get($value, 'id');

            return new ContractDocument(
                $this->fetchDocumentColumnData($fileId)
                    ->excludeColumn($relationKey)
                    ->toColumnArray()
            );
        }, $file);
    }

    /**
     * 取得付款週期建立資料
     *
     * @return ContractPaymentCycle
     */
    public function paymentCycle(): ContractPaymentCycle
    {
        $paymentCycle = (array) $this->request->post('paymentCycle');

        if (!empty($paymentCycle)) {
            $relationKey = $this->fetchContractRelationKey(ContractPaymentCycle::class);

            return new ContractPaymentCycle(
                $this->fetchPaymentCycleColumnData($paymentCycle)
                    ->excludeColumn($relationKey)
                    ->toColumnArray()
            );
        }

        return new ContractPaymentCycle();
    }

    /**
     * 取得合約通知建立資料
     *
     * @return array
     */
    public function notify(): array
    {
        $notify = (array) $this->request->post('notify');
        $relationKey = $this->fetchContractRelationKey(ContractNotify::class);

        return array_map(function (array $value) use ($relationKey): ContractNotify {
            return new ContractNotify(
                $this->fetchContractNotifyColumnData($value)
                    ->excludeColumn($relationKey, 'already_trigger')
                    ->toColumnArray()
            );
        }, $notify);
    }

    /**
     * 取得裝潢程度建立資料
     *
     * @return Decoration
     */
    public function decoration(): Decoration
    {
        $decoration = (array) $this->request->post('decoration');

        if (!empty($decoration)) {
            return new Decoration(
                $this->fetchDecorationColumnData($decoration)
                    ->noHaveMacro()
                    ->toColumnArray()
            );
        }

        return new Decoration();
    }

    /**
     * 取得費用建立資料
     *
     * @return Fees
     */
    public function fees(): Fees
    {
        $fees = (array) $this->request->post('fees');

        if (!empty($fees)) {
            return new Fees(
                $this->fetchFeesColumnData($fees)
                    ->noHaveMacro()
                    ->toColumnArray()
            );
        }

        return new Fees();
    }

    /**
     * 取得附設車位建立資料
     *
     * @return array
     */
    public function carpark(): array
    {
        $carpark = (array) $this->request->post('carpark');

        return array_map(function (array $value): AttachedCarpark {
            return new AttachedCarpark(
                $this->fetchCarparkColumnData($value)
                    ->noHaveMacro()
                    ->toColumnArray()
            );
        }, $carpark);
    }

    /**
     * 取得附設設備建立資料
     *
     * @return array
     */
    public function equipment(): array
    {
        $equipment = (array) $this->request->post('equipment');

        return array_map(function (int $crmEquipmentId): AttachedEquipment {
            return new AttachedEquipment(
                $this->fetchEquipmentColumnData($crmEquipmentId)
                    ->onlyColumn('crm_equipment_id')
                    ->toColumnArray()
            );
        }, $equipment);
    }

    /**
     * 取得帳單建立資料
     *
     * @return array
     */
    public function bill(): array
    {
        return $this->fetchContractBillColumnData($this->request)->toColumnArray();
    }

    /**
     * 取得帳單金額建立資料
     *
     * @param array $billAmount 金額資料
     * @param boolean $isCustomization 是否為自訂
     *
     * @return array
     */
    public function billAmount(array $billAmount, bool $isCustomization = false): array
    {
        $customization = $isCustomization ? Customization::TRUE->value : Customization::FALSE->value;

        return array_map(function (array $value) use ($customization): BillAmount {
            return new BillAmount(
                $this->fetchBillAmountColumnData($value + compact('customization'))
                    ->excludeColumn('contract_bill_id')
                    ->toColumnArray()
            );
        }, $billAmount);
    }

    /**
     * 取得銀行帳戶資料
     *
     * @return ContractBank
     */
    public function bank(): ContractBank
    {
        return new ContractBank(
            $this
                ->fetchContractBankColumnData(
                    (array) $this->request->post('bank')
                )
                ->toColumnArray()
        );
    }

    /**
     * 取得檔案簽名 ID
     * @param string $signatureRequest 檔案 UUID 或是 base64 圖片
     * @throws \App\Exceptions\ApiException
     * @return int
     */
    public function fetchSignatureFileId(string $signatureRequest): int
    {
        $signatureFileId = 0;

        if (str($signatureRequest)->isUuid()) {
            $signatureFileId = (int) FileMagic::find($signatureRequest)
                ->get()
                ?->id;
        } elseif (FileMagic::isBase64Image($signatureRequest)) {
            $signatureFileId = (int) FileMagic::parse($signatureRequest)
                ->disk('s3')
                ->path('leasehold/signature')
                ->save()
                ?->id;

            if (0 === $signatureFileId) {
                (new ApiMessage())->throwException(SignatureMessage::createFails->value, Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        if (0 !== $signatureFileId) {
            return $signatureFileId;
        }

        (new ApiMessage())->throwException(SignatureMessage::notFoundSignatureData->value, Response::HTTP_NOT_FOUND);
    }
}
