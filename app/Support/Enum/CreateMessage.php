<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum CreateMessage: string
{
    case SUCCESS = '建立成功';

    case FAILS = '建立失敗';
}
