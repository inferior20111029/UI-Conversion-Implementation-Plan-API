<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum CrmLayoutType: int
{
    use \App\Support\Trait\Enum\Convert;

    case apartment = 0; // 公寓

    case house = 1; // 透天
}
