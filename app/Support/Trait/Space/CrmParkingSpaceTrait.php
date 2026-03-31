<?php

declare(strict_types=1);

namespace App\Support\Trait\Space;

use App\Support\Enum\CarType;
use App\Support\Enum\CarApplicationType;

use App\Repositories\Space\CrmParkingSpaceSelectRepository;

trait CrmParkingSpaceTrait
{
    /**
     * @param \App\Models\CrmParkingSpace $item
     * @return array
     */
    public static function transformParkingSpace($item): array
    {
        return [
            "id"                              => $item->id,
            "car_space_id"                    => $item->car_space_id,
            "space_id"                        => $item->space_id,
            "parking_number"                  => $item->parking_number,
            "car_type"                        => $item->car_type,
            "extent_of_ownership"             => $item->extent_of_ownership,
            "extent_of_ownership_numerator"   => $item->extent_of_ownership_numerator,
            "extent_of_ownership_denominator" => $item->extent_of_ownership_denominator,
            "default_extent_of_ownership"     => $item->default_extent_of_ownership,
            "default_extent_of_ownership_numerator"   => $item->default_extent_of_ownership_numerator,
            "default_extent_of_ownership_denominator" => $item->default_extent_of_ownership_denominator,
            "parking_square_meter"  => $item->parking_square_meter,
            "parking_area"          => $item->parking_area,
            "land_square_meter"     => $item->land_square_meter,
            "default_parking_meter" => $item->default_parking_meter,
            "default_parking_area"  => $item->default_parking_area,
            "land_area"             => $item->land_area,
            "parking_type"          => $item->parking_type,
            "parking_size"          => $item->parking_size,
            "parking_attribute"     => $item->parking_attribute,
            "application"           => $item->application,
            "use_direction"         => $item->use_direction,
            "sell_price"            => $item->sell_price,
        ] + self::fetchCrmBuildingSpace($item->CrmBuildingSpaceForCar);
    }

    /**
     * @param \App\Models\CrmBuildingSpace $item
     * @return array
     */
    private static function fetchCrmBuildingSpace($item): array
    {
        return [
            'space_id'          => $item->space_id ?? '',
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
            "block_id"          => $item?->block_id ?? $item?->crmBuildingCommonInfo->block_id ?? '',
            "default_total_area" => $item->crmBuildingCommonInfo->preserved_total_area ?? 0,
            "total_area"         =>  $item->crmBuildingCommonInfo->pre_sale_total_area ?? 0,
            "main_application"  => $item->main_application ?? '',
        ];
    }

    public static function postColumn(): array
    {
        // 車位資訊
        $carInfo = [
            'company_id'        => crm('company_id'),
            'comid'             => crm('community_id'),
            'parking_number'    => request()->post('parking_number'), // 編號
            'space_id'          => (string) request()->post('space_id'), // 空間位置編號
            'application'       => request()->post('application'), // 車位法定名稱
            'parking_attribute' => request()->post('parking_attribute'), // 車位屬性
            'use_direction'     => request()->post('use_direction'), // 使用方式
            'car_type'          => request()->post('car_type'), // 車位種類
            'parking_type'      => request()->post('parking_type'), // 車位類型
            'parking_size'      => request()->post('parking_size'), // 車位尺寸
            'sell_price'        => request()->post('sell_price' , 0), // 車位售價
            'car_space_id'      => request()->post('car_space_id'), // 車位位置
        ];

        // [預售]增設車位面積
        $defaultExtent = [
            'default_extent_of_ownership_numerator'   => request()->post('default_extent_of_ownership_numerator', 0), // 權利範圍(分子)
            'default_extent_of_ownership_denominator' => request()->post('default_extent_of_ownership_denominator', 0) , // 權利範圍(分母)
            'default_parking_meter'                   => request()->post('default_parking_meter', 0), // 車位坪數(平方公尺)
            'default_parking_area'                    => request()->post('default_parking_area', 0), // 車位坪數(坪)
            'default_extent_of_ownership'             => request()->post('default_extent_of_ownership_numerator', 0). '/' . request()->post('default_extent_of_ownership_denominator', 0),
        ];

        // [保存]增設車位面積
        $extent = [
            'extent_of_ownership_numerator'    => request()->post('extent_of_ownership_numerator', 0), // 權利範圍(分子)
            'extent_of_ownership_denominator'  => request()->post('extent_of_ownership_denominator', 0), // 權利範圍(分母)
            'parking_square_meter'             => request()->post('parking_square_meter',0), // 車位坪數(平方公尺)
            'parking_area'                     => request()->post('parking_area', 0), // 車位坪數(坪)
            'extent_of_ownership'              => request()->post('extent_of_ownership_numerator', 0) . '/' . request()->post('extent_of_ownership_denominator',0),
        ];

        // 土地持分
        $land = [
            'land_square_meter' => request()->post('land_square_meter', 0) ,
            'land_area'         => request()->post('land_area', 0),
        ];

        return $carInfo + $defaultExtent + $extent + $land;
    }


    public static function option(): array
    {
        $crmParkingSelectData = (new CrmParkingSpaceSelectRepository())
            ->option()
            ->groupBy('type')
            ->map(function ($item) {
                return $item->pluck('value')->toArray();
            })->toArray();

        $additionalData = [
            'car_type'    => CarType::values(),
            'application' => CarApplicationType::values()
        ];

        $crmParkingSelect = array_merge($crmParkingSelectData, $additionalData);

        return $crmParkingSelect;
    }

    /**
     * @param  string|null  $status
     *
     * @return array|int[]
     */
    private function findApplicationType(?string $status): array
    {
        if (!$status) {
            return [];
        }

        $repairTypes = ['法定停車位', '增設停車位', '獎勵停車位', '殘障定停車位', '訪客貴賓專用車位'];

        $result = array_filter($repairTypes, fn($value) => strpos($value, $status) !== false);

        return $result ? array_keys($result) : [5];
    }
}
