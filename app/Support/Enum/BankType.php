<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum BankType: string
{
    use \App\Support\Trait\Enum\Convert;

    case virtual = '虛擬帳戶';

    case entity  = '實體帳戶';
}
