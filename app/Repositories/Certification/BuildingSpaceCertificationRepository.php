<?php

declare(strict_types=1);

namespace App\Repositories\Certification;

use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;

use App\Models\BuildingSpaceCertification;

class BuildingSpaceCertificationRepository
{
    /**
     * @param  string  $spaceId
     * @param  string  $type
     *
     * @return Collection
     */
    public function find(string $spaceId, string $type): Collection
    {
        return BuildingSpaceCertification::where('space_id', $spaceId)
            ->where('type', 'like', "%{$type}%")
            ->with('buildingSpaceCertificationFile.file')
            ->get();
    }

    /**
     * @param  array  $spaceIds
     * @param  Carbon  $createdAt
     *
     * @return Collection
     */
    public function findId(array $spaceIds, Carbon $createdAt): Collection
    {
        return BuildingSpaceCertification::whereIn('space_id', $spaceIds)
            ->where('created_at', $createdAt)
            ->get();
    }

    /**
     * @param  array  $data
     *
     * @return BuildingSpaceCertification
     */
    public function insert(array $data): BuildingSpaceCertification
    {
        return BuildingSpaceCertification::create($data);
    }

    /**
     * @param  array  $data
     *
     * @return bool
     */
    public function inserts(array $data): bool
    {
        return BuildingSpaceCertification::insert($data);
    }

    /**
     * @param  array  $data
     *
     * @return int
     */
    public function upsert(array $data): int
    {
        return BuildingSpaceCertification::upsert($data, ['id']);
    }

    /**
     * @param  array  $data
     *
     * @return int|null
     */
    public function count(array $data): ?int
    {
        return isset($data['space_id'], $data['type'])
            ? BuildingSpaceCertification::where('space_id', $data['space_id'])
            ->where('type', $data['type'])
            ->where('type', 'like', '%' . $data['type'] . '%')
            ->count()
            : null;
    }

    /**
     * @param  array  $ids
     *
     * @return int
     */
    public function destroy(array $ids): int
    {
        return BuildingSpaceCertification::destroy($ids);
    }
}
