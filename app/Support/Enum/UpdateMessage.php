<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum UpdateMessage: string
{
    case SUCCESS = '修改成功';

    case FAILS = '修改失敗';
}
