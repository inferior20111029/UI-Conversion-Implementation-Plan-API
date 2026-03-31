<?php

namespace App\Support\Trait\PropertyRights;

use Illuminate\Http\Request;

use App\Models\CrmBuildingSpace;

use App\Support\Enum\LayoutSetting;

use App\Support\Data\CrmBuildingSpaceData;
use App\Support\Data\CrmBuildingSpaceLayoutData;
use App\Support\Data\CrmBuildingSpacePriceData;
use App\Support\Data\CrmBuildingSpaceStateData;
use App\Support\Data\CrmBuildingSpaceDocumentData;

trait ColumnTrait
{
    /**
     * 取得關聯欄位
     *
     * @param \App\Models\CrmBuildingSpace $spaceData 戶別資料
     * @param string $contractRelationKey
     *
     * @return array
     */
    public function contractColumn(CrmBuildingSpace $spaceData, string $contractRelationKey): array
    {
        return [
            $contractRelationKey => $spaceData->space_id
        ];
    }

    /**
     * 取得戶別 relation key
     *
     * @param string $modelClass
     * @return string
     */
    public function fetchSpaceRelationKey(string $modelClass): string
    {
        return (new $modelClass())->space()->getForeignKeyName();
    }

    /**
     * 取得戶別欄位
     *
     * @param Request $request Request
     *
     * @return CrmBuildingSpaceData
     */
    public function fetchSpaceColumnData(Request $request): CrmBuildingSpaceData
    {
        $crmLayoutSettingId = $request->integer('crmLayoutSettingId');
        return new CrmBuildingSpaceData(compact('crmLayoutSettingId') + $request->all());
    }

    /**
     * 取得文件欄位
     *
     * @param integer $fileId 檔案 ID
     *
     * @return CrmBuildingSpaceDocumentData
     */
    public function fetchDocumentColumnData(int $fileId): CrmBuildingSpaceDocumentData
    {
        return new CrmBuildingSpaceDocumentData([
            'file_id' => $fileId
        ]);
    }

    /**
     * 取得戶別價格欄位
     *
     * @param Request $request Request
     *
     * @return CrmBuildingSpacePriceData
     */
    public function fetchPriceColumnData(Request $request): CrmBuildingSpacePriceData
    {
        return new CrmBuildingSpacePriceData([
            'price' => $request->integer('price'),
            'rentPrice' => $request->integer('rentPrice'),
            'deposit' => $request->integer('deposit')
        ] + $request->all());
    }

    /**
     * 取得戶別格局欄位資料
     *
     * @param string $spaceId 戶別 ID
     * @param string $type 房間定義
     * @param int $quantity 數量
     *
     * @return CrmBuildingSpaceLayoutData
     */
    public function fetchLayoutColumnData(string $spaceId, string $type, int $quantity): CrmBuildingSpaceLayoutData
    {
        return new CrmBuildingSpaceLayoutData(compact('spaceId', 'type', 'quantity'));
    }

    /**
     * 取得房屋概況欄位資料
     *
     * @param array $stateRequest
     *
     * @return CrmBuildingSpaceStateData
     */
    public function fetchStateColumnData(array $stateRequest): CrmBuildingSpaceStateData
    {
        $old = (int) data_get($stateRequest, 'old');
        return new CrmBuildingSpaceStateData(compact('old') + $stateRequest);
    }
}
