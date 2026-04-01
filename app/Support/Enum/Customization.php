<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum Customization: int
{
    use \App\Support\Trait\Enum\Convert;

    case FALSE = 0;

    case TRUE = 1;
}
