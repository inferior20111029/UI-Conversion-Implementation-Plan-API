<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum ConfigurationType: string
{
    use \App\Support\Trait\Enum\Convert;
    case building = '棟';

    case privacy  = '戶';

    case public = '公設';

    case district = '區';

    case floor = '樓';

    case staircase = '梯';
}
