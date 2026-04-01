<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum HousePlanning: string
{
    use \App\Support\Trait\Enum\Convert;

    case apartment = '公寓';

    case villa = '別墅';

    case detached = '透天厝';

    case elevatorBuilding = '電梯大樓';

    case store = '店面 (店鋪)';
}
