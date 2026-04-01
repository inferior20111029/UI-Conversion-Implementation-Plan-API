<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum WhileSoldOut: int
{
    case FALSE = 0;

    case TRUE = 1;
}
