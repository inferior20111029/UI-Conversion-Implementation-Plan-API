<?php

declare(strict_types=1);

namespace App\Services\Selected;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmParkingSpaceRepository;
use App\Repositories\Space\CrmParkingSpaceSelectRepository;

use Illuminate\Support\Collection;

final class ParkingSpaceConfigurationService extends Service
{
    public function __construct(
        private readonly CrmParkingSpaceRepository $crmParkingSpaceRepository,
        private readonly CrmParkingSpaceSelectRepository $crmParkingSpaceSelectRepository,
    ) {
    }

    /**
     * @return array
     */
    public function execute(): array
    {
        $selects = $this->crmParkingSpaceRepository->getParkingSpacesSelect();

        $selectsGrouped = $selects->groupBy('car_type');
        $allocated = $selects->whereNotNull('space_id');

        $calculation = [
            'total' => $selects->count(),
            'car'   => $selectsGrouped->get(1, collect())->count() + $selectsGrouped->get(2, collect())->count(),
            'motorcycle'        => $selectsGrouped->get(0, collect())->count(),
            'allocated_parking' => $allocated->count(),
            'allocated_parking_car'        => $allocated->whereIn('car_type', [1, 2])->count(),
            'allocated_parking_motorcycle' => $allocated->where('car_type', 0)->count(),
        ];

        $crmParkingSpace = $this->crmParkingSpaceSelectRepository->findAll()
            ->groupBy('type')
            ->map(fn ($items) => $items->pluck('value')->toArray())
            ->only(['parking_attribute', 'parking_type', 'use_direction', 'car_size'])
            ->toArray();

        return [...$calculation, ...$this->extractColumns($selects), ...$crmParkingSpace];
    }

    /**
     * @param  Collection  $selects
     *
     * @return array
     */
    private function extractColumns(Collection $selects): array
    {
        $columns = [
            'building_name',
            'district_name',
            'staircase_name',
            'floor_name',
            'household_name',
        ];

        $mapped = $selects->map(fn ($item) => $item->CrmBuildingSpaceForCar->only($columns));

        return collect($columns)->mapWithKeys(fn ($column) => [
            $column => $this->uniqueFilter(
                $mapped->pluck($column)->filter()->values()
            )->toArray(),
        ])->toArray();
    }

    /**
     *
     * @param Collection $collection
     * @return Collection
     */
    private function uniqueFilter(Collection $collection): Collection
    {
        return $collection->unique()->values();
    }
}
