<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum ParkingAttribute: string
{
    case sale = '銷售中';

    case private = '私車位';

    case public = '公車位';
}
