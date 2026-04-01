<?php

declare(strict_types=1);

namespace App\Support\Trait\Equipment;

use Illuminate\Support\Collection;

use App\Support\Tool\File\FileMagic;

use App\Models\CrmEquipment;
use App\Models\CrmBuildingSpace;

use App\Repositories\Space\CrmBuildingSpaceRepository;
use App\Repositories\Equipment\CrmEquipmentCategoryRepository;

trait EquipmentTrait
{
    /**
     * @param  CrmEquipment|null  $item
     *
     * @return array
     */
    public function fetchColumnData(?CrmEquipment $item): array
    {
        $columnData = [
            // 基本資訊
            'id'          => $item->id, // 設備名稱
            'name'        => $item->name, // 設備名稱
            'space_id'    => $item->space_id, // 戶別id
            'type_name'   => $item->crmTypeName?->name, // 類別名稱
            'system_name' => $item->crmSystemName?->name, // 系統名稱
            'area'        => $item->area, // 區域
            'space'       => $item->space, // 空間
            'location'    => $item->location, // 位置
            'public_type' => $item->public_type, // 空間屬性(L=大公, S=小公, P=專有)
            'pcces_code'  => $item->pcces_code, // 公共工程編碼
            'ominiclass_code'   => $item->ominiclass_code, // OminiClass編碼
            'user_defined_code' => $item->user_defined_code, // 設備編碼
            'brand'       => $item->brand, // 設備編碼
            'spec_info'   => $item->spec_info, // 細目規格資訊
            'spec'        => $item->spec, // 補充規格資訊
            'size'        => $item->size, // 尺寸
            'weight'      => $item->weight, // 重量
            'place_of_production' => $item->place_of_production, // 產地
            'price'       => $item->price, // 預估成本
            'cost'        => $item->cost, // 預估成本
            'acquisition_date'  => $item->acquisition_date, // 取得日期
            'expiration_date'   => $item->expiration_date, // 保固日期
            'amortization_year' => $item->amortization_year, // 使用年限
            'curing_cycle'      => $item->curing_cycle, // 養護週期
            'warranty'          => $item->warranty, // 保固年限
            'updated_at'        => $item->updated_at,
            'status'            => !empty($item->crmEquipmentScrap) ? '2' : $item->status ?? 0,
            'is_scrap'          => !empty($item->crmEquipmentScrap),
            // 詳細屬性
            'properties'        => json_decode($item->properties ?? '[]', true), // 詳細屬性
        ] + self::fetchCrmBuildingSpace($item->crmBuildingSpace);

        if (is_null($item->crmEquipmentUploadRecord)) {
            return $columnData;
        }

        if (!is_null($item->crmEquipmentUploadRecord)) {
            return $columnData + self::fetchUploadRecord($item->crmEquipmentUploadRecord);
        }

        return [];
    }

    /**
     * @param  CrmBuildingSpace|null  $item
     *
     * @return array
     */
    private static function fetchCrmBuildingSpace(?CrmBuildingSpace $item): array
    {
        return [
            "district"          => $item->district_name ?? '',
            "district_name"     => $item->district_name  ?? '',
            "district_natsort"  => $item->district_natsort ?? '',
            "building"          => $item->building_name ?? '',
            "building_name"     => $item->building_name ?? '',
            "building_natsort"  => $item->building_natsort ?? '',
            "staircase"         => $item->staircase_name ?? '',
            "staircase_name"    => $item->staircase_name ?? '',
            "staircase_natsort" => $item->staircase_natsort ?? '',
            "floor"             => $item->floor_name ?? '',
            "floor_name"        => $item->floor_name ?? '',
            "floor_natsort"     => $item->floor_natsort ?? '',
            "household"         => $item->household_name ?? '',
            "household_id"      => $item->household_name ?? '',
            "household_name"    => $item->household_name ?? '',
            "household_natsort" => $item->household_natsort ?? '',
            "block_id"          => $item->block_id ?? '',
            "main_application"  => $item->main_application ?? '',
        ];
    }

    /**
     * @param  Collection  $uploadRecord
     *
     * @return array
     */
    private static function fetchUploadRecord(Collection $uploadRecord): array
    {
        return $uploadRecord->map(function ($item) {
            $avatar = $item->avatarFile;
            return [
                'type_name'     => $item?->type_name ?? '',
                'original_name' => $avatar?->original_name ?? '',
                'file_uuid'     => $avatar?->uuid ?? '',
                'id'            => $avatar?->id ?? '',
                'url'           => FileMagic::find($avatar)->url()
            ];
        })->groupBy('type_name')->toArray();
    }

    /**
     * @return array
     */
    public function fetchBuildingSpaces(): array
    {
        $data = (new CrmBuildingSpaceRepository())->findByAll()->map(function ($item) {
            return [
                'space_id'     => $item->space_id,
                'district'     => $item->district_name,
                'building'     => $item->building_name,
                'staircase'    => $item->staircase_name,
                'floor'        => $item->floor_name,
                'household_id' => $item->household_name,
                'public_type'  => $item->public_type,
            ];
        });

        return [
            'district_name'  => $this->getUniqueSorted($data, 'district'),
            'building_name'  => $this->getUniqueSorted($data, 'building'),
            'staircase_name' => $this->getUniqueSorted($data, 'staircase'),
            'floor_name'     => $this->getUniqueSorted($data, 'floor'),
            'household_id'   => $this->getUniqueSorted($data, 'household_id'),
            'origin'         => $data,
        ];
    }

    private function getUniqueSorted($data, $key)
    {
        return $data->pluck($key)
            ->unique()
            ->sort()
            ->filter()
            ->values();
    }

    public function fetchCategory(): array
    {
        $categoryGroups = (new CrmEquipmentCategoryRepository())
            ->findAll()
            ->groupBy('level');

        $levelOneItems = $categoryGroups->get(1);
        if (is_null($levelOneItems)) {
            return [];
        }

        return $levelOneItems->mapWithKeys(function ($item) use ($categoryGroups) {
            return [
                $item->id => [
                    'id' => $item->id,
                    'name' => $item->name,
                    'branch' => $this->getChildBranches($item->id, $categoryGroups),
                ],
            ];
        })->values()->toArray();
    }

    private function getChildBranches($parentId, $categoryGroups): array
    {
        $children = $categoryGroups->get(2)->where('parent', $parentId);
        return $children->isNotEmpty() ? $children->mapWithKeys(function ($child) {
            return [
                $child->id => [
                    'id' => $child->id,
                    'name' => $child->name,
                ],
            ];
        })->values()->toArray() : [];
    }

    /**
     * 取得 單一屬性 空白模板 (category、item)
     *
     * @param $name
     * @param  array  $properties
     * @return array
     */
    private function getNewPropertyV1($name, $properties = []): array
    {
        return [
            'name'       => $name,
            'value'      => '',
            'properties' => $properties,
        ];
    }

    /**
     * @return array
     */
    public function fetchProperties(): array
    {
        $properties400 = [
            '4.01' => $this->getNewPropertyV1('電壓'),
            '4.02' => $this->getNewPropertyV1('電流'),
            '4.03' => $this->getNewPropertyV1('頻率'),
            '4.04' => $this->getNewPropertyV1('功率'),
            '4.05' => $this->getNewPropertyV1('相位'),
            '4.06' => $this->getNewPropertyV1('負載分類'),
            '4.07' => $this->getNewPropertyV1('導管大小'),
            '4.08' => $this->getNewPropertyV1('其他電氣屬性'),
        ];

        $properties500 = [
            '5.01' => $this->getNewPropertyV1('照度'),
            '5.02' => $this->getNewPropertyV1('明度'),
            '5.03' => $this->getNewPropertyV1('光通量'),
            '5.04' => $this->getNewPropertyV1('色溫'),
            '5.05' => $this->getNewPropertyV1('發光強度'),
            '5.06' => $this->getNewPropertyV1('其他照明屬性'),
        ];

        $properties600 = [
            '6.01' => $this->getNewPropertyV1('密度'),
            '6.02' => $this->getNewPropertyV1('氣體摩擦力'),
            '6.03' => $this->getNewPropertyV1('溫度'),
            '6.04' => $this->getNewPropertyV1('風速'),
            '6.05' => $this->getNewPropertyV1('氣體流量'),
            '6.06' => $this->getNewPropertyV1('風管大小'),
            '6.07' => $this->getNewPropertyV1('其他空調屬性'),
        ];

        $properties700 = [
            '7.01' => $this->getNewPropertyV1('液體流量'),
            '7.02' => $this->getNewPropertyV1('液體摩擦力'),
            '7.03' => $this->getNewPropertyV1('速度'),
            '7.04' => $this->getNewPropertyV1('液體壓力'),
            '7.05' => $this->getNewPropertyV1('水管大小'),
            '7.06' => $this->getNewPropertyV1('其他水資源屬性'),
        ];

        return [
            '4.00' => $this->getNewPropertyV1('電氣屬性', $properties400),
            '5.00' => $this->getNewPropertyV1('照明屬性', $properties500),
            '6.00' => $this->getNewPropertyV1('空調屬性', $properties600),
            '7.00' => $this->getNewPropertyV1('水資源屬性', $properties700),
        ];
    }
}
