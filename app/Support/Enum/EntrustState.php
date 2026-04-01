<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum EntrustState: int
{
    case DISABLE = 0;

    case ENABLE = 1;
}
