<?php

declare(strict_types=1);

namespace App\Services\Selected;

use App\Support\Abstract\Service;
use App\Support\Enum\CrmHouseType;

use App\Repositories\Space\CrmBuildingSpaceRepository;
use App\Repositories\Space\CrmBuildingCommonSpaceRepository;

use Illuminate\Support\Collection;

final class ConfigurationService extends Service
{
    public function __construct(
        private readonly CrmBuildingSpaceRepository $crmBuildingSpaceRepository,
        private readonly CrmBuildingCommonSpaceRepository $crmBuildingCommonSpaceRepository,
    ) {
    }

    /**
     * @param  string  $type
     *
     * @return array
     */
    public function execute(string $type): array
    {

        if ($type == 'privacy') {
            $selects = $this->crmBuildingSpaceRepository->fetchConfigurationSelect();
        } else {
            $selects = $this->crmBuildingCommonSpaceRepository
                ->fetchConfigurationSelect()->map(fn ($item) => [
                    'building_name'  => $item->building_name,
                    'district_name'  => $item->district_name,
                    'staircase_name' => $item->staircase_name,
                    'floor_name'     => $item->floor_name,
                    'household_name' => $item->household_name,
                    'doorplate'      => $item->crmBuildingCommonInfo?->doorplate,
                    'block_id'       => $item->crmBuildingCommonInfo?->block_id,
                ]);
        }

        return $this->extractColumns($selects, $type);
    }

    /**
     *
     * @param Collection $crmEquipment
     * @return array
     */
    private function extractColumns(Collection $selects, string $type): array
    {
        $columns = [
            'building_name',
            'district_name',
            'staircase_name',
            'floor_name',
            'household_name',
            'doorplate',
            'block_id',
        ];

        return array_combine(
            $columns,
            array_map(
                fn ($column) => $this->uniqueFilter($selects->pluck($column)
                    ->filter(fn ($value) => !is_null($value) && $value !== [])->values()),
                $columns
            )
        ) + ['main_application' => self::extractMainApplication($type)];
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

    private function extractMainApplication(string $type)
    {
        $houseTypeKeys = $type === 'privacy'
            ? ['H001', 'H002', 'H003', 'H004', 'H005', 'H006', 'H007']
            : ['H008', 'H009', 'H010', 'H011', 'H012', 'H013', 'H014'];

        return array_intersect_key(
            CrmHouseType::array(),
            array_flip($houseTypeKeys)
        );
    }
}
