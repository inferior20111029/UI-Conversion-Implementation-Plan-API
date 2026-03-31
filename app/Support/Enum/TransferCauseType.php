<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum TransferCauseType: string
{
    use \App\Support\Trait\Enum\Convert;

    case SALE = "買賣";

    case GIFT = "贈予";

    case INHERITANCE = "繼承";

    case PARTITION_OF_INHERITANCE = "分割繼承";

    case WITHDRAWAL_FROM_HOUSEHOLD = "退戶";
}
