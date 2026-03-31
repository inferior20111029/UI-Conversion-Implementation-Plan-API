<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum HousePlanningType: string
{
    use \App\Support\Trait\Enum\Convert;

    case residential = '住宅';

    case detachedHouse = '整層住家';

    case suite = '套房';

    case independentSuite = '獨立套房';

    case store = '店面';

    case officeBuilding = '辦公';

    case residenceOffice = '住辦';
}
