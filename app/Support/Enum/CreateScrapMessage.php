<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum CreateScrapMessage: string
{
    case SUCCESS = '報廢成功';

    case FAILS = '報廢失敗';
}
