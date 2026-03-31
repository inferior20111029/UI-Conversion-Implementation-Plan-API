<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum LoginType: string
{
    use \App\Support\Trait\Enum\Convert;

    case realEstateAgent = '房仲';
}
