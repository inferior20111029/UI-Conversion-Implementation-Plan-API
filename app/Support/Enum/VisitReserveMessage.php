<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum VisitReserveMessage: string
{
    case ALREADY_CANCEL = '此預約已經取消';

    case ALREADY_CHECK_IN = '此預約已經完成簽到';

    case CHECK_IN_SUCCESS = '簽到成功';

    case CHECK_IN_FAILS = '簽到失敗';

    case CANCEL_SUCCESS = '取消成功';

    case CANCEL_FAILS = '取消失敗';
}
