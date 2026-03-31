<?php

namespace App\Support\Trait\Space;

use Illuminate\Support\Arr;
use Home\Helpers\Natsort;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

trait CrmBuildingSpaceTrait
{
    public static function updateColumn(array $data, string $type = 'private'): array
    {
        $result = [
            'space_id'          => $data['space_id'],
            'district'          => $data['district'],
            'district_name'     => $data['district_name'],
            'district_natsort'  => $data['district_natsort'],
            'building'          => $data['building'],
            'building_name'     => $data['building_name'],
            'building_natsort'  => $data['building_natsort'],
            'staircase'         => $data['staircase'],
            'staircase_name'    => $data['staircase_name'],
            'staircase_natsort' => $data['staircase_natsort'],
            'floor'             => $data['floor'],
            'floor_name'        => $data['floor_name'],
            'floor_natsort'     => $data['floor_natsort'],
            'household'         => $data['household'],
            'household_name'    => $data['household_name'],
            'household_natsort' => $data['household_natsort'],
            'building_build_licence_id' => $data['building_build_licence_id'] ?? null,

        ] + self::organizeTree($data['crm_house_fee_number']);

        if ($type === 'private') {
            $privateData = [
                'locate'            => $data['locate'],
                'public_type'       => $data['public_type'],
                'doorplate'         => $data['doorplate'],
                'block_id'          => $data['block_id'],
                'tax_id'            => $data['tax_id'],
                'use_license_id'    => $data['use_license_id'],
                'main_application'  => $data['main_application'],
                'house_status'      => $data['house_status'],
                'handover_date'     => $data['handover_date'],
                'warranty_type'     => $data['warranty_type'],
                'land_use_zoning'   => $data['land_use_zoning'],
                'extent_of_ownership' => $data['extent_of_ownership'],
            ];

            $result = [...$result, ...$privateData];
        }

        return $result;
    }

    private static function organizeTree(array $data): array
    {
        $treeData = [];
        $items = [];

        foreach ($data as $feeNumber) {
            $items[$feeNumber['type']][$feeNumber['id']] = [
                'id'       => $feeNumber['id'],
                'type'     => $feeNumber['type'],
                'label'    => $feeNumber['value'],
                'children' => []
            ];
        }

        foreach ($data as $feeNumber) {
            if (!is_null($feeNumber['parent_id']) && isset($items[$feeNumber['type']][$feeNumber['parent_id']])) {
                $items[$feeNumber['type']][$feeNumber['parent_id']]['children'][] = &$items[$feeNumber['type']][$feeNumber['id']];
            } else {
                $treeData[$feeNumber['type']][$feeNumber['id']] = &$items[$feeNumber['type']][$feeNumber['id']];
            }
        }

        return $treeData;
    }

    public static function fetchFeeNumber(array $feeNumber, array $data, string $type): array
    {
        return array_map(
            fn (array $item): array => $feeNumber + [
                'type'     => $type,
                'value'    => $item['value'],
                'children' => $item['children'],
                'space_id' => $item['space_id'] ?? '',
            ],
            $data
        );
    }

    /**
     * @param array $feeNumber
     * @param array $data
     * @param string $type
     * @param string $key
     * @return array
     * @throws \Exception
     */
    public static function fetchFeeChildrenNumber(array $feeNumber, array $data, string $type, string $key): array
    {
        return array_map(
            fn (string $item): array => $feeNumber + [
                'id'        => str()->uuid()->toString(),
                'type'      => $type,
                'value'     => $item,
                'parent_id' => $key
            ],
            $data
        );
    }

    /**
     * @param $data
     * @param $type
     * @return array|null[]
     */
    public static function explodeData($data, $type)
    {
        if ($data !== null) {
            $explode = explode(',', $data);
            return [
                $type                   => $explode[0],
                $type . '_' . 'name'    => $explode[1],
                $type . '_' . 'natsort' => Natsort::natsort_canon($explode[1]),
            ];
        }

        return [
            $type                   => $data,
            $type . '_' . 'name'    => $data,
            $type . '_' . 'natsort' => $data,
        ];
    }

    /**
     * 新增水電錶資訊
     *
     * @param string $spaceId
     * @param array $data
     * @param string $type
     * @return bool
     * @throws \Exception
     */
    private function createCrmHouseFeeNumber(string $spaceId, array $data, string $type): bool
    {
        $feeNumber = [
            'company_id' => crm('company_id'),
            'comid'      => crm('community_id'),
            'space_id'   => $spaceId
        ];

        $parent = $this->fetchFeeNumber($feeNumber, $data, $type);
        $children = [];

        foreach ($parent as $item) {
            $id = $this->crmHouseFeeNumberRepository->create($item)->id;
            $children[$id] = $item['children'];
        }

        $childrenData = [];
        foreach ($children as $key => $item) {
            $childrenData[] = $this->fetchFeeChildrenNumber($feeNumber, $item, $type, (string)$key);
        }

        return $this->crmHouseFeeNumberRepository->insert(Arr::collapse($childrenData));
    }

    /**
     * @param  Collection  $fetchOptionGroupBy
     * @param  string  $key
     * @param $value
     *
     * @return array|null
     */
    private function mapConfiguration(Collection $fetchOptionGroupBy, string $key, $value)
    {
        $data = Arr::get($fetchOptionGroupBy, $key, collect())->firstWhere('configuration_name', $value);

        if (!$data) {
            return null;
        }

        $key = $key == in_array($key, ['privacy', 'public']) ? 'household' : $key;

        return [
            $key              => $data['configuration_value'],
            $key . '_name'    => $data['configuration_name'],
            $key . '_natsort' => $data['configuration_natsort'],
        ];
    }

    private function removeKeys(array $array, array $keys): array
    {
        return array_diff_key($array, array_flip($keys));
    }


    /**
     * @param  array  $items
     * @param  string  $spaceId
     *
     * @return array
     */
    private function processItems(array $items, string $spaceId): array
    {
        $processedItems = [];

        foreach ($items as $item) {
            $itemId = $this->ensureUuid($item['id']);
            $processedItems[] = $this->formatItem($item, $spaceId, null, $itemId);

            if (!empty($item['children']) && is_array($item['children'])) {
                foreach ($item['children'] as $child) {
                    $processedItems[] = $this->formatItem($child, $spaceId, $itemId);
                }
            }
        }

        return $processedItems;
    }

    /**
     * @param  array  $item
     * @param  string  $spaceId
     * @param  string|null  $parentId
     * @param  string|null  $itemId
     *
     * @return array
     */
    private function formatItem(array $item, string $spaceId, ?string $parentId = null, ?string $itemId = null): array
    {
        return [
            'id'         => $itemId ?? self::ensureUuid($item['id']),
            'space_id'   => $spaceId,
            'company_id' => crm('company_id'),
            'comid'      => crm('community_id'),
            'type'       => $item['type'],
            'value'      => $item['label'],
            'parent_id'  => $parentId,
        ];
    }

    /**
     * @param  string  $id
     *
     * @return string
     */
    private function ensureUuid(string $id): string
    {
        return Str::isUuid($id) ? $id : str()->uuid()->toString();
    }
}