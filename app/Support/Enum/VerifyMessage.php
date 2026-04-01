<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum VerifyMessage: string
{
    case SUCCESS = '驗證成功';

    case FAILS = '驗證失敗';

    case ALREADY_VERIFY = '無法重複驗證';
}
