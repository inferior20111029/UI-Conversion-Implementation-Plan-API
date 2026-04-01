<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum ExclusiveAreaName: string
{
    use \App\Support\Trait\Enum\Convert;

    case indoor = '室內面積';

    case awning = '室內雨遮面積';

    case balcony = '室內陽台面積';
}
