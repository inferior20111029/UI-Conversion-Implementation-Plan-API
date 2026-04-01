<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum ContractNotifyType: string
{
    use \App\Support\Trait\Enum\Convert;

    case customization = "自行輸入";

    case monthly = "每一個月";

    case beforeEnd = "合約結束前一個月";
}
