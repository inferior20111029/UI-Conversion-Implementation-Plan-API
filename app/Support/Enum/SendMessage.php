<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum SendMessage: string
{
    case FAILS = '發送失敗';

    case SUCCESS = '發送成功';

    case EMPTY_EMAIL = '沒有可發送的 Email';
}
