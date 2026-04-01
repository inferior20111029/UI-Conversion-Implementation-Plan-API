<?php

declare(strict_types=1);

namespace App\Repositories\Equipment;

use Illuminate\Support\Collection;

use App\Models\RepairRecordFile;

class RepairRecordFileRepository
{
    public function findById(int $repairId): Collection
    {
        return RepairRecordFile::where('repair_id' , $repairId)
            ->with('avatarFile')
            ->get();
    }

    /**
     * @param  array  $data
     *
     * @return bool|null
     */
    public function insert(array $data): ?bool
    {
        return RepairRecordFile::insert($data);
    }

    /**
     * @param  array  $data
     *
     * @return bool|null
     */
    public function destroy(array $data): ?bool
    {
        return RepairRecordFile::where($data)
            ->first()
            ->delete();
    }

    /**
     * @param $id
     *
     * @return int
     */
    public function forceDelete($id): int
    {
        return RepairRecordFile::where('repair_id', $id)
            ->forceDelete();
    }
}
