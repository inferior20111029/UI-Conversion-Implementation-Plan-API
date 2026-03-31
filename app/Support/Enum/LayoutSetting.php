<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum LayoutSetting: string
{
    use \App\Support\Trait\Enum\Convert;

    case room = '房間';

    case living_room = '客廳/餐廳';

    case kitchen = '廚房';

    case bathroom = '衛浴';

    case balcony = '陽台';
}
