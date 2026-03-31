<?php

declare(strict_types=1);

namespace App\Repositories\Equipment;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\CrmEquipment;

class CrmEquipmentRepository
{
    use \App\Support\Trait\Paginate\PaginateTrait;

    /**
     * @param $id
     *
     * @return CrmEquipment|null
     */
    public function findById($id): ?CrmEquipment
    {
        return CrmEquipment::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->where('id', $id)
            ->with([
                'crmBuildingSpace' => function (Builder|BelongsTo $query) {
                    $query->whereCompanyId(crm('company_id'))
                        ->where('comid', crm('community_id'));
                },
                'crmEquipmentUploadRecord' => function (Builder|HasMany $query) use ($id) {
                    $query->where('crm_equipment_id', $id)
                        ->whereCompanyId(crm('company_id'))
                        ->where('comid', crm('community_id'))
                        ->withWhereHas('avatarFile');
                },
                'crmEquipmentScrap' => function (Builder|BelongsTo $query) use ($id) {
                    $query->where('crm_equipment_id', $id);
                }
            ])
            ->first();
    }

    /**
     * @param $id
     *
     * @return CrmEquipment|null
     */
    public function findByReportRepair($id): ?CrmEquipment
    {
        return CrmEquipment::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->where('id', $id)
            ->whereHas('crmBuildingSpace', fn ($query) => $query->whereNull('deleted_at')
            )
            ->with([
                'crmBuildingSpace' => $this->commonConditions(crm('company_id'), crm('community_id')),
                'crmTypeName'      => $this->commonConditions(crm('company_id'), crm('community_id')),
                'crmSystemName'    => $this->commonConditions(crm('company_id'), crm('community_id')),
            ])
            ->first();
    }

    /**
     * @param $id
     *
     * @return CrmEquipment|null
     */
    public function findByPublicReportRepair($id): ?CrmEquipment
    {
        return CrmEquipment::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->where('id', $id)
            ->whereHas('crmBuildingCommonSpace', fn ($query) => $query->where('comid', crm('community_id')),
            )
            ->with([
                'crmBuildingCommonSpace' , fn ($query) => $query->where('comid', crm('community_id')),
                'crmTypeName'      => $this->commonConditions(crm('company_id'), crm('community_id')),
                'crmSystemName'    => $this->commonConditions(crm('company_id'), crm('community_id')),
            ])
            ->first();
    }

    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return CrmEquipment::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->with('crmBuildingSpace', function (Builder|belongsTo $crmBuildingSpace): void {
                $crmBuildingSpace
                    ->whereCompanyId(crm('company_id'))
                    ->whereComid(crm('community_id'));
            })
            ->get();
    }

    /**
     * @return Collection
     */
    public function groupBySystem(): Collection
    {
        return CrmEquipment::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->select('system_name')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('system_name')
            ->orderByDesc('count')
            ->get();
    }

    /**
     * @param  array  $ids
     *
     * @return Collection
     */
    public function findByIds(array $ids): Collection
    {
        return CrmEquipment::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->whereIn('id', $ids)
            ->get();
    }

    /**
     * @param  array  $data
     *
     * @return Collection
     */
    public function findByExcel(array $data): Collection
    {
        return CrmEquipment::where('company_id', $data['company_id'])
            ->where('comid', $data['comid'])
            ->whereBetween('created_at', [$data['start_time'], $data['end_time']])
            ->get();
    }

    /**
     * @param  string  $spaceId
     *
     * @return Collection
     */
    public function findBySpaceId(string $spaceId): Collection
    {
        return CrmEquipment::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->where('space_id', $spaceId)
            ->get();
    }

    public function findByGroupPaginate(array $data): Collection
    {
        return CrmEquipment::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->where(Arr::only($data, ['area', 'location', 'space', 'public_type']))
            ->when(!empty(Arr::only($data, ['brand', 'model'])), function ($query) use ($data) {
                foreach (Arr::only($data, ['brand', 'model']) as $key => $value) {
                    $query->whereLike($key, $value);
                }
            })
            ->whereHas('crmTypeName', function ($query) use ($data) {
                $query->where('company_id', crm('company_id'))
                    ->where('comid', crm('community_id'))
                    ->when($data['type_name'] ?? null, function ($q, $name) {
                        $q->where('name', 'LIKE', "%{$name}%");
                    });
            })
            ->whereHas('crmSystemName', function ($query) use ($data) {
                $query->where('company_id', crm('company_id'))
                    ->where('comid', crm('community_id'))
                    ->when($data['system_name'] ?? null, function ($q, $name) {
                        $q->where('name', 'LIKE', "%{$name}%");
                    });
            })
            ->when($data['name'] ?? null, fn($q, $name) => $q->where('name', 'LIKE', "%{$name}%"))
            ->groupBy([
                'name',
                'type_name',
                'system_name',
                'brand',
                'model',
                'area',
                'space',
                'location',
                'public_type'
            ])
            ->selectRaw('DISTINCT MIN(id) as id, name, type_name, system_name, brand, model, area, space, location, public_type')
            ->get();
    }

    /**
     * @param  array  $data
     *
     * @return CrmEquipment|null
     */
    public function insert(array $data): ?CrmEquipment
    {
        return CrmEquipment::create($data);
    }

    /**
     * @param  array  $data
     *
     * @return bool
     */
    public function insertBatch(array $data): bool
    {
        return CrmEquipment::insert($data);
    }

    /**
     * @param string $id
     * @param array $updateData
     * @return bool
     */
    public function update(string $id, array $updateData): bool
    {
        return CrmEquipment::find($id)->update($updateData);
    }

    /**
     * @param  array  $ids
     * @param  array  $updateData
     *
     * @return int
     */
    public function batchUpdate(array $ids, array $updateData): int
    {
        return CrmEquipment::whereIn('id', $ids)->update($updateData);
    }

    /**
     *
     * @param array $ids
     *
     * @return int
     */
    public function destroy(array $ids): int
    {
        return CrmEquipment::destroy($ids);
    }

    /**
     * @param  int  $companyId
     * @param  int  $communityId
     *
     * @return int
     */
    public function forceDelete(int $companyId, int $communityId): int
    {
        return CrmEquipment::where('company_id', $companyId)
            ->where('comid', $communityId)
            ->forceDelete();
    }

    /**
     * @param  array  $ids
     *
     * @return int
     */
    public function forceDeleteIds(array $ids): int
    {
        return CrmEquipment::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->whereIn('id', $ids)
            ->forceDelete();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return CrmEquipment::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->count();
    }

    /**
     * @param  array  $data
     * @param  string|null  $spaceId
     *
     * @return LengthAwarePaginator|null
     */
    public function crmEquipmentPage(array $data, string $spaceId = null): ?LengthAwarePaginator
    {
        $filters = Arr::only($data, [ 'area', 'location', 'space', 'public_type']);
        $buildingFilters = Arr::only($data, ['district_name', 'staircase_name', 'floor_name', 'building_name', 'household_name']);

        $query = CrmEquipment::whereCompanyId(crm('company_id'))
            ->where('comid', crm('community_id'))
            ->when($spaceId, fn ($q, $spaceId) => $q->where('space_id', $spaceId))
            ->when($filters, function ($query, $filters) {
                foreach ($filters as $key => $value) {
                    $query->where($key, 'like', "%{$value}%");
                }
            })
            ->when(isset($data['status']) && in_array($data['status'], ['0', '1']),
                fn ($q) => $q->where('status', $data['status'])->doesntHave('crmEquipmentScrap'))
            ->when(isset($data['status']) && $data['status'] === '2', fn ($q) => $q->withWhereHas('crmEquipmentScrap'))
            ->when($data['name'] ?? null, fn ($q, $name) => $q->where('name', 'like', "%{$name}%"))
            ->when($buildingFilters, function ($query) use ($buildingFilters, $spaceId) {
                $query->withWhereHas('crmBuildingSpace', function ($q) use ($buildingFilters, $spaceId) {
                    $q->when($spaceId, fn ($q, $spaceId) => $q->whereCompanyId(crm('company_id'))
                        ->where('comid', crm('community_id'))
                        ->where('space_id', $spaceId))
                        ->where($buildingFilters)
                        ->whereNull('deleted_at');
                });
            })->when(isset($data['updated_at']), function ($query) use ($data) {
                $startDate = Carbon::parse($data['updated_at'])->startOfDay();
                $endDate = Carbon::parse($data['updated_at'])->endOfDay();
                $query->whereBetween('updated_at', [$startDate, $endDate]);
            })->when(isset($data['type_name']), function ($query) use ($data) {
                $query->withWhereHas('crmTypeName', function ($q) use ($data) {
                    $q->where('id', $data['type_name']);
                });
            })->when(isset($data['system_name']), function ($query) use ($data) {
                $query->withWhereHas('crmSystemName', function ($q) use ($data) {
                    $q->where('id', $data['system_name']);
                });
            });


        $query->with([
            'crmBuildingSpace' => fn ($q) => $q->whereCompanyId(crm('company_id'))
                ->where('comid', crm('community_id'))
                ->when($spaceId, fn ($q, $spaceId) => $q->where('space_id', $spaceId))
                ->whereNull('deleted_at'),
            'crmTypeName' => fn ($q) => $q->whereCompanyId(crm('company_id'))
                ->where('comid', crm('community_id')),
            'crmSystemName' => fn ($q) => $q->whereCompanyId(crm('company_id'))
                ->where('comid', crm('community_id'))
        ]);

        return $query->paginate($this->paginateLimit());
    }

    /**
     * 元件列表選單
     *
     * @return Collection|null
     */
    public function crmEquipmentSelected(): ?Collection
    {
        $companyId = crm('company_id');
        $communityId = crm('community_id');

        $query = CrmEquipment::where('company_id', $companyId)
            ->where('comid', $communityId)
            ->with([
                'crmBuildingSpace' => fn ($query) => $query->where('company_id', $companyId)
                    ->where('comid', $communityId)
                    ->whereNull('deleted_at'),
                'crmTypeName'      => $this->commonConditions($companyId, $communityId),
                'crmSystemName'    => $this->commonConditions($companyId, $communityId),
            ]);

        return $query->groupBy('space_id', 'type_name', 'system_name', 'area', 'space', 'location')
            ->select(['space_id', 'area', 'space', 'location', 'system_name', 'type_name'])
            ->get();
    }

    /**
     * @param  int  $companyId
     * @param  int  $communityId
     * @param  string  $spaceId
     *
     * @return Collection|null
     */
    public function findPropertyEquipment(int $companyId, int $communityId, string $spaceId): ?Collection
    {
        return CrmEquipment::where('company_id', $companyId)
            ->where('comid', $communityId)
            ->where('space_id', $spaceId)
            ->whereHas(
                'crmBuildingSpace',
                fn ($query) => $query
                    ->where('space_id', $spaceId)
                    ->whereNull('deleted_at')
            )
            ->with([
                'crmBuildingSpace' => fn ($query) => $query->whereNull('deleted_at'),
                'crmTypeName'      => $this->commonConditions($companyId, $communityId),
                'crmSystemName'    => $this->commonConditions($companyId, $communityId),
            ])->get();
    }

    /**
     * @param  int  $companyId
     * @param  int  $communityId
     *
     * @return \Closure
     */
    private function commonConditions(int $companyId, int $communityId): \Closure
    {
        return fn ($query) => $query->where('company_id', $companyId)->where('comid', $communityId);
    }

    /**
     * @param  string  $spaceId
     *
     * @return Collection|null
     */
    public function fetchEquipmentBySpace (string $spaceId): ?Collection
    {
        return CrmEquipment::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->where('space_id', $spaceId)
            ->with([
                'crmEquipmentScrap',
                'crmEquipmentComponent',
                'crmTypeName' => fn ($query) => $query->whereCompanyId(crm('company_id')),
                'crmSystemName' => fn ($query) => $query->whereCompanyId(crm('company_id')),
            ])
            ->get();
    }

    /**
     * @param  array  $data
     *
     * @return int
     */
    public function upsert(array $data): int
    {
        return CrmEquipment::upsert($data, ['id']);
    }

    /**
     * @param  array  $data
     *
     * @return Collection|null
     */
    public function fetchEquipmentByConditions(array $data): ?Collection
    {
        return CrmEquipment::where($data)->get();
    }
}