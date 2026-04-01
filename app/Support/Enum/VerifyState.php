<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum VerifyState: int
{
    case NOT_YET = 0;

    case ALREADY = 1;
}
