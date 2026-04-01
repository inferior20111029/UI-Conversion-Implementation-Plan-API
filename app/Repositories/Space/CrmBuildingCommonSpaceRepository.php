<?php

declare(strict_types=1);

namespace App\Repositories\Space;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\CrmBuildingCommonSpace;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrmBuildingCommonSpaceRepository
{
    use \App\Support\Trait\Paginate\PaginateTrait;

    /**
     * @return Collection|null
     */
    public function findByAll(): ?Collection
    {
        $companyId = crm('company_id');
        $communityId = crm('community_id');

        return CrmBuildingCommonSpace::where('comid', $communityId)
            ->where('building_common_info_id', '!=', 0)
            ->withWhereHas(
                'crmBuildingCommonInfo',
                fn (Builder|HasOne $query) =>
                $query->where('company_id', $companyId)
                    ->where('comid', $communityId)
                    ->whereIn('main_application', ['H008', 'H009', 'H010', 'H011', 'H012', 'H013', 'H014'])
            )
            ->get();
    }

    /**
     * @param  array  $blockId
     *
     * @return Collection|null
     */
    public function findByExcelUpdate(array $blockId): ?Collection
    {
        $communityId = crm('community_id');

        return CrmBuildingCommonSpace::where('comid', $communityId)
            ->whereIn('block_id', $blockId)
            ->get();
    }

    /**
     * @return Collection
     */
    public function findAll(): Collection
    {
        return CrmBuildingCommonSpace::where('comid', crm('community_id'))
            ->get();
    }

    /**
     * @param array $data
     * @return CrmBuildingCommonSpace
     */
    public function create(array $data): CrmBuildingCommonSpace
    {
        return CrmBuildingCommonSpace::create($data);
    }

    /**
     * @param  array  $data
     *
     * @return bool
     */
    public function insert(array $data): bool
    {
        return CrmBuildingCommonSpace::insert($data);
    }

    /**
     * @param string $spaceId
     * @param array $updateData
     * @return bool
     */
    public function update(string $spaceId, array $updateData): bool
    {
        return CrmBuildingCommonSpace::find($spaceId)->update($updateData);
    }

    /**
     * @param  array  $data
     *
     * @return int
     */
    public function upsert(array $data): int
    {
        return CrmBuildingCommonSpace::upsert($data, ['space_id']);
    }

    /**
     * @param  array|null  $spaceId
     *
     * @return int
     */
    public function forceDelete(?array $spaceId): int
    {
        return CrmBuildingCommonSpace::where('comid', crm('community_id'))
            ->when($spaceId, fn ($query) => $query->whereIn('space_id', $spaceId))
            ->forceDelete();
    }

    /**
     *
     * @param array $ids
     *
     * @return int
     */
    public function destroy(array $ids): int
    {
        return CrmBuildingCommonSpace::destroy($ids);
    }

    /**
     * @param  string|null  $uuid
     *
     * @return CrmBuildingCommonSpace|null
     */
    public function findByUuid(?string $uuid): ?CrmBuildingCommonSpace
    {
        return CrmBuildingCommonSpace::where('comid', crm('community_id'))
            ->where('space_id', $uuid)
            ->with(['crmHouseFeeNumber'])
            ->first();
    }

    /**
     * 取得產權基本資訊
     *
     * @param  string  $uuid
     *
     * @return CrmBuildingCommonSpace|null
     */
    public function findByPropertyInfo(string $uuid): ?CrmBuildingCommonSpace
    {
        $companyId = crm('company_id');
        $communityId = crm('community_id');

        return CrmBuildingCommonSpace::where('comid', crm('community_id'))
            ->where('space_id', $uuid)
            ->withWhereHas(
                'crmBuildingCommonInfo',
                fn (Builder|HasOne $query) => $query->where('company_id', $companyId)
                    ->where('comid', $communityId)
                    ->whereIn('main_application', ['H008', 'H009', 'H010', 'H011', 'H012', 'H013', 'H014'])
            )->with('crmBuildingCommonBaseInfo' ,
                fn (Builder|BelongsTo $query) => $query->where('space_id', $uuid))
            ->first();
    }

    /**
     * @param  int  $comid
     * @param  array  $data
     *
     * @return LengthAwarePaginator
     */
    public function getPaginatedSpaceConfigurations(int $communityId, int $companyId, array $filters): LengthAwarePaginator
    {
        $filteredConditions = Arr::except($filters, ['doorplate', 'main_application', 'block_id']);

        return CrmBuildingCommonSpace::where('comid', $communityId)
            ->where('building_common_info_id', '!=', 0)
            ->where($filteredConditions)
            ->withWhereHas('crmBuildingCommonInfo', fn ($query) => $query->whereCompanyId($companyId)
                ->where('comid', $communityId)
                ->when($filters['doorplate'] ?? null, fn ($q, $value) => $q->where('doorplate', 'LIKE', "%{$value}%"))
                ->when($filters['main_application'] ?? null, fn ($q, $value) => $q->where('main_application', 'LIKE', "%{$value}%"))
                ->when($filters['block_id'] ?? null, fn ($q, $value) => $q->where('block_id', 'LIKE', "%{$value}%"))
            )
            ->with(['crmBuildingCommonInfo' => fn ($query) => $query->whereCompanyId(crm('company_id'))
                ->where('comid', crm('community_id'))
            ])
            ->paginate($this->paginateLimit());
    }

    /**
     * 空間列表 選項
     *
     * @return Collection|null
     */
    public function fetchConfigurationSelect(): Collection
    {
        $companyId = crm('company_id');
        $communityId = crm('community_id');

        return CrmBuildingCommonSpace::where('comid', $communityId)
            ->withWhereHas('crmBuildingCommonInfo', function (Builder|HasOne $query) use ($companyId, $communityId): Builder|HasOne {
                return $query->where('company_id', $companyId)
                    ->where('comid', $communityId);
            })
            ->get();
    }

    /**
     * @param  int  $companyId
     * @param  int  $communityId
     * @param  string  $spaceId
     * @param  array  $carType
     *
     * @return Collection|null
     */
    public function findProperty(int $companyId, int $communityId, string $spaceId, array $carType): ?Collection
    {
        // vacantHouse
        return CrmBuildingCommonSpace::where('comid', $communityId)
            ->has('community')
            ->withWhereHas('carSpace', function (Builder|hasMany $query) use ($companyId, $communityId, $carType, $spaceId): Builder|hasMany {
                return $query->where('company_id', $companyId)
                    ->where('comid', $communityId)
                    ->where('space_id', $spaceId)
                    ->where('use_direction', '租賃')
                    ->whereIn('car_type', $carType);
            })
            ->whereHas('carSpace.CrmBuildingSpace', function (Builder|hasMany $query) use ($companyId, $communityId, $spaceId): Builder|hasMany {
                return $query->where('company_id', $companyId)
                    ->where('comid', $communityId)
                    ->where('space_id', $spaceId);
            })
            ->get();
    }

    /**
     * 車位物件 賣&售
     *
     * @param  int  $companyId
     * @param  int  $communityId
     * @param  string  $type
     *
     * @return Collection|null
     */
    public function findRentalSale(int $companyId, int $communityId, string $type): ?Collection
    {
        return CrmBuildingCommonSpace::where('comid', $communityId)
            ->has('community')
            ->whereHas('carSpace.propertyCarState', function (Builder $query) use ($type) {
                $rentalAndSaleType = $type === 'rent' ? 'rental' : 'sell';
                $query->where('rental_and_sale', $rentalAndSaleType);
            })
            ->whereHas('carSpace', function (Builder $query) use ($companyId, $communityId) {
                $query->where('company_id', $companyId)
                    ->where('comid', $communityId);
            })
            ->with(['carSpace.propertyCarState' => function ($query) use ($type) {
                $rentalAndSaleType = $type === 'rent' ? 'rental' : 'sell';
                $query->where('rental_and_sale', $rentalAndSaleType);
            }])
            ->get();
    }

    /**
     * @return Collection|null
     */
    public function fetchFeeNumberExcel(): ?Collection
    {
        return CrmBuildingCommonSpace::where('comid', crm('community_id'))
            ->with([
                'crmHouseFeeNumber' => function (Builder|HasMany $query): Builder|HasMany {
                    return $query->where('company_id', crm('company_id'))
                        ->where('comid', crm('community_id'));
                }
            ])
            ->get();
    }
}