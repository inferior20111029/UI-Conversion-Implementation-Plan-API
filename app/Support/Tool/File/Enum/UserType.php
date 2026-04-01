<?php

declare(strict_types=1);

namespace App\Support\Tool\File\Enum;

enum UserType: string
{
    case CRM = 'crm';

    case LEASEHOLD = 'leasehold';
}
