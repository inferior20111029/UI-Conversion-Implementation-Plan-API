<?php

namespace App\Support\Trait\EquipmentGroup;

trait ColumnTrait
{
    /**
     * 取得設備群組檢視資料
     *
     * @return array
     */
    public function fetchShowColumnData($item): array
    {
        return [
            'id'          => $item->id, // 設備id
            'name'        => $item->name, // 設備名稱
            'type_name'   => $item->crmTypeName->name ?? '', // 類別名稱
            'system_name' => $item->crmSystemName->name ?? '', // 系統名稱
            'brand'       => $item->brand, // 品牌
            'model'       => $item->model, // 型號
            'area'        => $item->area, // 區域
            'space'       => $item->space, // 空間
            'location'    => $item->location, // 位置
            'public_type' => $item->public_type, // 空間屬性(L=大公, S=小公, P=專有)
        ];
    }
}
