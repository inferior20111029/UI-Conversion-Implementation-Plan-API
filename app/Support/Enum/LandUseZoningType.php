<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum LandUseZoningType: string
{
    use \App\Support\Trait\Enum\Convert;

    case residence = '住宅區';

    case commercial = '商業區';
}
