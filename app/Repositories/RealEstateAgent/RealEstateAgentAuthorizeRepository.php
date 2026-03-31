<?php

declare(strict_types=1);

namespace App\Repositories\RealEstateAgent;

use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\RealEstateAgentAuthorize;

use App\Repositories\RealEstateAgent\Component\FilterRealEstateAgent;

class RealEstateAgentAuthorizeRepository
{
    use \App\Support\Trait\Paginate\PaginateTrait;

    /**
     * 取得全部已授權房屋仲介
     *
     * @param int $companyId 公司 ID
     * @param int $communityId 社區 ID
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function findAll(int $companyId, int $communityId): LengthAwarePaginator
    {
        return $this->realEstateAgentAuthorizeQuery($companyId, $communityId)->paginate($this->paginateLimit());
    }

    /**
     * 透過 UUID 取得單筆已授權房屋仲介
     *
     * @param int $companyId 公司 ID
     * @param int $communityId 社區 ID
     * @param string $uuid 授權 UUID
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function findByUuid(int $companyId, int $communityId, string $uuid): LengthAwarePaginator
    {
        return $this->realEstateAgentAuthorizeQuery($companyId, $communityId)
            ->whereUuid($uuid)
            ->paginate($this->paginateLimit());
    }

    /**
     * 透過 IdentificationCode 取得單筆已授權房屋仲介
     *
     * @param  int  $companyId
     * @param  int  $communityId
     * @param  string  $code
     *
     * @return RealEstateAgentAuthorize|null
     */
    public function findByIdentificationCode(int $companyId, int $communityId, string $code): ?RealEstateAgentAuthorize
    {
        return RealEstateAgentAuthorize::notDelete()
            ->whereCompanyId($companyId)
            ->whereCommunityId($communityId)
            ->whereIdentificationCode($code)
            ->first();
    }

    /**
     * 批次刪除
     *
     * @param int $companyId 公司 ID
     * @param int $communityId 社區 ID
     * @param array $targetUuids
     * @param int $deleteBy
     *
     * @return int
     */
    public function multipleDelete(int $companyId, int $communityId, array $targetUuids, int $deleteBy): int
    {
        return RealEstateAgentAuthorize::notDelete()
            ->whereCompanyId($companyId)
            ->whereCommunityId($communityId)
            ->whereIn('uuid', $targetUuids)
            ->update([
                'delete_by' => $deleteBy
            ]);
    }

    /**
     * 取得房屋仲介授權 Query
     *
     * @param int $companyId 公司 ID
     * @param int $communityId 社區 ID
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function realEstateAgentAuthorizeQuery(int $companyId, int $communityId): Builder
    {
        $query = RealEstateAgentAuthorize::notDelete()
            ->whereCompanyId($companyId)
            ->whereCommunityId($communityId)
            ->withWhereHas('realEstateAgent', function (Builder|BelongsTo $query) use ($companyId, $communityId): void {
                $query
                    ->alreadyVerify()
                    ->notDelete()
                    ->with('entrust', function (Builder|HasMany $entrustQuery) use ($companyId, $communityId): void {
                        $entrustQuery
                            ->entrustOn()
                            ->orderByDesc('id')
                            ->withWhereHas('file')
                            ->withWhereHas('space', function (Builder|HasOne $spaceQuery) use ($companyId, $communityId): void {
                                $spaceQuery
                                    ->withWhereHas('community:comid,comname')
                                    ->whereCompanyId($companyId)
                                    ->whereCommunityId($communityId)
                                    ->whereNull('deleted_at');
                            });
                    });
            })
            ->orderByDesc('updated_at');

        return (new FilterRealEstateAgent($query))->execute()->getQuery();
    }
}
