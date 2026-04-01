<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum TimeCycle: string
{
    use \App\Support\Trait\Enum\Convert;

    case weekly = '每週';

    case monthly = '每月';

    case yearly = '每年';
}
