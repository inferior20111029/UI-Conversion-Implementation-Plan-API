<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum ContractType: string
{
    use \App\Support\Trait\Enum\Convert;

    case space = '戶別';

    case carpark = '車位';
}
