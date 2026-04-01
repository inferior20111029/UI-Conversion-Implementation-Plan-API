<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum PropertyTitleType: string
{
    use \App\Support\Trait\Enum\Convert;

    case committee = "委員";

    case inhabitant = "所有權人";

    case related_main = "主要聯絡人";

    case inhabitant_member = "所有權成員";

    case renter = "承租戶";

    case renter_member = "承租戶成員";

    case related_promiser = "立約人";

    case related_surety = "保證人";

    case related_loaner = "貸款人";

    case related_spouse = "配偶";

    case introducer = "介紹人";

    case property_personal = "個人";

    case property_juristic = "法人";

    case related_paymenter = "繳款人";

    case related_stockholder = "股東";

    case related_commission = "委任關係";

    case related_cohabitant = "同戶關係人";
}