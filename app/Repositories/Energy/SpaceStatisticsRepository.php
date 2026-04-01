<?php

declare(strict_types=1);

namespace App\Repositories\Energy;

use Illuminate\Support\Collection;
use App\Models\SpaceEnergyStatistics;

class SpaceStatisticsRepository
{
    /**
     * @param  string  $spaceId
     * @param  string  $numberId
     *
     * @return Collection
     */
    public function findByNumberId(string $spaceId, string $numberId): Collection
    {
        return SpaceEnergyStatistics::where('space_id', $spaceId)
            ->where('fee_number_id', $numberId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @param  array  $data
     *
     * @return bool|null
     */
    public function insert(array $data): ?bool
    {
        return SpaceEnergyStatistics::insert($data);
    }

    /**
     * @param  array  $data
     *
     * @return int
     */
    public function upsert(array $data): int
    {
        return SpaceEnergyStatistics::upsert($data, ['id']);
    }
}
