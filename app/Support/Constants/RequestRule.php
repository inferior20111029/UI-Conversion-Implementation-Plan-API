<?php

declare(strict_types=1);

namespace App\Support\Constants;

interface RequestRule
{
    /**
     * 公司 ID
     */
    public const COMPANY_ID = [
        'companyId' => 'required|integer|min:1'
    ];

    /**
     * 建案 ID
     */
    public const COMMUNITY_ID = [
        'communityId' => 'required|integer|min:1'
    ];

    /**
     * 戶別 ID
     */
    public const SPACE_ID = [
        'spaceId' => 'required|uuid'
    ];

    /**
     * 開始時間、結束時間
     */
    public const TIME = [
        'startTime' => 'date_format:Y-m-d H:i|nullable',
        'endTime' => 'exclude_if:startTime,null|date_format:Y-m-d H:i|after:startTime|nullable'
    ];

    /**
     * 啟用狀態 0:未啟用, 1:啟用
     */
    public const ENABLE_STATE = [
        'enableState' => 'required|integer|between:0,1'
    ];

    public const PASSWORD = [
        'password' => 'required|string|confirmed|min:6',
        'password_confirmation' => 'required|string'
    ];
}
