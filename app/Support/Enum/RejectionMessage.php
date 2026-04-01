<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum RejectionMessage: string
{
    case SUCCESS = '驗退成功';

    case FAILS = '驗退失敗';
}
