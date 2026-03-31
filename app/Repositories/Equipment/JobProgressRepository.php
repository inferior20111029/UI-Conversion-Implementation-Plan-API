<?php

declare(strict_types=1);

namespace App\Repositories\Equipment;

use Illuminate\Support\Collection;

use App\Models\JobProgress;

class JobProgressRepository
{
    public function findAll(): Collection
    {
        return JobProgress::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->get();
    }

    /**
     * @param  array  $data
     *
     * @return bool|null
     */
    public function insert(array $data): ?bool
    {
        return JobProgress::insert($data);
    }

    /**
     * @param  array  $data
     *
     * @return bool|null
     */
    public function destroy(array $data): ?bool
    {
        return JobProgress::where($data)
            ->first()
            ->delete();
    }
}
