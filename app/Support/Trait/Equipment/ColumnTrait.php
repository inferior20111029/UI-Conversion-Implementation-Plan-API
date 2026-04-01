<?php

namespace App\Support\Trait\Equipment;

use App\Support\Tool\File\FileMagic;
use App\Support\Enum\ComponentFilesType;

trait ColumnTrait
{
    /**
     * @param  string  $type
     *
     * @return array
     */
    public function fetchEquipmentColumnData(string $type = 'created'): array
    {
        $data = [
            // 基本資訊
            'name'        => request()->post('name'), // 設備名稱
            'type_name'   => request()->post('type_name'), // 類別名稱
            'system_name' => request()->post('system_name'), // 系統名稱
            'space_id'    => request()->post('space_id'), // 戶別id
            'area'        => request()->post('area'), // 區域
            'space'       => request()->post('space'), // 空間
            'location'    => request()->post('location'), // 位置
            'public_type' => request()->post('public_type'), // 空間屬性(L=大公, S=小公, P=專有)
            'pcces_code'  => request()->post('pcces_code'), // 公共工程編碼
            'ominiclass_code'   => request()->post('ominiclass_code'), // OminiClass編碼
            'user_defined_code' => request()->post('user_defined_code'), // 設備編碼
            'brand'       => request()->post('brand'), // 設備編碼
            'spec_info'   => request()->post('spec_info'), // 細目規格資訊
            'spec'        => request()->post('spec'), // 補充規格資訊
            'size'        => request()->post('size'), // 尺寸
            'weight'      => request()->post('weight'), // 重量
            'place_of_production' => request()->post('place_of_production'), // 產地
            'price'       => request()->post('price'), // 預估成本
            'cost'        => request()->post('cost'), // 取得成本
            'from'        => request()->post('from'), // 取得來源
            'unit'        => request()->post('unit'), // 單位
            'acquisition_date'  => request()->post('acquisition_date'), // 取得日期
            'expiration_date'   => request()->post('expiration_date'), // 保固日期
            'amortization_year' => request()->post('amortization_year'), // 使用年限
            'curing_cycle'      => request()->post('curing_cycle'), // 養護週期
            'warranty'          => request()->post('warranty'), // 保固年限
            // 詳細屬性
            'properties'        => request()->post('properties'), // 詳細屬性
        ];

        if ($type !== 'edit') {
            return $data + [
                'company_id' => crm('company_id'),
                'comid'      => crm('community_id'),
                'poc_id'     => 'property_' . (time() + rand(0, 100000000)),
                'creater'    => crm('username'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return $data + [
            'editor'     => crm('username'),
            'updated_at' => now(),
        ];
    }

    /**
     * @param  int|string  $id
     * @param  array|null  $filesType
     *
     * @return array
     */
    public function fetchEquipmentColumnFileData(int|string $id, array $filesType = null): array
    {
        $componentFileTypes = ComponentFilesType::values();
        $results = [];

        foreach ($componentFileTypes as $componentFile) {
            $fileDatas = $filesType[$componentFile] ?? request($componentFile);

            if (is_array($fileDatas)) {
                $results[$componentFile] = array_map(fn ($fileData) => [
                    'type_name' => $componentFile,
                    'avatar' => $fileData,
                ], array_filter($fileDatas));
            }
        }

        $flatResults = array_reduce($results, fn ($carry, $items) => array_merge($carry, $items), []);

        return array_map(fn ($fileData) => [
            'company_id'       => crm('company_id'),
            'comid'            => crm('community_id'),
            'crm_equipment_id' => $id,
            'type_name'        => $fileData['type_name'],
            'avatar'           => (int) FileMagic::find($fileData['avatar'])->get()?->id,
            'created_at'       => now(),
            'updated_at'       => now(),
        ], $flatResults);
    }

    private function upsertEquipmentComponent(int $id)
    {
        $componentIds = request()->post('component_id');

        if ($componentIds) {
            $componentUpsert = collect(explode(',', $componentIds))
                ->map(fn ($componentId) => [
                    'id'               => (int)$componentId,
                    'crm_equipment_id' => $id,
                ])->all();

            $this->crmEquipmentComponentRepository->upsert($componentUpsert);
        }
    }
}