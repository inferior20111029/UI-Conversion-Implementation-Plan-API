<?php

declare(strict_types=1);

namespace App\Support\Tool\Crm;

use Illuminate\Support\Arr;

use App\Support\Constants\ApiAccess;

use Home\AuthPermission\Permission;

class CrmAuth
{
    /**
     * 取得使用者資料
     *
     * @param string|integer|null $parameters
     *
     * @return mixed
     */
    public static function user(string|int|null $parameters = null): mixed
    {
        $auth = self::fetchAuth();

        if (!empty($parameters)) {
            return data_get($auth, "{$parameters}");
        }

        return empty($auth) ? null : (object) $auth;
    }

    /**
     * 取得特定登入資料
     *
     * @param array $parameters
     *
     * @return array
     */
    public static function only(...$parameters): array
    {
        $auth = self::fetchAuth() ?? [];

        return Arr::only($auth, Arr::map($parameters, 'strval'));
    }

    /**
     * 確認當前登入者模式是否為公司型
     *
     * @return boolean
     */
    public static function isCompany(): bool
    {
        return Permission::TYPE_GENERAL === self::user('mode');
    }

    /**
     * 確認當前登入者模式是否為社區型
     *
     * @return boolean
     */
    public static function isCommunity(): bool
    {
        return Permission::TYPE_BUILDING === self::user('mode');
    }

    /**
     * 取得社區資料
     *
     * @param integer $communityId 社區 ID
     *
     * @return array|null
     */
    public static function getCommunity(int $communityId = 0): ?array
    {
        $community = (array) self::user('community');

        if (0 !== $communityId) {
            return self::filterCommunity($community, $communityId);
        }

        return $community;
    }

    /**
     * 取得當前社區資料
     *
     * @param string ...$key 資料 array key
     *
     * @return mixed
     */
    public static function currentCommunity(string ...$key): mixed
    {
        $community = (array) self::user('community');
        $communityId = (int) self::user('community_id');

        $current = self::filterCommunity($community, $communityId);

        if (!empty($key)) {
            return 1 === count($key)
                ? data_get($current, $key)
                : Arr::only((array) $current, $key);
        }

        return $current;
    }

    /**
     * 是否可以瀏覽公司型
     *
     * @return boolean
     */
    public static function canAccessCompany(): bool
    {
        return (bool) self::user('auth_company');
    }

    /**
     * 篩選社區資料
     *
     * @param array $community 社區資料
     * @param integer $communityId 社區 ID
     *
     * @return array|null
     */
    private static function filterCommunity(array $community, int $communityId): ?array
    {
        return Arr::first($community, fn(array $value): bool => $communityId === data_get($value, 'community_id'));
    }

    /**
     * 取得登入資料
     * @return array|null
     */
    private static function fetchAuth(): ?array
    {
        return data_get($GLOBALS, ApiAccess::GLOBAL_AUTH_KEY);
    }
}
