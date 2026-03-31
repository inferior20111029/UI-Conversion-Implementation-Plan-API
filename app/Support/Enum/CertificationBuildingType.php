<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum CertificationBuildingType: string
{
    use \App\Support\Trait\Enum\Convert;

    case BERS  = 'BERS'; // 建築能效標章

    case BERSe = 'BERSe'; // 既有建築能效標章

    case BERSn = 'BERSn'; // 新建建築能效標章

    case BCFD = 'BCFD'; // 建築碳足跡認證

    case LEED = 'LEED'; // LEED綠建築標章

    case WELL = 'WELL'; // 國際WELL健康建築認證
}
