<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum ImportMessage: string
{
    case SUCCESS = '匯入成功';

    case FAILS = '匯入失敗';
}
