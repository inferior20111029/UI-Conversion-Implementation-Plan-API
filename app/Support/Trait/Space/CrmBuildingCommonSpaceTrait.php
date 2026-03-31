<?php

namespace App\Support\Trait\Space;

trait CrmBuildingCommonSpaceTrait
{
    /**
     * @param  string  $type
     *
     * @return array[]
     */
    public static function fetchColumnData(string $type = 'created'): array
    {
        $requestData = [
            'locate'                    => request()->post('locate') ?? null, // 坐落
            'doorplate'                 => request()->post('doorplate') ?? null, // 門牌
            'block_id'                  => request()->post('block_id') ?? null, // 建號
            'tax_id'                    => request()->post('tax_id') ?? null, // 稅籍號碼
            'use_license_id'            => request()->post('use_license_id') ?? null, // 使用執照編號
            'main_application'          => request()->post('main_application') ?? null, // 主要用途
            'building_build_licence_id' => request()->post('building_build_licence_id') ?? null, // 建照執照號碼
            'land_use_zoning'           => request()->post('land_use_zoning') ?? null, // 土地使用分區
            'extent_of_ownership'       => request()->post('extent_of_ownership') ?? null, // 權利範圍
            'land_area'                 => request()->post('land_area') ?? request()->post('preserved_total_area') ?? 0, // 土地面積
            'building_area'             => request()->post('building_area') ?? 0, // 建物面積
            'pre_sale_total_area'       => request()->post('pre_sale_total_area') ?? 0, // 預售總建物面積
            'preserved_total_area'      => request()->post('preserved_total_area') ?? 0, // 保存總建物面積
        ];

        $additionalData = ($type !== 'edit')
            ? [
                'company_id' => crm('company_id'),
                'comid'      => crm('community_id'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
            : [
                'updated_at' => now(),
            ];

        return [...$requestData, ...$additionalData];
    }

    /**
     * @return array
     */
    private function fetchSpaceColumnData(): array
    {
        $district  = request()->post('district');
        $building  = request()->post('building');
        $staircase = request()->post('staircase');
        $floor     = request()->post('floor');
        $household = request()->post('household');

        $water     = request()->post('water'); // 水表
        $electric  = request()->post('electric'); // 電表


        $infoData = [
            $this->explodeData($district, 'district') +
                $this->explodeData($building, 'building') +
                $this->explodeData($staircase, 'staircase') +
                $this->explodeData($floor, 'floor') +
                $this->explodeData($household, 'household')
        ];

        return [$water, $electric, $infoData];
    }

    private function updateCommonColumn($item): array
    {
        return [
            'id'                          => $item->id,
            'locate'                      => $item->locate,
            'doorplate'                   => $item->doorplate,
            'tax_id'                      => $item->tax_id,
            'use_license_id'              => $item->use_license_id,
            'main_application'            => $item->main_application,
            'extent_of_ownership'         => $item->extent_of_ownership,
            'land_use_zoning'             => $item->land_use_zoning,
            'block_id'                    => $item->block_id,
            'land_area'                   => $item->land_area,
            'building_area'               => $item->building_area,
            'pre_sale_total_area'         => $item->pre_sale_total_area,
            'preserved_total_area'        => $item->preserved_total_area,
            'building_build_licence_id'   => $item->building_build_licence_id,
            'space'                       => $item->buildingCommonSpace->map(fn ($space) => [
                'space_id'       => $space->space_id,
                'district_name'  => $space->district_name,
                'building_name'  => $space->building_name,
                'staircase_name' => $space->staircase_name,
                'floor_name'     => $space->floor_name,
                'household_name' => $space->household_name,
            ])->toArray(),
        ];
    }
}
