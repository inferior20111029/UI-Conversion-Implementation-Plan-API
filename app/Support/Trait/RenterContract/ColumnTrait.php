<?php

declare(strict_types=1);

namespace App\Support\Trait\RenterContract;

use Illuminate\Http\Request;

use App\Models\RenterContract;

use App\Support\Data\FeesData;
use App\Support\Data\BillAmountData;
use App\Support\Data\DecorationData;
use App\Support\Data\ContractBillData;
use App\Support\Data\ContractNotifyData;
use App\Support\Data\RenterContractData;
use App\Support\Data\AttachedCarparkData;
use App\Support\Data\ContractDocumentData;
use App\Support\Data\AttachedEquipmentData;
use App\Support\Data\RentItemsIncludedData;
use App\Support\Data\ContractBankData;
use App\Support\Data\ContractPaymentCycleData;
use App\Support\Data\ContractAssociatedPersonsData;

trait ColumnTrait
{
    /**
     * 取得 UUID 欄位資料
     *
     * @param string|null $fillingUuid
     *
     * @return array
     */
    public function uuidColumn(?string $fillingUuid = null): array
    {
        $uuid = empty($fillingUuid) ? str()->uuid()->toString() : $fillingUuid;
        return compact("uuid");
    }

    /**
     * 取得多態欄位資料
     *
     * @param \App\Models\RenterContract $contractData 合約資料
     *
     * @return array
     */
    public function morphColumn(RenterContract $contractData): array
    {
        return [
            'taggable_type' => $contractData::class,
            'taggable_id' => $contractData->id
        ];
    }

    /**
     * 取得關聯欄位
     *
     * @param \App\Models\RenterContract $contractData 合約資料
     * @param string $contractRelationKey
     *
     * @return array
     */
    public function contractColumn(RenterContract $contractData, string $contractRelationKey): array
    {
        return [
            $contractRelationKey => $contractData->id
        ];
    }

    /**
     * 取得合約 relation key
     *
     * @param string $modelClass
     * @return string
     */
    public function fetchContractRelationKey(string $modelClass): string
    {
        return (new $modelClass())->contract()->getForeignKeyName();
    }

    /**
     * 取得合約欄位資料
     *
     * @param \Illuminate\Http\Request $request Request
     *
     * @return \App\Support\Data\RenterContractData
     */
    public function fetchContractColumnData(Request $request): RenterContractData
    {
        $allowDeclare = $request->integer('allowDeclare');
        $allowEarlyTermination = $request->integer('allowEarlyTermination');
        $allowSublease = $request->integer('allowSublease');
        $restore = $request->integer('restore');

        return new RenterContractData(
            $this->uuidColumn() + compact('allowDeclare', 'allowEarlyTermination', 'allowSublease', 'restore') + $request->all()
        );
    }

    /**
     * 租金包含項目欄位
     *
     * @param integer $rentItemsOptionsId
     *
     * @return \App\Support\Data\RentItemsIncludedData
     */
    public function fetchItemsIncludedColumnData(int $rentItemsOptionsId): RentItemsIncludedData
    {
        return new RentItemsIncludedData(compact('rentItemsOptionsId'));
    }

    /**
     * 取得合約相關人員欄位
     *
     * @param array $person
     *
     * @return \App\Support\Data\ContractAssociatedPersonsData
     */
    public function fetchAssociatedPersonsColumnData(array $person): ContractAssociatedPersonsData
    {
        return new ContractAssociatedPersonsData($this->uuidColumn() + $person);
    }

    /**
     * 取得合約文件欄位
     *
     * @param array $person
     *
     * @return \App\Support\Data\ContractDocumentData
     */
    public function fetchDocumentColumnData(int $fileId): ContractDocumentData
    {
        return new ContractDocumentData($this->uuidColumn() + compact('fileId'));
    }

    /**
     * 取得合約付款週期欄位
     *
     * @param array $paymentCycle
     *
     * @return \App\Support\Data\ContractPaymentCycleData
     */
    public function fetchPaymentCycleColumnData(array $paymentCycle): ContractPaymentCycleData
    {
        $month = (int) data_get($paymentCycle, 'month');
        $dayOfWeek = (int) data_get($paymentCycle, 'dayOfWeek');
        $dayOfMonth = (int) data_get($paymentCycle, 'dayOfMonth');

        return new ContractPaymentCycleData(compact('month', 'dayOfWeek', 'dayOfMonth') + $paymentCycle);
    }

    /**
     * 取得合約通知欄位
     *
     * @param array $notify
     *
     * @return \App\Support\Data\ContractNotifyData
     */
    public function fetchContractNotifyColumnData(array $notify): ContractNotifyData
    {
        return new ContractNotifyData($this->uuidColumn() + $notify);
    }

    /**
     * 取得裝潢程度欄位
     *
     * @param array $decoration
     *
     * @return \App\Support\Data\DecorationData
     */
    public function fetchDecorationColumnData(array $decoration): DecorationData
    {
        return new DecorationData($decoration);
    }

    /**
     * 取得費用欄位
     *
     * @param array $fees
     *
     * @return \App\Support\Data\FeesData
     */
    public function fetchFeesColumnData(array $fees): FeesData
    {
        return new FeesData(array_map('intval', $fees));
    }

    /**
     * 取得附加車位欄位資料
     *
     * @param array $carpark
     *
     * @return \App\Support\Data\AttachedCarparkData
     */
    public function fetchCarparkColumnData(array $carpark): AttachedCarparkData
    {
        $price = (int) data_get($carpark, 'price');
        return new AttachedCarparkData(compact('price') + $carpark);
    }

    /**
     * 取得附加設備的欄位資料
     *
     * @param integer $crmEquipmentId 設備 ID
     *
     * @return \App\Support\Data\AttachedEquipmentData
     */
    public function fetchEquipmentColumnData(int $crmEquipmentId): AttachedEquipmentData
    {
        return new AttachedEquipmentData(compact('crmEquipmentId'));
    }

    /**
     * 取得合約帳單欄位
     *
     * @param \Illuminate\Http\Request $billData
     *
     * @return \App\Support\Data\ContractBillData
     */
    public function fetchContractBillColumnData(Request $billData): ContractBillData
    {
        $includeTax = $billData->integer('includeTax');
        $paid = $billData->integer('paid');

        return new ContractBillData($this->uuidColumn() + compact('includeTax', 'paid') + $billData->all());
    }

    /**
     * 取得帳單金額欄位
     *
     * @param array $billAmount
     *
     * @return \App\Support\Data\BillAmountData
     */
    public function fetchBillAmountColumnData(array $billAmount): BillAmountData
    {
        $price = (int) data_get($billAmount, 'price');
        return new BillAmountData(compact('price') + $billAmount);
    }

    /**
     * 取得合約銀行帳戶欄位
     *
     * @param array $bankRequest
     *
     * @return \App\Support\Data\ContractBankData
     */
    public function fetchContractBankColumnData(array $bankRequest): ContractBankData
    {
        return new ContractBankData($bankRequest);
    }
}
