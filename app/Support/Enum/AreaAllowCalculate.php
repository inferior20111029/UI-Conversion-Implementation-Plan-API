<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum AreaAllowCalculate: int
{
    use \App\Support\Trait\Enum\Convert;

    case deny = 0;

    case allow = 1;
}
