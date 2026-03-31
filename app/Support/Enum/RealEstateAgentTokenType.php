<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum RealEstateAgentTokenType: string
{
    case verify = '驗證帳號';

    case changePassword = '更改密碼';
}
