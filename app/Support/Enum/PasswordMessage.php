<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum PasswordMessage: string
{
    case MATCH_FAILS = '原始密碼錯誤';

    case CAN_NOT_MATCH_ORIGINAL = "新密碼不得與原始密碼一致";
}
