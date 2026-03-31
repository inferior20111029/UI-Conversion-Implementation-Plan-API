<?php

declare(strict_types=1);

namespace App\Repositories\PropertyManage;

use App\Models\Property;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Support\Parameter\PropertyManageParameter;
use App\Support\Enum\EnableState;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Repositories\PropertyManage\Component\FilterProperty;

class PropertyRepository
{
    use \App\Support\Trait\Paginate\PaginateTrait;

    /**
     * 建立物件資料
     *
     * @param string $spaceId
     * @param \App\Support\Parameter\PropertyManageParameter $parameter
     *
     * @return \App\Models\Property|null
     */
    public function createProperty(PropertyManageParameter $parameter): ?Property
    {
        $property = Property::create($parameter->contract->toArray());
        $property->fees()->save($parameter->fees);
        $property->decoration()->save($parameter->decoration);
        $property->rentItemsIncluded()->saveMany($parameter->itemsIncluded);
        $property->attachedCarparks()->saveMany($parameter->carpark);
        $property->neighborhoodTransportation()->saveMany($parameter->transportation);
        $property->neighborhoodLivability()->saveMany($parameter->livability);
        $property->document()->saveMany($parameter->document);
        $property->itemCheckIn()->save($parameter->itemCheckIn);
        $property->propertyContactInfo()->saveMany($parameter->contactInfo);
        $property->propertyContactPerson()->save($parameter->contactPerson);
        $property->attachedEquipments()->saveMany($parameter->equipment);

        return $property;
    }

    /**
     * 建立車位物件資料
     *
     * @param string $spaceId
     * @param \App\Support\Parameter\PropertyManageParameter $parameter
     *
     * @return \App\Models\Property|null
     */
    public function createCarParkingProperty(PropertyManageParameter $parameter): ?Property
    {
        $property = Property::create($parameter->contract->toArray());
        $property->fees()->save($parameter->fees);
        $property->document()->saveMany($parameter->document);
        $property->itemCheckIn()->save($parameter->itemCheckIn);
        $property->rentItemsIncluded()->saveMany($parameter->itemsIncluded);
        $property->propertyContactInfo()->saveMany($parameter->contactInfo);
        $property->propertyContactPerson()->save($parameter->contactPerson);
        $property->attachedEquipments()->saveMany($parameter->equipment);

        return $property;
    }

    /**
     * @param int $companyId
     * @param int $communityId
     * @param string $uuid
     * @param string $spaceId
     * @param string|null $parkingSpaceId
     *
     * @return Property|null
     */
    public function findUUID(int $companyId, int $communityId, string $spaceId, string $uuid, ?string $parkingSpaceId = null): ?Property
    {
        $query = Property::whereCompanyId($companyId)
            ->where('community_id', $communityId)
            ->where('uuid', $uuid);

        if ($parkingSpaceId) {
            $query->where('crm_parking_space_id', $parkingSpaceId);
        } else {
            $query->where('space_id', $spaceId);
        }

        return $query->first();
    }

    /**
     * @param int $companyId
     * @param int $communityId
     * @param string $spaceId
     * @param string $uuid
     *
     * @return Property|null
     */
    public function findCarParkingUUID(int $companyId, int $communityId, string $spaceId, string $uuid): ?Property
    {
        return $this->findUUID($companyId, $communityId, $spaceId, $uuid, $spaceId);
    }

    /**
     * 取得物件戶別編輯資料
     *
     * @param  int  $companyId
     * @param  int  $communityId
     * @param  int|null  $id
     * @param  array|null  $space
     *
     * @return Property|null
     */
    public function findPropertyUUID(int $companyId, int $communityId, ?int $id, ?array $space = []): ?Property
    {
        return Property::whereCompanyId($companyId)
            ->where('community_id', $communityId)
            ->when($space !== [], function (Builder $query) use ($space) {
                $query->where($space);
            })
            ->when($id !== null, function (Builder $query) use ($id) {
                $query->where('id', $id);
            })
            ->with([
                'decoration',
                'fees',
                'attachedCarparks.crmParkingSpace',
                'rentItemsIncluded',
                'itemCheckIn',
                'neighborhoodLivability',
                'neighborhoodTransportation',
                'propertyContactInfo',
                'propertyContactPerson',
                'document' => fn(Builder|HasMany $query) => $query->with('file')
            ])
            ->with(['attachedEquipments' => function (Builder|MorphMany $query) use ($companyId, $communityId): void {
                $query
                    ->withWhereHas('equipment', function (Builder|HasOne $equipmentQuery) use ($companyId, $communityId): void {
                        $equipmentQuery
                            ->whereCompanyId($companyId)
                            ->where('comid', $communityId)
                            ->with([
                                'crmTypeName',
                                'crmSystemName'
                            ]);
                    });
            }])
            ->orderByDesc('id')
            ->first();
    }

    /**
     * 取得物件車位編輯資料
     *
     * @param  int  $companyId
     * @param  int  $communityId
     * @param  int  $id
     *
     * @return Property|null
     */
    public function findCarParking(int $companyId, int $communityId, int $id): ?Property
    {
        return Property::whereCompanyId($companyId)
            ->where('community_id', $communityId)
            ->where('id', $id)
            ->with([
                'fees',
                'itemCheckIn',
                'propertyContactInfo',
                'propertyContactPerson',
            ])
            ->first();
    }

    /**
     * @param  int  $companyId
     * @param  int  $communityId
     *
     * @return LengthAwarePaginator|null
     */
    public function findPropertyPaginate(int $companyId, int $communityId, array $filteredData): ?LengthAwarePaginator
    {
        $filters = Arr::only($filteredData, ['type', 'title', 'enable_state', 'creator']);
        $crmBuildingSpaceFilters = Arr::only($filteredData, ['district', 'building', 'staircase', 'floor', 'household']);

        $query = Property::whereCompanyId($companyId)
            ->where('community_id', $communityId)
            ->when($filters, function (Builder $query, $filters) {
                foreach ($filters as $key => $value) {
                    $query->whereLike($key, $value);
                }
            })
            ->whereHas('crmBuildingSpace', function (Builder $query) use ($companyId, $communityId, $crmBuildingSpaceFilters) {
                $query
                    ->whereNull('deleted_at')
                    ->whereCompanyId($companyId)
                    ->where('community_id', $communityId);

                foreach ($crmBuildingSpaceFilters as $key => $value) {
                    $query->whereLike("{$key}_name", $value);
                }
            })
            ->when(isset($filteredData['is_car']), function (Builder $query) use ($filteredData) {
                if ($filteredData['is_car']) {
                    $query->whereHas('attachedCarparks', function ($q) {
                        $q->whereNotNull('crm_parking_space_id');
                    });
                } else {
                    $query->whereDoesntHave('attachedCarparks');
                }
            })
            ->with([
                'fees',
                'attachedCarparks',
                'crmBuildingSpace' => fn($query) => $query->whereNull('deleted_at'),
            ])
            ->orderByDesc('id');

        return (new FilterProperty($query))
            ->execute()
            ->getQuery()
            ->paginate($this->paginateLimit());
    }

    /**
     * @param  int  $companyId
     * @param  int  $communityId
     *
     * @return LengthAwarePaginator|null
     */
    public function findPropertyCarPaginate(int $companyId, int $communityId, array $filteredData): ?LengthAwarePaginator
    {
        $filters = Arr::only($filteredData, ['type', 'title', 'enable_state', 'creator']);
        $crmBuildingSpaceFilters = Arr::only($filteredData, ['district', 'building', 'staircase', 'floor', 'household']);
        $crmCarFilters = Arr::only($filteredData, ['parking_type', 'parking_attribute', 'car_type', 'parking_number']);

        return Property::whereCompanyId($companyId)
            ->where('community_id', $communityId)
            ->whereNotNull('crm_parking_space_id')
            ->when(isset($filteredData['price']), function (Builder $query) use ($filteredData) {
                $query->whereHas('fees', function (Builder $query) use ($filteredData) {
                    $query->where('price', $filteredData['price']);
                });
            })
            ->when($filters, function (Builder $query, $filters) {
                foreach ($filters as $key => $value) {
                    $query->whereLike($key, $value);
                }
            })
            ->whereHas('crmParkingSpaceId', function (Builder $query) use ($companyId, $communityId, $crmBuildingSpaceFilters, $crmCarFilters) {
                $query->whereCompanyId($companyId)
                    ->where('comid', $communityId)
                    ->when(!empty($crmBuildingSpaceFilters) , function (Builder $query) use ($crmBuildingSpaceFilters, $crmCarFilters) {
                        $query->whereHas('CrmBuildingSpaceForCar', function (Builder $query) use ($crmBuildingSpaceFilters) {
                            foreach ($crmBuildingSpaceFilters as $key => $value) {
                                $query->whereLike($key .'_'. 'name', $value);
                            }
                        });
                    });

                foreach ($crmCarFilters as $key => $value) {
                    $query->whereLike($key, $value);
                }
            })
            ->with([
                'fees',
                'crmParkingSpace.CrmBuildingSpaceForCar',
            ])
            ->orderByDesc('id')
            ->paginate($this->paginateLimit());
    }

    /**
     * 透過 UUID 取得物件資料
     *
     * @param int $companyId
     * @param int $communityId
     * @param string $uuid
     *
     * @return \App\Models\Property|null
     */
    public function findByUuid(int $companyId, int $communityId, string $uuid): ?Property
    {
        return $this->propertyQuery($companyId, $communityId)
            ->whereUuid($uuid)
            ->first();
    }

    /**
     * 前台透過 UUID 取得物件資料
     * @param string $uuid
     *
     * @return \App\Models\Property|null
     */
    public function frontendFindByUuid(string $uuid): ?Property
    {
        return Property::enableOn()
            ->whereUuid($uuid)
            ->first();
    }

    /**
     * 取得全部物件資料
     *
     * @param int $companyId
     * @param int $communityId
     *
     * @return \Illuminate\Support\Collection
     */
    public function findAll(int $companyId, int $communityId): Collection
    {
        return $this->propertyQuery($companyId, $communityId)->get();
    }

    private function propertyQuery(int $companyId, int $communityId): Builder
    {
        return Property::whereCompanyId($companyId)
            ->whereCommunityId($communityId)
            ->withWhereHas('crmBuildingSpace', function (Builder|BelongsTo $query) use ($companyId, $communityId): void {
                $query
                    ->select(
                        'space_id',
                        'district_name',
                        'building_name',
                        'staircase_name',
                        'floor_name',
                        'household_name',
                        'doorplate'
                    )
                    ->whereNull('deleted_at')
                    ->whereHas('company', fn(Builder $companyQuery): Builder => $companyQuery->where('company_id', $companyId))
                    ->whereHas('community', function (Builder $communityQuery) use ($communityId): void {
                        $communityQuery
                            ->where('comid', $communityId)
                            ->where('status', EnableState::ENABLE->value)
                            ->whereNull('deleted_by');
                    });
            })
            ->enableOn();
    }
}
