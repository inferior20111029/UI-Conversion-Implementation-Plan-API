<?php

declare(strict_types=1);

namespace App\Support\Constants;

interface ApiAccess
{
    /**
     * 軟體代碼
     *
     * @var string
     */
    public const LICENSE_CODE = 'leasehold';

    /**
     * 軟體功能代碼
     *
     * @var string
     */
    public const LICENSE_ACTION = 'leasehold';

    /**
     * 全域變數 key name
     *
     * @var string
     */
    public const GLOBAL_AUTH_KEY = 'crm_auth';
}
