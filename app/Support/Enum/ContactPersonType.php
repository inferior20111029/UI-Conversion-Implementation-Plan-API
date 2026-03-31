<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum ContactPersonType: string
{
    use \App\Support\Trait\Enum\Convert;

    case LANDLORD  = 'landlord'; // 房東

    case AGENT = 'agent'; // 仲介
}
