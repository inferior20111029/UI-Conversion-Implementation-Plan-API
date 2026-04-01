<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum CarType: int
{
    use \App\Support\Trait\Enum\Convert;
    case MOTORCYCLE = 0; // 機車
    case CAR = 1; // 汽車
}
