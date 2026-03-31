<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum HouseRentState: string
{
    use \App\Support\Trait\Enum\Convert;

    case rental = '出租';

    case sell = '出售';

    case notYet = '無';
}
