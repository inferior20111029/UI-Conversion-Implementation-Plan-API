<?php

declare(strict_types=1);

namespace App\Repositories\RealEstateAgent;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\RealEstateAgent;
use App\Models\RealEstateAgentAuthorize;

use App\Support\Enum\VerifyState;

class RealEstateAgentRepository
{
    /**
     * 取得全部房屋仲介
     *
     * @param integer $companyId 公司 ID
     *
     * @return \Illuminate\Support\Collection
     */
    public function findAll(int $companyId): Collection
    {
        return $this->fetchRealEstateAgentData($companyId);
    }

    /**
     * 透過 UUID 取得單筆房屋仲介
     *
     * @param integer $companyId 公司 ID
     * @param string $uuid
     *
     * @return \Illuminate\Support\Collection
     */
    public function findByUuid(int $companyId, string $uuid): Collection
    {
        return $this->fetchRealEstateAgentData($companyId, $uuid);
    }

    /**
     * 取得委託資料
     *
     * @param integer $companyId 公司 ID
     * @param integer $communityId 建案 ID
     * @param string $spaceId 戶別 ID
     *
     * @return \Illuminate\Support\Collection
     */
    public function findEntrust(int $companyId, int $communityId, string $spaceId): Collection
    {
        return RealEstateAgent::notDelete()
            ->alreadyVerify()
            ->whereHas('authorize', fn(Builder|HasMany $query): Builder|HasMany => $query
                ->whereCompanyId($companyId)
                ->whereCommunityId($communityId)
                ->notDelete())
            ->with('entrust', function (Builder|HasMany $entrustQuery) use ($companyId, $communityId, $spaceId): void {
                $entrustQuery
                    ->whereCompanyId($companyId)
                    ->whereCommunityId($communityId)
                    ->where('space_id', $spaceId)
                    ->whereNotNull('start_time')
                    ->whereNotNull('end_time')
                    ->entrustOn()
                    ->with('file')
                    ->withWhereHas('space', function (Builder|HasOne $spaceQuery) use ($companyId, $communityId): void {
                        $spaceQuery
                            ->whereCompanyId($companyId)
                            ->where('comid', $communityId)
                            ->whereNull('deleted_at');
                    });
            })
            ->get();
    }

    /**
     * 建立單筆房屋仲介
     *
     * @param array $insertData 仲介人員建立資料
     * @param array $accountCreateData 帳號建立資料
     *
     * @return \App\Models\RealEstateAgent|null
     */
    public function create(array $insertData, array $accountCreateData): ?RealEstateAgent
    {
        $realEstateAgent = RealEstateAgent::updateOrCreate(
            [
                'verify_state' => VerifyState::NOT_YET->value,
                'national_id_number' => (string) data_get($insertData, 'national_id_number')
            ],
            $insertData
        );

        $realEstateAgent->login()->updateOrCreate([], $accountCreateData);

        return $realEstateAgent;
    }

    /**
     * 更新單筆房屋仲介
     *
     * @param integer $id 房屋仲介 ID
     * @param array $updateData
     *
     * @return bool
     */
    public function update(int $id, array $updateData): bool
    {
        return RealEstateAgent::find($id)->update($updateData);
    }

    /**
     * 檢查身分證字號是否重複
     *
     * @param string $nationalIdNumber 身分證字號
     *
     * @return boolean
     */
    public function checkRepeatNationalIdNumber(string $nationalIdNumber): bool
    {
        return RealEstateAgent::whereNationalIdNumber($nationalIdNumber)
            ->notDelete()
            ->alreadyVerify()
            ->exists();
    }

    /**
     * 透過 Token 取得仲介資料
     *
     * @param string $token
     *
     * @return \App\Models\RealEstateAgent
     */
    public function findByToken(string $token): ?RealEstateAgent
    {
        return RealEstateAgent::notDelete()
            ->withWhereHas('token', function (Builder|HasMany $query) use ($token) {
                $query
                    ->whereToken($token)
                    ->whereNull('last_used_at')
                    ->where('expires_at', '>', now());
            })
            ->with('login')
            ->first();
    }

    /**
     * 透過識別碼取得仲介資料
     *
     * @param string $identificationCode
     *
     * @return Builder|object|RealEstateAgent|\Illuminate\Database\Eloquent\Model|null
     */
    public function findByIdentificationCode(string $identificationCode): ?RealEstateAgent
    {
        return RealEstateAgent::notDelete()
            ->alreadyVerify()
            ->whereIdentificationCode($identificationCode)
            ->first();
    }

    /**
     * 透過身分證取得仲介資料
     * @param string $nationalIdNumber
     * @return Builder|object|RealEstateAgent|\Illuminate\Database\Eloquent\Model|null
     */
    public function findByRepeatNationalIdNumber(string $nationalIdNumber): ?RealEstateAgent
    {
        return RealEstateAgent::notDelete()
            ->whereNationalIdNumber($nationalIdNumber)
            ->first();
    }

    /**
     * 建立房屋仲介授權
     *
     * @param array $createData
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createAuthorize(array $createData): ?RealEstateAgentAuthorize
    {
        return RealEstateAgent::notDelete()
            ->alreadyVerify()
            ->whereIdentificationCode(Arr::get($createData, 'identification_code'))
            ->first()
            ->authorize()
            ->updateOrCreate(
                Arr::only($createData, ['company_id', 'community_id', 'identification_code']),
                $createData
            );
    }

    /**
     * 取得房仲資料
     *
     * @param integer $companyId 公司 ID
     * @param string|null $uuid 房仲 UUID
     *
     * @return \Illuminate\Support\Collection
     */
    private function fetchRealEstateAgentData(int $companyId, ?string $uuid = null): Collection
    {
        return RealEstateAgent::notDelete()
            ->when(!empty($uuid), fn(Builder $query): Builder => $query->whereUuid($uuid))
            ->with('avatarFile', 'login')
            ->with('entrust', function (Builder|HasMany $entrustQuery) use ($companyId): void {
                $entrustQuery
                    ->entrustOn()
                    ->orderByDesc('id')
                    ->withWhereHas('space', function (Builder|HasOne $spaceQuery) use ($companyId): void {
                        $spaceQuery
                            ->withWhereHas('community:comid,comname')
                            ->whereCompanyId($companyId)
                            ->whereNull('deleted_at');
                    });
            })
            ->orderByDesc('id')
            ->get();
    }

    /**
     * 確認房仲是否有此戶別委託
     *
     * @param int $companyId 公司 ID
     * @param int $communityId 建案 ID
     * @param string $spaceId 戶別 ID
     * @param string $realEstateAgentUuid 房仲 UUID
     *
     * @return bool
     */
    public function checkHaveEntrust(int $companyId, int $communityId, string $spaceId, string $realEstateAgentUuid): bool
    {
        return RealEstateAgent::notDelete()
            ->alreadyVerify()
            ->whereUuid($realEstateAgentUuid)
            ->withWhereHas('entrust', function (Builder|HasMany $entrustQuery) use ($companyId, $communityId, $spaceId) {
                $entrustQuery
                    ->where('company_id', $companyId)
                    ->where('community_id', $communityId)
                    ->where('space_id', $spaceId)
                    ->entrustOn()
                    ->isOpenTime();
            })
            ->exists();
    }

    /**
     * 取得房仲擁有的房屋物件
     * @param int $realEstateAgentId
     * @return \Illuminate\Support\Collection
     */
    public function fetchProperty(int $realEstateAgentId): Collection
    {
        return RealEstateAgent::notDelete()
            ->alreadyVerify()
            ->where('id', $realEstateAgentId)
            ->withWhereHas('entrust', function (Builder|HasMany $entrustQuery): void {
                $entrustQuery
                    ->select('id', 'uuid', 'real_estate_agent_id', 'space_id')
                    ->entrustOn()
                    ->isOpenTime()
                    ->whereHas('agent', function (Builder|BelongsTo $agentQuery): void {
                        $agentQuery->has('authorize');
                    })
                    ->withWhereHas('space', function (Builder|HasOne $spaceQuery): void {
                        $spaceQuery
                            ->select(
                                'space_id',
                                'district_name',
                                'building_name',
                                'staircase_name',
                                'floor_name',
                                'household_name'
                            )
                            ->has('company')
                            ->has('community')
                            ->whereNull('deleted_at')
                            ->withWhereHas('property', function (Builder|HasMany $propertyQuery): void {
                                $propertyQuery
                                    ->enableOn()
                                    ->orderByDesc('id');
                            });
                    });
            })
            ->get();
    }
}
