<?php

declare(strict_types=1);

namespace App\Support\Trait\Role;

trait ColumnTrait
{
    /**
     * @param  string  $type
     * @param  array  $configurationType
     *
     * @return void
     */
    public function updateCrmBuildingSpace(string $type, array $configurationType): void
    {
        $now = now();

        $crmBuildingSpace = $this->crmBuildingSpaceRepository->findByAll()->map(function ($item) use ($type, $configurationType, $now) {
            $itemArray = $item->toArray();
            $nameKey = $type . '_name';

            return [
                ...$itemArray,
                'space_id'   => $itemArray['space_id'] ?? null,
                $type        => $itemArray[$type] ?? null,
                $nameKey     => $configurationType[$itemArray[$type]] ?? null,
                'updated_at' =>  $now,
            ];
        });

        $this->crmBuildingSpaceRepository->upsert($crmBuildingSpace->toArray());
    }

    /**
     * @param  string  $type
     * @param  array  $configurationType
     *
     * @return void
     */
    public function updateBuildingCommonSpace(string $type, array $configurationType): void
    {
        $now = now();

        $crmBuildingSpace = $this->crmBuildingCommonSpaceRepository->findAll()->map(function ($item) use ($type, $configurationType, $now) {
            $itemArray = $item->toArray();
            $nameKey = $type . '_name';

            return [
                ...$itemArray,
                'space_id'   => $itemArray['space_id'] ?? null,
                $type        => $itemArray[$type] ?? null,
                $nameKey     => $configurationType[$itemArray[$type]] ?? null,
                'created_at' =>  $now,
                'updated_at' =>  $now,
            ];
        });

        $this->crmBuildingCommonSpaceRepository->upsert($crmBuildingSpace->toArray());
    }
}
