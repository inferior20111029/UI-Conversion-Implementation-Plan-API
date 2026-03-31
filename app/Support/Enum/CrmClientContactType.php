<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum CrmClientContactType: string
{
    use \App\Support\Trait\Enum\Convert;

    case EMAIL_BACKUP = "email_backup";

    case EMAIL = "email";

    case PHONE = "phone";

    case TELEPHONE = "telephone";
}
