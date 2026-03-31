<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum CarApplicationType: int
{
    use \App\Support\Trait\Enum\Convert;

    case STATUTORY_PARKING_SPACES = 0; // 法定車位

    case ADDITIONAL_PARKING_SPACES = 1; // 增設車位

    case INCENTIVE_PARKING_SPACES = 2; // 獎勵車位

    case HANDICAPPED_PARKING = 3; // 殘障車位

    case VIP_GUESTS_ONLY = 4; // 訪客貴賓專用車位
}
