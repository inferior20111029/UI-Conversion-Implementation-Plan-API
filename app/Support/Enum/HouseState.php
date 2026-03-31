<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum HouseState: string
{
    use \App\Support\Trait\Enum\Convert;

    case standardConfiguration = '標配';

    case roughcast = '毛胚';

    case decoration = '裝潢';
}
