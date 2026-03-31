<?php

declare(strict_types=1);

namespace App\Services\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

use Symfony\Component\HttpFoundation\Response;
use Awobaz\Compoships\Database\Eloquent\Relations\HasMany;

use App\Support\Enum\EnableState;
use App\Support\Enum\AccessMessage;
use App\Support\Abstract\Service;
use App\Support\Constants\ApiAccess;
use App\Support\Constants\ApiHeader;

use App\Support\EzPlus\User;

use Home\Models\Login\LoginUserHasRole;
use Home\Models\Twibm20080519\Community;

use Home\AuthPermission\Permission;

use Home\Repositories\Login\LoginLicense\LoginLicenseRepositoryEloquent;
use Home\Repositories\Login\LoginUserHasRole\LoginUserHasRoleRepositoryEloquent;
use Home\Repositories\Login\LoginBuildingHasUser\LoginBuildingHasUserRepositoryEloquent;
use Home\Repositories\Login\LoginUserHasFunction\LoginUserHasFunctionRepositoryEloquent;
use Home\Repositories\Login\LoginUser\LoginUserRepositoryEloquent;


use Home\Repositories\Twibm20080519\Community\CommunityRepositoryEloquent;

final class PassportService extends Service
{
    /**
     * 快取過期分鐘
     * @var int
     */
    public const CACHE_EXPIRED_MINUTE = 3;

    /**
     * 登入使用者
     *
     * @param \Illuminate\Http\Request $request Request
     *
     * @return void
     */
    public function execute(Request $request): void
    {
        $accessToken = $this->fetchAccessToken($request);
        $user = $this->fetchUser($accessToken);
dd( $user );
        $companyId = (int) data_get($user, 'company_id');
        $userId = (int) data_get($user, 'user_id');

        // 檢查軟體權限
//         $this->checkLicense($companyId);

        // 取出擁有的建案資料
        $hasCommunities = $this->fetchUserHasCommunities($userId);
        $communitiesIds = $hasCommunities->pluck('building_id')->unique()->all();

        // 確認是否可以使用此建案
        $communityId = (int) $request->header(ApiHeader::COMMUNITY_ID_HEADER_KEY);
//        $this->checkUserCanAccessCommunity($communityId, $communitiesIds);

        $attachData = [
            'mode' => $this->fetchAuthMode($communityId),
            'community' => $this->fetchCommunities($communitiesIds),
            'community_id' => $communityId,
            'auth_company' => $this->canAccessCompany($companyId, $userId),
            'access_token' => $accessToken
        ];

        $GLOBALS[ApiAccess::GLOBAL_AUTH_KEY] = [...$user, ...$attachData];
    }

    /**
     * 取得 Access Token
     *
     * @param \Illuminate\Http\Request $request Request
     *
     * @return string
     */
    public function fetchAccessToken(Request $request): string
    {
        $accessToken = $request->bearerToken();

        if (!empty($accessToken)) {
            return $accessToken;
        }

        abort(Response::HTTP_FORBIDDEN);
    }

    /**
     * 取得人員資料
     *
     * @param string $accessToken Access Token
     * @throws \App\Exceptions\ApiException
     *
     * @return array|null
     */
    public function fetchUser(string $accessToken): ?array
    {
        if (Cache::has($accessToken)) {
            return Cache::get($accessToken);
        }

        $user = User::fetch($accessToken);

        if ($user['provider'] === 'agent') {
            $user['user_id'] = (int) (new LoginUserRepositoryEloquent())
                ->getLoginUserByAccount($user['ausername'])
                ->user_id;
        }

        if (!empty($user) && (!empty($user['user_id']) || !empty($user['agent_id'])) && !empty($user['company_id'])) {
            Cache::put($accessToken, $user, now()->addMinutes(self::CACHE_EXPIRED_MINUTE));

            return $user;
        }

        $this->fails(AccessMessage::loginFail->value, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * 檢查軟體權限
     *
     * @param integer $companyId 公司 ID
     * @throws \App\Exceptions\ApiException
     *
     * @return void
     */
    public function checkLicense(int $companyId): void
    {
        $license = (bool) (new LoginLicenseRepositoryEloquent())
            ->getLoginLicenses([
                'with' => ['loginLicenseHasFunctions' => function (HasMany $query): void {
                    $query
                        ->select('license_id')
                        ->where('code', ApiAccess::LICENSE_CODE)
                        ->where('action', ApiAccess::LICENSE_ACTION);
                }],
                'company_id' => $companyId,
            ])->first()?->loginLicenseHasFunctions?->isNotEmpty();

        if (false === $license) {
            $this->fails(AccessMessage::licenseReject->value, Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * 取得使用者擁有的建案資料
     *
     * @param integer $userId 使用者 ID
     *
     * @return \Illuminate\Support\Collection
     */
    public function fetchUserHasCommunities(int $userId): Collection
    {
        $cacheKey = implode('-', [
            $userId,
            'community',
            'cache'
        ]);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $community = (new LoginBuildingHasUserRepositoryEloquent())
            ->getLoginBuildingHasMembers([
                'user_id' => $userId,
                'code' => ApiAccess::LICENSE_CODE,
                'action' => ApiAccess::LICENSE_ACTION,
            ]);

        Cache::put($cacheKey, $community, now()->addMinutes(self::CACHE_EXPIRED_MINUTE));

        return $community;
    }

    /**
     * 檢查是否可以瀏覽此建案資料
     *
     * @param integer $communityId 建案 ID
     * @param array $communitiesIds 有權限的建案 IDs
     * @throws \App\Exceptions\ApiException
     *
     * @return void
     */
    public function checkUserCanAccessCommunity(int $communityId, array $communitiesIds): void
    {
        if (0 === $communityId) {
            return;
        }

        if (in_array($communityId, $communitiesIds)) {
            return;
        }

        $this->fails(AccessMessage::communityForbidden->value, Response::HTTP_FORBIDDEN);
    }

    /**
     * 取得建案資料
     *
     * @param array $communitiesId 建案 IDs
     *
     * @return array
     */
    public function fetchCommunities(array $communitiesIds): array
    {
        return (new CommunityRepositoryEloquent())
            ->getCommunitys([
                'comids' => $communitiesIds,
                'status' => EnableState::ENABLE->value,
                'sort' => 'sort',
                'order' => 'desc'
            ])
            ->map(function (Community $community): array {
                return ['community_id' => $community->comid] + $community->only('comname', 'code');
            })
            ->toArray();
    }

    /**
     * 取得驗證模式 (公司型或建案型)
     *
     * @param integer $communityId 建案 ID
     *
     * @return string
     */
    public function fetchAuthMode(int $communityId): string
    {
        if (0 === $communityId) {
            return Permission::TYPE_GENERAL;
        }

        return Permission::TYPE_BUILDING;
    }

    /**
     * 確認使用者是否可以切換成公司型
     *
     * @param integer $companyId 公司 ID
     * @param integer $userId 使用者 ID
     *
     * @return boolean
     */
    public function canAccessCompany(int $companyId, int $userId): bool
    {
        $checkUserCompanyPermission = (new LoginUserHasFunctionRepositoryEloquent())
            ->getTotalLoginUserHasFunctions([
                'type' => Permission::TYPE_GENERAL,
                'company_id' => $companyId,
                'building_id' => 0,
                'user_id' => $userId,
                'code' => ApiAccess::LICENSE_CODE,
                'action' => ApiAccess::LICENSE_ACTION
            ]);

        if (0 === $checkUserCompanyPermission) {
            return (new LoginUserHasRoleRepositoryEloquent())
                ->getLoginUserHasRoles([
                    'type' => Permission::TYPE_GENERAL,
                    'company_id'  => $companyId,
                    'building_id' => 0,
                    'user_id' => $userId,
                    'with' => ['loginRoleHasFunctions' => function (HasMany $query): void {
                        $query
                            ->select('role_id')
                            ->where('code', ApiAccess::LICENSE_CODE)
                            ->where('action', ApiAccess::LICENSE_ACTION);
                    }]
                ])
                ->filter(fn(LoginUserHasRole $role): bool => $role->loginRoleHasFunctions->isNotEmpty())
                ->isNotEmpty();
        }

        return true;
    }

    /**
     * 確保是使用社區型
     *
     * @param \Illuminate\Http\Request $request
     * @throws \App\Exceptions\ApiException
     *
     * @return void
     */
    public function onlyCommunity(Request $request): void
    {
        $communityId = (int) $request->header(ApiHeader::COMMUNITY_ID_HEADER_KEY);

        if (0 === $communityId) {
            $this->fails('only community', Response::HTTP_FORBIDDEN);
        }
    }
}
