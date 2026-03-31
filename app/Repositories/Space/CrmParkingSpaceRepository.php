<?php

declare(strict_types=1);

namespace App\Repositories\Space;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

use Symfony\Component\HttpFoundation\Response;

use App\Models\RenterContract;
use App\Models\CrmParkingSpace;

use App\Support\Response\ApiMessage;

use App\Support\Enum\FetchMessage;
use App\Support\Enum\ParkingAttribute;

use App\Support\Parameter\RenterContractParameter;

class CrmParkingSpaceRepository
{
    use \App\Support\Trait\Paginate\PaginateTrait;

    /**
     * @param string $uuid
     * @param int $companyId
     * @param int $communityId
     * @return CrmParkingSpace|null
     */
    public function findByUuid(string $uuid, int $companyId, int $communityId): ?CrmParkingSpace
    {
        return CrmParkingSpace::whereCompanyId($companyId)
            ->where('comid', $communityId)
            ->where('id', $uuid)
            ->with('houseState')
            ->first();
    }

    /**
     * @param  string|null  $spaceUuid
     * @param  int  $companyId
     * @param  int  $communityId
     *
     * @return CrmParkingSpace|null
     */
    public function findBySpaceUuid(?string $spaceUuid): ?CrmParkingSpace
    {
        return CrmParkingSpace::whereCompanyId(crm('company_id'))
            ->where('comid', crm('community_id'))
            ->where('id', $spaceUuid)
            ->first();
    }

    /**
     * @param $type
     *
     * @return Collection|null
     */
    public function findByAll(): ?Collection
    {
        return CrmParkingSpace::whereCompanyId(crm('company_id'))
            ->where('comid', crm('community_id'))
            ->get();
    }

    /**
     * 戶別下車位空間
     *
     * @param string $spaceId 空間 ID
     *
     * @return Collection|null
     */
    public function findBySpace(string $spaceId)
    {
        return CrmParkingSpace::whereCompanyId(crm('company_id'))
            ->where('comid', crm('community_id'))
            ->whereSpaceId($spaceId)
            ->with([
                'CrmBuildingSpaceForCar' => function (Builder|BelongsTo $query): void {
                    $query->where('comid', crm('community_id'))
                        ->where('building_common_info_id', '!=', 0);
                },
                'CrmBuildingSpaceForCar.crmBuildingCommonInfo' => function (Builder|HasOne $query): void {
                    $query->where('comid', crm('community_id'));
                }
            ])->get();
    }

    /**
     * @param  string  $spaceId
     *
     * @return CrmParkingSpace[]|Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function findByCarSpace(string $spaceId)
    {
        return CrmParkingSpace::whereCompanyId(crm('company_id'))
            ->where('comid', crm('community_id'))
            ->where('id', $spaceId)
            ->with([
                'CrmBuildingSpaceForCar' => function (Builder|BelongsTo $query): void {
                    $query->where('comid', crm('community_id'))
                        ->where('building_common_info_id', '!=', 0);
                },
                'CrmBuildingSpaceForCar.crmBuildingCommonInfo' => function (Builder|HasOne $query): void {
                    $query->where('comid', crm('community_id'));
                }
            ])->get();
    }

    /**
     * @param array $data
     * @return int
     */
    public function insert(array $data): bool
    {
        return CrmParkingSpace::insert($data);
    }

    /**
     * @param array $data
     * @return int
     */
    public function create(array $data): CrmParkingSpace
    {
        return CrmParkingSpace::create($data);
    }

    /**
     * @param string $id
     * @param array $updateData
     * @return bool
     */
    public function update(string $id, array $updateData): bool
    {
        return CrmParkingSpace::find($id)->update($updateData);
    }

    /**
     * @param  array  $data
     *
     * @return int
     */
    public function upsert(array $data): int
    {
        return CrmParkingSpace::upsert($data, ['id']);
    }

    /*
     * 更新戶別車位資料
     *
     * @param integer $companyId 公司 ID
     * @param integer $communityId 社區 ID
     * @param array $ids 車位 ID
     * @param array $updateData
     *
     * @return integer|bool
     */
    public function updateHouseCarParking(int $companyId, int $communityId, array $ids, array $updateData): int|bool
    {
        return CrmParkingSpace::whereIn('id', $ids)
            ->whereCompanyId($companyId)
            ->where('comid', $communityId)
            ->whereHas('CrmBuildingSpaceForCar', function (Builder|BelongsTo $query) use ($communityId): void {
                $query
                    ->where('comid', $communityId)
                    ->has('crmBuildingCommonInfo');
            })
            ->where(function (Builder $query): void {
                $query
                    ->whereNull('space_id')
                    ->orWhere('space_id', '');
            })
            ->where('parking_attribute', '<>', ParkingAttribute::public->value)
            ->update($updateData);
    }

    /**
     *
     * @param array $ids
     *
     * @return int
     */
    public function destroy(array $ids): int
    {
        return CrmParkingSpace::destroy($ids);
    }

    /**
     * @return int
     */
    public function forceDelete(): int
    {
        return CrmParkingSpace::whereCompanyId(crm('company_id'))
            ->where('comid', crm('community_id'))
            ->forceDelete();
    }

    /**
     * @param  string  $id
     *
     * @return int|bool
     */
    public function cancel(string $id): int|bool
    {
        return CrmParkingSpace::where('id', $id)->update(
            ['space_id' => null]
        );
    }

    /**
     * @param  string  $id
     * @param  string  $spaceId
     *
     * @return int|bool
     */
    public function configuration(string $id, string $spaceId): int|bool
    {
        return CrmParkingSpace::where('id', $id)->update(
            ['space_id' => $spaceId]
        );
    }

    /**
     * @param  array  $data
     *
     * @return LengthAwarePaginator
     */
    public function parkingConfigurationPage(array $data): LengthAwarePaginator
    {
        $buildingFilters = Arr::only($data, ['district_name', 'staircase_name', 'floor_name', 'building_name', 'household_name']);
        $parkingFilters = Arr::only($data, ['parking_number', 'parking_type', 'parking_size', 'parking_attribute', 'use_direction', 'car_type']);

        return CrmParkingSpace::whereCompanyId(crm('company_id'))
            ->where('comid', crm('community_id'))
            ->where(function ($query) use ($parkingFilters) {
                foreach ($parkingFilters as $key => $value) {
                    $query->whereLike($key, $value);
                }
            })
            ->when(!empty($data['application']) , fn ($query) => $query->whereIn('application', $data['application']))
            ->when($buildingFilters, function ($query) use ($buildingFilters) {
                $query->whereHas('CrmBuildingSpaceForCar', function ($subQuery) use ($buildingFilters) {
                    $subQuery->where($buildingFilters);
                });
            })
            ->when($data['block_id'] ?? false, function ($query) use ($data) {
                $query->whereHas('CrmBuildingSpaceForCar.crmBuildingCommonInfo', function ($subQuery) use ($data) {
                    $subQuery->whereLike('block_id', $data['block_id']);
                });
            })
            ->with(['CrmBuildingSpaceForCar', 'CrmBuildingSpace'])
            ->orderBy('created_at')
            ->paginate($this->paginateLimit());
    }

    /**
     * 取得合約資料
     *
     * @param integer $companyId  公司 ID
     * @param integer $communityId  建案 ID
     * @param string $carParkingId 車位 ID
     * @param string|null $uuid 合約 UUID
     *
     * @return \App\Models\CrmParkingSpace|null
     */
    public function fetchContract(int $companyId, int $communityId, string $carParkingId, ?string $uuid): ?CrmParkingSpace
    {
        return CrmParkingSpace::whereCompanyId($companyId)
            ->whereComid($communityId)
            ->whereHas('CrmBuildingSpaceForCar', function (Builder|BelongsTo $query) use ($communityId): void {
                $query
                    ->where('comid', $communityId)
                    ->has('crmBuildingCommonInfo');
            })
            ->withWhereHas('renterContract', function (Builder|MorphMany $query) use ($uuid): void {
                $query
                    ->when(!empty($uuid), fn(Builder $contractQuery): Builder => $contractQuery->whereUuid($uuid))
                    ->whereNotNull(['start_time', 'end_time'])
                    ->isNotDelete()
                    ->withCount('cache')
                    ->with([
                        'fees',
                        'signature',
                        'paymentCycle',
                        'persons',
                        'notify',
                        'bank',
                        'bill' => fn(Builder|HasMany $query): Builder|HasMany => $query->isNotDelete()->withWhereHas('amount'),
                        'document' => fn(Builder|HasMany $query): Builder|HasMany => $query->withWhereHas('file')
                    ])
                    ->with('fromMutual', function (Builder|HasOne $mutualQuery): void {
                        $mutualQuery
                            ->withWhereHas('sourceContract:id,uuid,taggable_type,taggable_id');
                    })
                    ->orderByDesc('cache_count')
                    ->orderByDesc('start_time')
                    ->orderByDesc('id');
            })
            ->find($carParkingId);
    }

    /**
     * @return Collection|null
     */
    public function fetchExcelDownload(): ?Collection
    {
        return CrmParkingSpace::whereCompanyId(crm('company_id'))
            ->where('comid', crm('community_id'))
            ->with([
                'CrmBuildingSpace' => function (Builder|BelongsTo $query): void {
                    $query->where('company_id', crm('company_id'))
                        ->where('comid', crm('community_id'))
                        ->whereNull('deleted_at');
                },
                'CrmBuildingSpaceForCar' => function (Builder|BelongsTo $query): void {
                    $query->where('comid', crm('community_id'))
                        ->where('building_common_info_id', '!=', 0)
                        ->withWhereHas('crmBuildingCommonInfo');
                },
            ])
            ->get();
    }

    /**
     * 建立合約
     *
     * @param string $carParkingId 車位 ID
     * @param \App\Support\Parameter\RenterContractParameter $parameter
     *
     * @return \App\Models\RenterContract|null
     */
    public function createContract(string $carParkingId, RenterContractParameter $parameter): ?RenterContract
    {
        $carParking = CrmParkingSpace::sharedLock()
            ->whereHas('CrmBuildingSpaceForCar', function (Builder|BelongsTo $query): void {
                $query->has('crmBuildingCommonInfo');
            })
            ->find($carParkingId);

        if (empty($carParking)) {
            (new ApiMessage())
                ->throwException(FetchMessage::NOT_FOUND_CAR_PARKING_DATA->value, Response::HTTP_NOT_FOUND);
        }

        $renterContract = $carParking->renterContract()->save($parameter->contract);
        $renterContract->persons()->saveMany($parameter->persons);
        $renterContract->document()->saveMany($parameter->document);
        $renterContract->paymentCycle()->save($parameter->paymentCycle);
        $renterContract->notify()->saveMany($parameter->notify);
        $renterContract->fees()->save($parameter->fees);
        $renterContract->bank()->save($parameter->bank);

        return $renterContract;
    }

    /**
     * 車位列表下拉選單
     *
     * @return Collection|null
     */
    public function getParkingSpacesSelect(): ?Collection
    {
        $companyId = crm('company_id');
        $communityId = crm('community_id');

        return CrmParkingSpace::whereCompanyId($companyId)
            ->where('comid', $communityId)
            ->whereHas('CrmBuildingSpaceForCar', function (Builder|BelongsTo $query) use ($communityId): void {
                $query->where('comid', $communityId)
                    ->where('building_common_info_id', '!=', 0);
            })
            ->with([
                'CrmBuildingSpaceForCar' => function (Builder|BelongsTo $query) use ($communityId): void {
                    $query->where('comid', $communityId)
                        ->where('building_common_info_id', '!=', 0);
                }
            ])
            ->get();
    }

    /**
     * 取得可分配戶別的車位資料
     * @param int $companyId
     * @param int $communityId
     * @return \Illuminate\Support\Collection
     */
    public function fetchCanDistributeSpaceOfParking(int $companyId, int $communityId): Collection
    {
        return CrmParkingSpace::whereCompanyId($companyId)
            ->where('comid', $communityId)
            ->withWhereHas('CrmBuildingSpaceForCar', function (Builder|BelongsTo $query) use ($communityId): void {
                $query
                    ->select(
                        'space_id',
                        'district_name',
                        'building_name',
                        'staircase_name',
                        'floor_name',
                        'household_name'
                    )
                    ->where('comid', $communityId)
                    ->has('crmBuildingCommonInfo');
            })
            ->where('space_id', '')
            ->where('parking_attribute', '<>', ParkingAttribute::public->value)
            ->get();
    }
}
