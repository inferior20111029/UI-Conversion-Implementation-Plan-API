<?php

declare(strict_types=1);

namespace App\Support\Trait\Equipment;

use Illuminate\Support\Collection;

use App\Support\Tool\File\FileMagic;

use App\Models\CrmEquipment;
use App\Models\CrmBuildingSpace;

use App\Repositories\Space\CrmBuildingSpaceRepository;
use App\Repositories\Equipment\CrmEquipmentCategoryRepository;

trait SpaceEquipmentTrait
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
        ] + self::fetchCrmBuildingSpace($item->crmBuildingSpace);


    }

    /**
     * @param  CrmBuildingSpace|null  $item
     *
     * @return array
     */
    private static function fetchCrmBuildingSpace(?CrmBuildingSpace $item): array
    {
        return [
            "district"          => $item->district ?? '',
            "district_name"     => $item->district_name  ?? '',
            "district_natsort"  => $item->district_natsort ?? '',
            "building"          => $item->building ?? '',
            "building_name"     => $item->building_name ?? '',
            "building_natsort"  => $item->building_natsort ?? '',
            "staircase"         => $item->staircase ?? '',
            "staircase_name"    => $item->staircase_name ?? '',
            "staircase_natsort" => $item->staircase_natsort ?? '',
            "floor"             => $item->floor ?? '',
            "floor_name"        => $item->floor_name ?? '',
            "floor_natsort"     => $item->floor_natsort ?? '',
            "household"         => $item->household ?? '',
            "household_name"    => $item->household_name ?? '',
            "household_natsort" => $item->household_natsort ?? '',
            "block_id"          => $item->block_id ?? '',
            "main_application"  => $item->main_application ?? '',
        ];
    }
}
