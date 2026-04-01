<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum BillFeesType: string
{
    use \App\Support\Trait\Enum\Convert;

    case contractPrice = "合約金額";

    case carparkPrice = "車位金額";

    case managementFee = "管理費";
}
