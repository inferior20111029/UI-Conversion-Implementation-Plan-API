<?php

declare(strict_types=1);

namespace App\Repositories\Space;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

use App\Repositories\Space\Component\FilterSpace;

use App\Models\RenterContract;
use App\Models\CrmBuildingSpace;

use App\Support\Enum\EnableState;
use App\Support\Enum\SpacePublicType;
use App\Support\Parameter\RenterContractParameter;

class CrmBuildingSpaceRepository
{
    use \App\Support\Trait\Paginate\PaginateTrait;

    /**
     * @param string $uuid
     * @param int $companyId
     * @return CrmBuildingSpace|null
     */
    public function findByUuid(string $uuid, int $companyId): ?CrmBuildingSpace
    {
        return CrmBuildingSpace::whereCompanyId($companyId)
            ->where('space_id', $uuid)
            ->whereNull('deleted_at')
            ->with('crmHouseFeeNumber')
            ->first();
    }

    /**
     * 取得全部戶別資料
     *
     * @param int $type 戶別 / 公設類別 0:公設 1:住戶
     *
     * @return Collection
     */
    public function findByAll(int $type = 1): Collection
    {
        return CrmBuildingSpace::whereNull('deleted_at')
            ->whereHas('company', fn(Builder $query): Builder => $query->where('company_id', crm('company_id')))
            ->whereHas('community', function (Builder $query): void {
                $query
                    ->where('comid', crm('community_id'))
                    ->where('status', EnableState::ENABLE->value);
            })
            ->where('public_type', $type)
            ->orderBy('household_natsort')
            ->get();
    }

    /**
     * @return Collection|null
     */
    public function fetchFeeNumberExcel(): ?Collection
    {
        return CrmBuildingSpace::where('company_id', crm('company_id'))
            ->where('comid', crm('community_id'))
            ->whereNull('deleted_at')
            ->with([
                'crmHouseFeeNumber' => function (Builder|HasMany $query): Builder|HasMany {
                    return $query->where('company_id', crm('company_id'))
                        ->where('comid', crm('community_id'));
                }
            ])
            ->get();
    }

    /**
     * @param array $updateData
     * @return int
     */
    public function upsert(array $updateData): int
    {
        return  CrmBuildingSpace::upsert($updateData, ['space_id']);
    }

    /**
     * @param array $data
     * @return CrmBuildingSpace
     */
    public function insert(array $data): CrmBuildingSpace
    {
        return CrmBuildingSpace::create($data);
    }

    /**
     * @param array $data
     * @return CrmBuildingSpace
     */
    public function insertExcel(array $data): bool
    {
        return CrmBuildingSpace::insert($data);
    }

    /**
     * @param string $id
     * @param array $updateData
     * @return bool
     */
    public function update(string $id, array $updateData): bool
    {
        return CrmBuildingSpace::find($id)->update($updateData);
    }

    public function updateConfiguration(array $where, array $updateData): bool
    {
        return CrmBuildingSpace::where($where)->update($updateData);
    }

    /**
     *
     * @param array $ids
     *
     * @return int
     */
    public function destroy(array $ids): int
    {
        return CrmBuildingSpace::destroy($ids);
    }

    /**
     * @return int
     */
    public function delete(): int
    {
        return CrmBuildingSpace::whereCompanyId(crm('company_id'))
            ->where('comid', crm('community_id'))
            ->delete();
    }

    /**
     * 取得空間組態
     *
     * @param  int  $companyId
     * @param  int  $comid
     * @param  array  $data
     * @param  int  $type
     *
     * @return LengthAwarePaginator
     */
    public function spaceConfigurationPage(int $companyId, int $comid, array $data, int $type): LengthAwarePaginator
    {
        return CrmBuildingSpace::whereCompanyId($companyId)
            ->where('comid', $comid)
            ->where('public_type', $type)
            ->when($data, function (Builder $query, $filters) {
                foreach ($filters as $key => $value) {
                    $query->whereLike($key, $value);
                }
            })
            ->orderBy('household_natsort')
            ->paginate($this->paginateLimit());
    }

    /**
     * 取得專有空間
     *
     * @param integer $companyId 公司 ID
     * @param integer $communityId 建案 ID
     * @param string|null $spaceId 空間 ID
     *
     *  array
     */
    public function findPrivate(int $companyId, int $communityId, ?string $spaceId = null): array
    {
        $query = CrmBuildingSpace::whereNull('deleted_at')
            ->when(!empty($spaceId), fn(Builder $query): Builder => $query->where('space_id', $spaceId))
            ->whereHas('company', fn(Builder $query): Builder => $query->where('company_id', $companyId))
            ->whereHas('community', function (Builder $query) use ($communityId): void {
                $query
                    ->where('comid', $communityId)
                    ->where('status', EnableState::ENABLE->value);
            })
            ->orderBy('household_natsort')
            ->wherePublicType(SpacePublicType::private->value)
            ->with('houseCarParking', function (Builder|HasMany $query) use ($companyId, $communityId): void {
                $query
                    ->where('company_id', $companyId)
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
                    });
            })
            ->with('realEstateAgentEntrust', function (Builder|HasMany $query): void {
                $query
                    ->entrustOn()
                    ->withWhereHas('agent', function (Builder|BelongsTo $agentQuery): void {
                        $agentQuery->notDelete();
                    });
            })
            ->with('certification', function (Builder|HasMany $query): void {
                $query
                    ->select('space_id', 'name', 'version', 'type', 'application_at')
                    ->withWhereHas('buildingSpaceCertificationFile.file');
            })
            ->with('property', function ($query): void {
                $query
                    ->select('id', 'uuid', 'space_id', 'type')
                    ->enableOn();
            })
            ->withCount([
                'houseState as rental_count' => fn(Builder $query) => $query->where('live', 'rented')
            ])
            ->with([
                'planning',
                'price',
                'areaSetting',
                'exclusiveArea',
                'landArea',
                'publicHoldingArea',
                'agreedDedicatedArea',
                'agreedDedicatedAreaSetting',
                'layoutSetting',
                'spaceEarnestPayment',
                'spaceLayout',
                'document' => fn(Builder|HasMany $query): Builder|HasMany => $query->withWhereHas('file')
            ]);

        $baseQuery = (new FilterSpace($query))
            ->execute()
            ->getQuery()
            ->sharedLock();

        $totalRentalCount = $baseQuery
            ->get()
            ->sum('rental_count');

        $paginatedSpaces = $baseQuery
            ->paginate($this->paginateLimit());

        return [$paginatedSpaces, $totalRentalCount];
    }

    /**
     * 取得合約資料
     *
     * @param integer $companyId 公司 ID
     * @param integer $communityId 建案 ID
     * @param string $spaceId 戶別 ID
     * @param string $uuid 合約 UUID
     *
     * @return \App\Models\CrmBuildingSpace|null
     */
    public function fetchContract(int $companyId, int $communityId, string $spaceId, ?string $uuid): ?CrmBuildingSpace
    {
        return CrmBuildingSpace::whereNull('deleted_at')
            ->whereHas('company', fn(Builder $query): Builder => $query->where('company_id', $companyId))
            ->whereHas('community', function (Builder $query) use ($communityId): void {
                $query
                    ->where('comid', $communityId)
                    ->where('status', EnableState::ENABLE->value);
            })
            ->withWhereHas('renterContract', function (Builder|MorphMany $query) use ($uuid, $companyId, $communityId): void {
                $query
                    ->when(!empty($uuid), fn(Builder $contractQuery): Builder => $contractQuery->whereUuid($uuid))
                    ->whereNotNull('start_time')
                    ->whereNotNull('end_time')
                    ->isNotDelete()
                    ->withCount('cache')
                    ->with([
                        'decoration',
                        'fees',
                        'rentItemsIncluded',
                        'signature',
                        'paymentCycle',
                        'persons',
                        'notify',
                        'bank',
                        'renterInspectionReturn.file',
                        'bill' => fn(Builder|HasMany $query): Builder|HasMany => $query->isNotDelete()->withWhereHas('amount'),
                        'document' => fn(Builder|HasMany $query): Builder|HasMany => $query->withWhereHas('file')
                    ])
                    ->with('attachedCarpark', function (Builder|MorphMany $query) use ($companyId, $communityId): void {
                        $query
                            ->withWhereHas('crmParkingSpace', function (Builder|BelongsTo $query) use ($companyId, $communityId): void {
                                $query
                                    ->where('company_id', $companyId)
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
                                    });
                            });
                    })
                    ->with('attachedEquipment', function (Builder|MorphMany $query) use ($companyId, $communityId): void {
                        $query
                            ->withWhereHas('equipment', function (Builder|HasOne $equipmentQuery) use ($companyId, $communityId): void {
                                $equipmentQuery
                                    ->whereCompanyId($companyId)
                                    ->where('comid', $communityId)
                                    ->with(
                                        'crmTypeName',
                                        'crmSystemName',
                                        'crmEquipmentScrap',
                                    );
                            });
                    })
                    ->with('mutualRenterContract', function (Builder|HasMany $mutualQuery): void {
                        $mutualQuery
                            ->withWhereHas('relationContract:id,uuid,taggable_type,taggable_id');
                    })
                    ->orderByDesc('cache_count')
                    ->orderByDesc('start_time')
                    ->orderByDesc('id');
            })
            ->with('equipment', fn(Builder|HasMany $query): Builder|HasMany => $query->with('crmTypeName', 'crmSystemName'))
            ->find($spaceId);
    }

    /**
     * 建立合約
     *
     * @param string $spaceId
     * @param \App\Support\Parameter\RenterContractParameter $parameter
     *
     * @return \App\Models\RenterContract|null
     */
    public function createContract(string $spaceId, RenterContractParameter $parameter): ?RenterContract
    {
        $space = CrmBuildingSpace::whereNull('deleted_at')->has('company')->has('community')->find($spaceId);
        $renterContract = $space->renterContract()->save($parameter->contract);
        $renterContract->rentItemsIncluded()->saveMany($parameter->itemsIncluded);
        $renterContract->persons()->saveMany($parameter->persons);
        $renterContract->document()->saveMany($parameter->document);
        $renterContract->paymentCycle()->save($parameter->paymentCycle);
        $renterContract->notify()->saveMany($parameter->notify);
        $renterContract->decoration()->save($parameter->decoration);
        $renterContract->fees()->save($parameter->fees);
        $renterContract->attachedCarpark()->saveMany($parameter->carpark);
        $renterContract->attachedEquipment()->saveMany($parameter->equipment);
        $renterContract->bank()->save($parameter->bank);

        return $renterContract;
    }

    /**
     * 空間列表 選項
     *
     * @return Collection|null
     */
    public function fetchConfigurationSelect(): ?Collection
    {
        $companyId = crm('company_id');
        $communityId = crm('community_id');

        return CrmBuildingSpace::select(
            'building_name',
            'district_name',
            'staircase_name',
            'floor_name',
            'household_name',
            'doorplate',
            'block_id',
            'main_application',
        )
            ->where('company_id', $companyId)
            ->where('comid', $communityId)
            ->whereNull('deleted_at')
            ->groupBy(
                'building_name',
                'district_name',
                'staircase_name',
                'floor_name',
                'household_name',
                'doorplate',
                'block_id',
                'main_application'
            )
            ->get();
    }

    /**
     * 取得物件空間資料
     *
     * @param  int  $companyId
     * @param  int  $communityId
     * @param  string  $type // 租屋出售
     *
     * @return Collection|null
     */
    public function findProperty(int $companyId, int $communityId, string $type): ?Collection
    {
        // vacantHouse  rentalAndSale
        return CrmBuildingSpace::whereNull('deleted_at')
            ->where('company_id', $companyId)
            ->where('comid', $communityId)
            ->has('company')
            ->has('community')
            ->when($type == 'rent', function ($query) {
                $query->whereHas('houseState', function (Builder|HasOne $query): Builder|HasOne {
                    return $query->where('rental_and_sale', 'rental');
                })
                    ->with(['houseState' => function (Builder|HasOne $query): Builder|HasOne {
                        return $query->where('rental_and_sale', 'rental');
                    }]);
            })
            ->when($type == 'sell', function ($query) {
                $query->whereHas('houseState', function (Builder|HasOne $query): Builder|HasOne {
                    return $query->where('rental_and_sale', 'sell');
                })
                    ->with(['houseState' => function (Builder|HasOne $query): Builder|HasOne {
                        return $query->where('rental_and_sale', 'sell');
                    }]);
            })
            ->get();
    }

    /**
     * 取得面積總覽資料
     *
     * @param integer $companyId
     * @param integer $communityId
     *
     * @return Collection
     */
    public function findArea(int $companyId, int $communityId): Collection
    {
        return CrmBuildingSpace::select('space_id')
            ->whereNull('deleted_at')
            ->whereHas('company', fn(Builder $query): Builder => $query->where('company_id', $companyId))
            ->whereHas('community', function (Builder $query) use ($communityId): void {
                $query
                    ->where('comid', $communityId)
                    ->where('status', EnableState::ENABLE->value);
            })
            ->wherePublicType(SpacePublicType::private->value)
            ->withSum('exclusiveArea', 'ping')
            ->withSum('publicHoldingArea', 'total')
            ->withSum('agreedDedicatedArea', 'ping')
            ->get();
    }

    /**
     * 尋找關係人資料
     *
     * @param  int  $companyId
     * @param  int  $communityId
     *
     * @return Collection|null
     */
    public function fetchRelatedParty(int $companyId, int $communityId): ?Collection
    {
        return CrmBuildingSpace::whereNull('deleted_at')
            ->where('company_id', $companyId)
            ->where('comid', $communityId)
            ->whereHas('company')
            ->whereHas('community')
            ->with([
                'crmPropertyInfoList' => fn($query) => $query->where('is_edit', '1'),
                'crmPropertyInfoList.crmPropertyTransactionInfo' => fn($query) => $query->where('client_id', '!=', '0'),
                'crmPropertyInfoList.crmPropertyTransactionInfo.crmClient',
                'crmPropertyInfoList.crmPropertyTransactionInfo.crmClient.crmClientContact',
                'crmPropertyInfoList.crmPropertyTransactionInfo.crmClientHasCompany',
            ])
            ->get();
    }

    /**
     * 取得格局面積資料
     *
     * @param  int  $companyId
     * @param  int  $communityId
     * @param  string  $spaceId
     *
     * @return Collection
     */
    public function findPatternArea(int $companyId, int $communityId, string $spaceId): Collection
    {
        return CrmBuildingSpace::where('space_id', $spaceId)
            ->whereNull('deleted_at')
            ->whereHas('company', fn(Builder $query) => $query->where('company_id', $companyId))
            ->whereHas('community', function (Builder $query) use ($communityId) {
                $query->where('comid', $communityId)
                    ->where('status', EnableState::ENABLE->value);
            })
            ->with([
                'layoutSetting.crmLayoutSettingDetail',
                'spaceLayout' => fn($query) => $query->where('space_id', $spaceId),
            ])
            ->withSum('exclusiveArea', 'ping')
            ->get();
    }
}
