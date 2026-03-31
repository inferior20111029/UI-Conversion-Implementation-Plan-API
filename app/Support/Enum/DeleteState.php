<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum DeleteState: int
{
    case NOT_DELETE = 0;

    case DELETE = 1;
}
