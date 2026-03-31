<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum CarParkingUseDirection: string
{
    use \App\Support\Trait\Enum\Convert;

    case rent = '租賃';

    case public = '共用';

    case private = '自用';
}
