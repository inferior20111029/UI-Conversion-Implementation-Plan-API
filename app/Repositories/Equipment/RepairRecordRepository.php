<?php

declare(strict_types=1);

namespace App\Repositories\Equipment;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

use App\Models\RepairRecord;

class RepairRecordRepository
{
    use \App\Support\Trait\Paginate\PaginateTrait;

    public function findAll(): Collection
    {
        return RepairRecord::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->get();
    }

    public function findById($id): RepairRecord
    {
        return RepairRecord::where('comid', crm('community_id'))
            ->where('id', $id)
            ->with([
                'repairRecordFile.avatarFile',
                'equipment'  => fn ($query) => $query->whereCompanyId(crm('company_id'))
                    ->where('comid', crm('community_id')),
            ])
            ->first();
    }

    public function findBySpaceId($data): Collection
    {
        return RepairRecord::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->where('space_id', $data['space_id'])
            ->with([
                'avatarFile',
                'equipment' => fn ($query) => $query->whereCompanyId(crm('company_id'))
                    ->where('comid', crm('community_id'))
                    ->where('space_id', $data['space_id']),
            ])
            ->get();
    }

    /**
     * @param  array  $data
     *
     * @return bool|null
     */
    public function insert(array $data): ?bool
    {
        return RepairRecord::insert($data);
    }

    /**
     * @param  array  $data
     *
     * @return int
     */
    public function upsert(array $data): int
    {
        return RepairRecord::upsert($data, ['id']);
    }

    /**
     * @param $id
     * @param $repairId
     *
     * @return int|null
     */
    public function updateRepair(int $id, int $repairId): ?int
    {
        return RepairRecord::whereId($id)
            ->update(['repair_id' => $repairId]);
    }

    /**
     * @param  array  $data
     *
     * @return RepairRecord|null
     */
    public function create(array $data): ?RepairRecord
    {
        return RepairRecord::create($data);
    }

    /**
     * @param  array  $ids
     *
     * @return int
     */
    public function destroy(array $ids): int
    {
        return RepairRecord::destroy($ids);
    }

    /**
     * @param  string  $spaceId
     * @param  array  $data
     *
     * @return mixed
     */
    public function page(string $spaceId, array $data)
    {
        $companyId   = crm('company_id');
        $communityId = crm('community_id');
        $space       = $data['space'] ?? null;
        $description = $data['description'] ?? null;
        $equipmentName = $data['equipment_name'] ?? null;
        $status     = $data['status'] ?? null;

        return RepairRecord::where('space_id', $spaceId)
            ->where('comid', $communityId)
            ->when($space, fn ($query) =>  $query->where('space', 'LIKE', "%{$space}%"))
            ->when($description, fn ($query) => $query->where('description', 'LIKE', "%{$description}%"))
            ->withWhereHas('rscPost', fn ($query) => $query->whereCompanyId($companyId)
                ->where('comid', $communityId)
                ->when(!empty($status) , fn ($query) => $query->whereIn('f_status',$status))
            )
            ->with([
                'equipment' => fn ($query) => $query->whereCompanyId($companyId)
                    ->where('comid', $communityId)
                    ->where('space_id', $spaceId),
                'rscPost' => fn ($query) => $query->whereCompanyId($companyId)
                    ->where('comid', $communityId)
                    ->when(!empty($status) , fn ($query) => $query->whereIn('f_status',$status))
            ])
            ->whereHas('equipment', fn($query) => $query->when($equipmentName, fn($query, $name) =>
                    $query->where('name', 'LIKE', "%{$name}%") )
            )
            ->paginate($this->paginateLimit());
    }
}
