<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum LoginMessage: string
{
    case FAILS = '登入失敗';

    case LOGOUT_SUCCESS = '登出成功';
}
