<?php

declare(strict_types=1);

namespace App\Repositories\Space;

use Illuminate\Support\Collection;
use App\Models\CrmBuildingCommonInfo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrmBuildingCommonInfoRepository
{
    use \App\Support\Trait\Paginate\PaginateTrait;

    /**
     * @param array $data
     * @return CrmBuildingCommonInfo
     */
    public function insert(array $data): CrmBuildingCommonInfo
    {
        return CrmBuildingCommonInfo::create($data);
    }

    /**
     * @param  array  $data
     *
     * @return int
     */
    public function upsert(array $data): int
    {
        return CrmBuildingCommonInfo::upsert($data, ['id']);
    }

    /**
     * @param  int  $id
     *
     * @return mixed
     */
    public function findById(int $id)
    {
        return CrmBuildingCommonInfo::whereCompanyId(crm('company_id'))
            ->where('comid', crm('community_id'))
            ->where('id', $id)
            ->with([
                'buildingCommonSpace' => function (Builder|HasMany $query): Builder|HasMany {
                    return $query->where('comid', crm('community_id'));
                }
            ])
            ->get();
    }

    /**
     * @param  array  $blockId
     *
     * @return Collection|null
     */
    public function findByExcelUpdate(array $blockId): ?Collection
    {
        $companyId = crm('company_id');
        $communityId = crm('community_id');

        return CrmBuildingCommonInfo::where('company_id', $companyId)
            ->where('comid', $communityId)
            ->whereIn('block_id', $blockId)
            ->get();
    }

    /**
     * @param  array  $importData
     *
     * @return CrmBuildingCommonInfo|null
     */
    public function updateOrCreate(array $importData): ?CrmBuildingCommonInfo
    {
        return CrmBuildingCommonInfo::updateOrCreate([
            'comid'      => $importData['comid'],
            'company_id' => $importData['company_id'],
            'block_id'   => $importData['block_id'],
        ], $importData);
    }

    /**
     * @return int
     */
    public function forceDelete(): int
    {
        return CrmBuildingCommonInfo::where('company_id',  crm('company_id'))
            ->where('comid', crm('community_id'))
            ->forceDelete();
    }

    /**
     * @param  int  $companyId
     * @param  int  $communityId
     *
     * @return LengthAwarePaginator
     */
    public function getSpaceConfigurationPaginated(int $companyId, int $communityId): LengthAwarePaginator
    {
        return CrmBuildingCommonInfo::whereCompanyId($companyId)
            ->where('comid', $communityId)
            ->with([
                'buildingCommonSpace' => fn ($query) => $query->where('comid', $communityId)
            ])
            ->paginate($this->paginateLimit());
    }
}