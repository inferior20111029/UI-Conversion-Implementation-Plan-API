<?php

declare(strict_types=1);

namespace App\Repositories\HiddenColumn;

use App\Models\HiddenColumn;
use Illuminate\Support\Collection;

class HiddenColumnRepository
{
    /**
     * @param  int  $userId
     * @param  int  $companyId
     * @param  string  $key
     *
     * @return Collection|null
     */
    public function findByUserId(int $userId, int $companyId, string $key): ?Collection
    {
        return HiddenColumn::whereCompanyId($companyId)
            ->where('key', $key)
            ->where('user_id', $userId)
            ->get();
    }

    /**
     * @param  array  $data
     *
     * @return bool|null
     */
    public function insert(array $data): ?bool
    {
        return HiddenColumn::insert($data);
    }

    /**
     * @param  array  $data
     *
     * @return int
     */
    public function upsert(array $data): int
    {
        $result = HiddenColumn::updateOrInsert(
            [
                'user_id' => $data['user_id'],
                'key'     => $data['key']
            ],
            $data
        );

        return $result ? 1 : 0;
    }

    /**
     *
     * @param array $ids
     *
     * @return int
     */
    public function forceDelete(array $ids): int
    {
        return HiddenColumn::whereIn('certification_id', $ids)->forceDelete();
    }
}
