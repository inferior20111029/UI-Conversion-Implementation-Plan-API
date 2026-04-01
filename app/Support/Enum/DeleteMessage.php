<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum DeleteMessage: string
{
    case SUCCESS = '刪除成功';

    case FAILS = '刪除失敗';
}
