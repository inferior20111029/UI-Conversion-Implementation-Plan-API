<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum DecorationTime: string
{
    use \App\Support\Trait\Enum\Convert;

    case withinHalfYear = '半年內';

    case withinOneYear = '一年內';

    case withinThreeYear = '三年內';

    case thanThreeYears = '三年以上';
}
