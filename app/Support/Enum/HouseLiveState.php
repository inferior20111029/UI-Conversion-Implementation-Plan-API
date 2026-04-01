<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum HouseLiveState: string
{
    use \App\Support\Trait\Enum\Convert;

    case selfOccupied = '自住';

    case rented = '已出租';

    case vacantHouse = '空屋';
}
