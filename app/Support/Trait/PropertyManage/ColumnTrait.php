<?php

declare(strict_types=1);

namespace App\Support\Trait\PropertyManage;

use Illuminate\Http\Request;

use App\Models\RenterContract;

use App\Support\Data\FeesData;
use App\Support\Data\DecorationData;
use App\Support\Data\PropertyData;
use App\Support\Data\PropertyContactInfoData;
use App\Support\Data\PropertyContactPersonData;
use App\Support\Data\PropertyDocumentData;
use App\Support\Data\AttachedCarparkData;
use App\Support\Data\AttachedEquipmentData;
use App\Support\Data\RentItemsIncludedData;
use App\Support\Data\NeighborhoodLivabilityData;
use App\Support\Data\NeighborhoodTransportationData;
use App\Support\Data\ItemCheckInData;

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
     * 取得物件 relation key
     *
     * @param string $modelClass
     * @return string
     */
    public function fetchPropertyRelationKey(string $modelClass): string
    {
        return (new $modelClass())->property()->getForeignKeyName();
    }

    /**
     * 取得文件欄位
     *
     * @param  int  $fileId
     * @param  string  $type
     * @param $url
     *
     * @return PropertyDocumentData
     */
    public function fetchDocumentColumnData(int $fileId, string $type, $url = null): PropertyDocumentData
    {
        return new PropertyDocumentData($this->uuidColumn() + compact('fileId', 'type', 'url'));
    }

    /**
     * 取得最短租期欄位
     *
     * @param $values
     *
     * @return ItemCheckInData
     */
    public function fetchCheckInColumnData($values): ItemCheckInData
    {
        $checkInDate             = data_get($values, 'date');
        $minimumPeriod           = (int) data_get($values, 'lease_term');
        $minimumRentalPeriodType = data_get($values, 'lease_term_type');

        return new ItemCheckInData($this->uuidColumn() + compact('checkInDate', 'minimumPeriod', 'minimumRentalPeriodType'));
    }

    /**
     * @param  array  $values
     *
     * @return PropertyContactInfoData
     */
    public function fetchContactInfoColumnData(array $values): PropertyContactInfoData
    {
        return new PropertyContactInfoData($values);
    }

    /**
     * @param  array  $values
     *
     * @return PropertyContactPersonData
     */
    public function fetchContactPersonColumnData(array $values): PropertyContactPersonData
    {
        $type = data_get($values, 'type');
        $name = data_get($values, 'name');

        return new PropertyContactPersonData($this->uuidColumn() + compact('type', 'name'));
    }

    /**
     * 取得物件欄位資料
     *
     * @param \Illuminate\Http\Request $request Request
     *
     * @return \App\Support\Data\PropertyData
     */
    public function fetchContractColumnData(Request $request): PropertyData
    {
        return new PropertyData(
            $this->uuidColumn() +
                $request->all() +
                [
                    'company_id'   => crm('company_id'),
                    'community_id' => crm('community_id'),
                    'enable_state' => (int) $request->state,
                    'creator'      => crm('username')
                ]
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
        $price             = (int) data_get($fees, 'price');
        $unitPrice         = (int) data_get($fees, 'unit_price');
        $deposit           = (int) data_get($fees, 'deposit');
        $depositTotalMonth = (int) data_get($fees, 'depositTotalMonth');
        $managementFee     = (int) data_get($fees, 'managementFee');

        return new FeesData(compact('price', 'unitPrice', 'deposit', 'depositTotalMonth', 'managementFee'));
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
     * @param array $crmEquipmentId
     *
     * @return \App\Support\Data\AttachedEquipmentData
     */
    public function fetchEquipmentColumnData(array $crmEquipment): AttachedEquipmentData
    {
        $crmEquipmentId = (int) data_get($crmEquipment, 'id');
        $displayState   = (int) data_get($crmEquipment, 'display_state');

        return new AttachedEquipmentData(compact('crmEquipmentId', 'displayState'));
    }

    /**
     * 取得附近交通欄位資料
     *
     * @param array $value
     *
     * @return \App\Support\Data\NeighborhoodTransportationData
     */
    public function fetchTransportationColumnData(array $value): NeighborhoodTransportationData
    {
        $neighborhoodTransportationId = (int) data_get($value, 'type');

        return new NeighborhoodTransportationData($value + ['neighborhood_transportation_id' => $neighborhoodTransportationId]);
    }

    /**
     * 取得生活機能欄位資料
     *
     * @param int $value
     *
     * @return \App\Support\Data\NeighborhoodLivabilityData
     */
    public function fetchLivabilityColumnData($neighborhoodLivabilityId): NeighborhoodLivabilityData
    {
         return new NeighborhoodLivabilityData(compact('neighborhoodLivabilityId'));
    }
}
