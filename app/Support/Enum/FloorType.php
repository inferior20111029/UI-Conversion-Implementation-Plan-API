<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum FloorType: string
{
    use \App\Support\Trait\Enum\Convert;
    case ground = '地上層';

    case underground = '地下層';

    case intermediate = '夾層';

    case protrusion = '突出物';
}
