<?php

namespace App\Support\Trait\Excel;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use App\Support\Enum\CrmHouseType;

trait ConfigurationCommonExportTrait
{
    /**
     * @return array
     */
    private function fetchExtractCommonInfo(): array
    {
        return [
            'comid',
            'company_id',
            'doorplate',
            'tax_id',
            'block_id',
            'locate',
            'extent_of_ownership',
            'building_build_licence_id',
            'use_license_id',
            'main_application',
            'land_use_zoning',
            'pre_sale_total_area',
            'preserved_total_area',
        ];
    }

    /**
     * @param $type
     *
     * @return array
     */
    private function fetchExtractCommonSpace(): array
    {
        return [
            'block_id',
            'comid',
            'space_id',
            'district',
            'district_name',
            'district_natsort',
            'building',
            'building_name',
            'building_natsort',
            'staircase',
            'staircase_name',
            'staircase_natsort',
            'floor',
            'floor_name',
            'floor_natsort',
            'household',
            'household_name',
            'household_natsort',
        ];
    }
}