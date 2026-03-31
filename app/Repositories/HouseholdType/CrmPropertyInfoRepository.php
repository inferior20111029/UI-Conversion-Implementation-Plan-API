<?php

declare(strict_types=1);

namespace App\Repositories\HouseholdType;

use App\Models\CrmPropertyInfoList;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmPropertyInfoRepository
{
    use \App\Support\Trait\Paginate\PaginateTrait;

    /**
     * @param array $data
     * @return int
     */
    public function insertGetId($data): int
    {
        return CrmPropertyInfoList::insertGetId($data);
    }

    /**
     * @param  string  $spaceId
     *
     * @return int
     */
    public function updateSpaceEdit(string $spaceId): int
    {
        return CrmPropertyInfoList::where('space_id', $spaceId)
            ->update(['is_edit' => 0]);
    }

    /**
     * @param  array  $spaceIds
     *
     * @return int
     */
    public function updateSpacesEdit(array $spaceIds): int
    {
        return CrmPropertyInfoList::whereIn('space_id', $spaceIds)
            ->update(['is_edit' => 0]);
    }

    /**
     * @param  array  $data
     *
     * @return int
     */
    public function upsert(array $data): int
    {
        return CrmPropertyInfoList::upsert($data, ['id']);
    }

    /**
     * @param  array  $updateData
     *
     * @return CrmPropertyInfoList|null
     */
    public function updateOrCreate(array $updateData): ?CrmPropertyInfoList
    {
        return CrmPropertyInfoList::updateOrCreate(
            [
                'space_id' => $updateData['space_id'],
                'is_edit'  => 1,
            ],
            [
                'transfer_date' => $updateData['transfer_date'],
                'updated_at'    => now(),
            ],
        );
    }

    /**
     * @param  int  $id
     *
     * @return CrmPropertyInfoList
     */
    public function find(int $id): CrmPropertyInfoList
    {
        return CrmPropertyInfoList::find($id);
    }

    /**
     * @param  array|null  $spaceIds
     *
     * @return Collection
     */
    public function findByEdit(?array $spaceIds = []): Collection
    {
        return CrmPropertyInfoList::where('is_edit', 1)
            ->when($spaceIds !== [], function ($query) use ($spaceIds) {
                $query->whereIn('space_id', $spaceIds);
            })
            ->whereHas('CrmBuildingSpace', function (Builder|BelongsTo $query): void {
                $query
                    ->whereNull('deleted_at')
                    ->has('company')
                    ->has('community');
            })->get();
    }

    /**
     * @param  array  $spaceIds
     *
     * @return int
     */
    public function forceDeleteBySpaceId(array $spaceIds): int
    {
        return CrmPropertyInfoList::whereIn('space_id', $spaceIds)
            ->forceDelete();
    }

    /**
     * @param  string  $spaceId
     *
     * @return Collection
     */
    public function paginateWithSecondRecord(string $spaceId): Collection
    {
        $withTransactionInfo = [
            'crmPropertyTransactionInfo' => function (Builder|HasMany $query) use ($spaceId) {
                $query->where('space_id', $spaceId)
                    ->where('mode', 'inhabitant');
            },
            'crmPropertyTransactionInfo.crmClient'
        ];

        return  CrmPropertyInfoList::where('space_id', $spaceId)
            ->with($withTransactionInfo)
            ->orderByDesc('is_edit')
            ->get();
    }

    /**
     * @param  array  $ids
     *
     * @return int
     */
    public function destroy(array $ids): int
    {
        return CrmPropertyInfoList::destroy($ids);
    }

    public function insertBatch(array $data): bool
    {
        return CrmPropertyInfoList::insert($data);
    }
}