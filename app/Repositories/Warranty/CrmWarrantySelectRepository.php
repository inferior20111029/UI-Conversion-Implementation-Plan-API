<?php

declare(strict_types=1);

namespace App\Repositories\Warranty;

use App\Models\CrmWarrantySelect;
use Illuminate\Support\Collection;

class CrmWarrantySelectRepository
{
    /**
     * 保固選單資料
     *
     * @param  int  $communityId  社區 ID
     *
     * @return Collection
     */
    public function findAll(int $communityId): Collection
    {
        return CrmWarrantySelect::where('comid', $communityId)
            ->get();
    }

    /**
     * 新增保固選單資料
     *
     * @param  array  $data
     *
     * @return bool
     */
    public function insert(array $data): bool
    {
       return CrmWarrantySelect::insert($data);
    }

    /**
     * 更新保固資料
     *
     * @param  array  $data
     *
     * @return int
     */
    public function upsert(array $data): int
    {
        return CrmWarrantySelect::upsert($data, ['id']);
    }

    /**
     *　刪除保固資料
     *
     * @param array $ids
     *
     * @return int
     */
    public function destroy(array $ids): int
    {
        return CrmWarrantySelect::destroy($ids);
    }
}