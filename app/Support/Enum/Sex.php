<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum Sex: string
{
    use \App\Support\Trait\Enum\Convert;

    case man = '男性';

    case female = '女性';

    case other = '其他';
}
