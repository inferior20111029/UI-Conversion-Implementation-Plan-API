<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum AccessMessage: string
{
    use \App\Support\Trait\Enum\Convert;

    case loginFail = '登入失敗';

    case licenseReject = '無效的授權';

    case communityForbidden = '無法瀏覽此社區';

    case tokenInvalid = '無效的 Token';
}
