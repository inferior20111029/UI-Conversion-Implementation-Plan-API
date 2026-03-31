<?php

declare(strict_types=1);

namespace App\Support\Trait\Public;

use App\Support\Tool\File\FileMagic;
use App\Support\Enum\CrmHouseType;

use App\Models\CrmBuildingCommonBaseInfo;
use App\Models\CrmBuildingCommonSpace;

trait ColumnTrait
{
    /**
     * @param  CrmBuildingCommonBaseInfo  $data
     *
     * @return array
     */
    private function transform(CrmBuildingCommonBaseInfo $data)
    {
        return [
            'id'                       => $data->id,
            'space_id'                 => $data->space_id,
            'serial_number'            => $data->serial_number,
            'machine_number'           => $data->machine_number,
            'type'                     => $data->type,
            'introduction'             => $data->introduction,
            'prohibit'                 => $data->prohibit == 1,
            'house_viewing'            => $data->house_viewing,
            'is_file'                  => ($data->management_measures_file !== 0),
            'management_measures_text' => $data->management_measures_text,
            'management_measures_file' => [
                'file_uuid'    => $data->managementMeasuresFile->uuid ?? '',
                'file_name'    => $data->managementMeasuresFile->original_name ?? '',
                'url'          => FileMagic::find($data->managementMeasuresFile) ? FileMagic::find($data->managementMeasuresFile)->url() : ''
            ],
            'picture' => [
                'file_uuid'    => $data->pictureAvatar->uuid ?? '',
                'file_name'    => $data->pictureAvatar->original_name ?? '',
                'url'          => FileMagic::find($data->pictureAvatar) ? FileMagic::find($data->pictureAvatar)->url() : ''
            ],
        ];
    }

    /**
     * @param  CrmBuildingCommonSpace  $data
     *
     * @return array
     */
    private function propertyTransform(CrmBuildingCommonSpace $data): array
    {
        $commonInfo = $data->crmBuildingCommonInfo;

        return [
            'building_common_info_id'  => $commonInfo->id,
            'space_id'                 => $data->space_id,
            'household_name'           => $data->household_name,
            'district_name'            => $data->district_name,
            'building_name'            => $data->building_name,
            'staircase_name'           => $data->staircase_name,
            'floor_name'               => $data->floor_name,
            'block_id'                 => $commonInfo->block_id,
            'doorplate'                => $commonInfo->doorplate,
            'land_use_zoning'          => $commonInfo->land_use_zoning,
            'main_application'         => CrmHouseType::array()[$commonInfo->main_application] ?? null,
            'locate'                   => $commonInfo->locate,
            'extent_of_ownership'      => $commonInfo->extent_of_ownership,
            'building_build_licence_id' => $commonInfo->building_build_licence_id,
            'use_license_id'            => $commonInfo->use_license_id,
            'land_area'                 => $commonInfo->land_area,
            'building_area'             => $commonInfo->building_area,
            'is_edit'                   => $data->crmBuildingCommonBaseInfo !== null,
        ];
    }

    /**
     * @param $request
     *
     * @return array
     */
    private function fetchUpdateColumnData($request): array
    {
        return $this->prepareColumnData($request);
    }

    /**
     * @param $request
     * @param  int  $id
     *
     * @return array
     */
    private function fetchPatchColumnData($request, int $id): array
    {
        return $this->prepareColumnData($request, $id);
    }

    /**
     * @param $request
     * @param  int|null  $id
     *
     * @return array
     */
    private function prepareColumnData($request, int $id = null): array
    {
        $avatar        = $this->findFileId($request->management_measures_file);
        $avatarPicture = $this->findFileId($request->picture);

        $data = [
            ...$request->all(),
            ...['prohibit' => (bool) $request->prohibit],
            ...[
                'management_measures_file' => $avatar,
                'picture'                  => $avatarPicture,
                'updated_at'               => now()
            ]
        ];

        if ($id !== null) {
            $data['id'] = $id;
        } else {
            $data['created_at'] = now();
        }

        return $data;
    }

    /**
     * @param $file
     *
     * @return int|null
     */
    private function findFileId($file): ?int
    {
        return (int) FileMagic::find($file)->get()?->id;
    }

    /**
     * 取得公設列表資料
     *
     * @param $building
     *
     * @return array
     */
    private function fetchPaginateResponse($building): array
    {
        $spaces = $building->buildingCommonSpace->map(fn ($space) => [
            'space_id'       => $space['space_id'],
            'district_name'  => $space['district_name'],
            'building_name'  => $space['building_name'],
            'staircase_name' => $space['staircase_name'],
            'floor_name'     => $space['floor_name'],
            'household_name' => $space['household_name'],
        ]);

        return [
            'block_id'                  => $building?->block_id,
            'building_common_info_id'   => $building?->id,
            'pre_sale_total_area'       => $building?->pre_sale_total_area,
            'pre_sale_total_area_ping'  => $building?->pre_sale_total_area * 0.3025,
            'preserved_total_area'      => $building?->preserved_total_area,
            'preserved_total_area_ping' => $building?->preserved_total_area * 0.3025,
            'spaces'                    => $spaces->toArray(),
        ];
    }
}