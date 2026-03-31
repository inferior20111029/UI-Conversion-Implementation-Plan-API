<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum ContractPersonType: string
{
    use \App\Support\Trait\Enum\Convert;

    case housemate = '同住人';

    case surety = '保證人';
}
