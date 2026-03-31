<?php

declare(strict_types=1);

namespace App\Services\Selected;

use App\Support\Abstract\Service;

use App\Models\CrmEquipment;

use App\Repositories\Equipment\CrmEquipmentRepository;

use Illuminate\Support\Collection;

final class EquipmentListService extends Service
{
    public function __construct(
        private readonly CrmEquipmentRepository $crmEquipmentRepository,
    ) {
    }

    public function execute()
    {
        $crmEquipment = $this->crmEquipmentRepository
            ->crmEquipmentSelected()
            ->map(fn ($item) => $this->fetchColumnData($item));

        return $this->extractColumns($crmEquipment);
    }

    /**
     * Extracts specific columns from the CRM equipment collection.
     *
     * @param Collection $crmEquipment
     *
     * @return array
     */
    private function extractColumns($crmEquipment): array
    {
        $columns = [
            'area', 'space', 'location', 'district_name', 'building_name',
            'staircase_name', 'floor_name', 'household_name', 'type_name', 'system_name'
        ];

        return array_combine(
            $columns,
            array_map(fn ($column) => $this->uniqueFilter($crmEquipment->pluck($column)
                ->filter(fn ($value) => !is_null($value) && $value !== [])->values()), $columns)
        );
    }

    /**
     * Fetches column data for a given CRM equipment item.
     *
     * @param CrmEquipment $item
     *
     * @return array
     */
    private function fetchColumnData($item): array
    {
        return [
            'area'             => $item['area'],
            'space'            => $item['space'],
            'location'         => $item['location'],
            'district_name'    => $item->crmBuildingSpace['district_name'] ?? null,
            'building_name'    => $item->crmBuildingSpace['building_name'] ?? null,
            'staircase_name'   => $item->crmBuildingSpace['staircase_name'] ?? null,
            'floor_name'       => $item->crmBuildingSpace['floor_name'] ?? null,
            'household_name'   => $item->crmBuildingSpace['household_name'] ?? null,
            'type_name'        => $this->filterNullValuesAndEmptyArrays([
                'id'           => $item->crmTypeName['id'] ?? null,
                'name'         => $item->crmTypeName['name'] ?? null,
            ]),
            'system_name'      => $this->filterNullValuesAndEmptyArrays([
                'id'           => $item->crmSystemName['id'] ?? null,
                'name'         => $item->crmSystemName['name'] ?? null,
            ]),
        ];
    }

    /**
     * Filters out null values and empty arrays from an array.
     *
     * @param  array  $array
     *
     * @return array
     */
    private function filterNullValuesAndEmptyArrays(array $array): array
    {
        return array_filter($array, fn ($value) => !is_null($value) && $value !== []);
    }

    /**
     * Filters out duplicates from a collection of arrays.
     *
     * @param $collection
     *
     * @return Collection|null
     */
    private function uniqueFilter($collection): ?Collection
    {
        return $collection->unique()->values();
    }
}
