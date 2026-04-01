<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum AcceptanceMessage: string
{
    case SUCCESS = '驗收成功';

    case FAILS = '驗收失敗';
}
