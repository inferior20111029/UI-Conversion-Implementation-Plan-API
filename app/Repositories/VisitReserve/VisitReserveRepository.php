<?php

declare(strict_types=1);

namespace App\Repositories\VisitReserve;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

use App\Support\Enum\EnableState;

use App\Models\VisitReserve;
use App\Models\Login;
use App\Models\Login\LoginUser;

use App\Repositories\VisitReserve\Component\FilterVisitReserve;

class VisitReserveRepository
{
    use \App\Support\Trait\Paginate\PaginateTrait;

    /**
     * 取得全部預約資料
     *
     * @param int $companyId 公司 ID
     * @param int $communityId 社區 ID
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function findAll(int $companyId, int $communityId): LengthAwarePaginator
    {
        return $this->visitReserveQuery($companyId, $communityId)->paginate($this->paginateLimit());
    }

    /**
     * 取得全部預約資料
     *
     * @param int $companyId 公司 ID
     * @param int $communityId 社區 ID
     * @param string $uuid 預約 UUID
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function findByUuid(int $companyId, int $communityId, string $uuid): LengthAwarePaginator
    {
        return $this->visitReserveQuery($companyId, $communityId)
            ->when(!empty($uuid), fn(Builder $query): Builder => $query->whereUuid($uuid))
            ->paginate($this->paginateLimit());
    }

    /**
     * 取得單筆預約資料
     *
     * @param  string  $uuid
     *
     * @return VisitReserve|null
     */
    public function fetchByUuid(string $uuid): ?VisitReserve
    {
     return VisitReserve::where('uuid', $uuid)
            ->withWhereHas('property', function (Builder|BelongsTo $query): void {
                $query
                    ->select('id', 'uuid', 'space_id')
                    ->enableOn()
                    ->withWhereHas('crmBuildingSpace', function (Builder|BelongsTo $query) : void {
                        $query
                            ->select(
                                'space_id',
                                'comid',
                                'household_name',
                            )
                            ->whereNull('deleted_at')
                            ->withWhereHas('community', function (Builder|HasOne $communityQuery): void {
                                $communityQuery
                                    ->select('comname', 'comid')
                                    ->where('status', EnableState::ENABLE->value)
                                    ->whereNull('deleted_by');
                            });
                    });
            }
        )->first();
    }

    /**
     * 後臺管理員建立預約資料
     *
     * @param int $userId 使用者 ID
     * @param \App\Models\VisitReserve $insertData 預約建立資料
     *
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function adminBackendCreate(int $userId, VisitReserve $insertData): ?VisitReserve
    {
        return LoginUser::find($userId)
            ->visitReserve()
            ->save($insertData);
    }

    /**
     * 前台房仲建立預約資料
     *
     * @param int $loginId 登入 ID
     * @param \App\Models\VisitReserve $insertData 預約建立資料
     *
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function frontendAgentCreate(int $loginId, VisitReserve $insertData): ?VisitReserve
    {
        return Login::find($loginId)
            ->visitReserve()
            ->save($insertData);
    }

    /**
     * 取得仲介擁有的預約看房
     * @param int $realEstateAgentId
     * @param mixed $uuid
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function findByRealEstateAgent(int $realEstateAgentId, ?string $uuid = null): LengthAwarePaginator
    {
        return VisitReserve::where('real_estate_agent_id', $realEstateAgentId)
            ->when(str($uuid)->isUuid(), fn(Builder $query): Builder => $query->whereUuid($uuid))
            ->withWhereHas(
                'property',
                function (Builder|BelongsTo $query): void {
                    $query
                        ->select('id', 'uuid', 'space_id')
                        ->enableOn()
                        ->withWhereHas('crmBuildingSpace', function (Builder|BelongsTo $query): void {
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
                                ->whereHas('community', function (Builder $communityQuery): void {
                                    $communityQuery
                                        ->where('status', EnableState::ENABLE->value)
                                        ->whereNull('deleted_by');
                                });
                        });
                }
            )
            ->orderByDesc('appointment_time')
            ->orderByDesc('id')
            ->paginate($this->paginateLimit());
    }

    /**
     * 取得預約資料 Query
     *
     * @param int $companyId 公司 ID
     * @param int $communityId 社區 ID
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function visitReserveQuery(int $companyId, int $communityId): Builder
    {
        $query =
            VisitReserve::withWhereHas(
                'property',
                function (Builder|BelongsTo $query) use ($companyId, $communityId): void {
                    $query
                        ->select('id', 'uuid', 'space_id')
                        ->enableOn()
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
                        });
                }
            )
            ->with([
                'visitReserveTable' => function (MorphTo $morphTo) {
                    $morphTo->constrain([
                        LoginUser::class => function ($query) {
                            $query->whereNull('deleted_at');
                        },
                        Login::class,
                    ]);
                }
            ])
            ->withWhereHas('realEstateAgent:id,uuid,name,cellphone,contact_numbers,company_name')
            ->orderByDesc('appointment_time')
            ->orderByDesc('id');

        return (new FilterVisitReserve($query))
            ->execute()
            ->getQuery();
    }
}
