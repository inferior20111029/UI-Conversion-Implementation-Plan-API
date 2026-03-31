<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum CarParkingAttribute: string
{
    use \App\Support\Trait\Enum\Convert;

    case sell = '銷售中';

    case private = '私車位';
}
