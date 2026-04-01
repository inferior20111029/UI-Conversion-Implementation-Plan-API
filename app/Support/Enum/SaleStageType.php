<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum SaleStageType: string
{
    use \App\Support\Trait\Enum\Convert;

    case earnestPayment = '斡旋金';

    case deposit = '押金';

    case sale = '已出售';
}
