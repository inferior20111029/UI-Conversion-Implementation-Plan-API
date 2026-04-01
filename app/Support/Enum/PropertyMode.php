<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum PropertyMode: string
{
    use \App\Support\Trait\Enum\Convert;

    // 所有權人
    case inhabitant = 'inhabitant';

    // 立約人
    case promiser = 'related.promiser';

    // 個人
    case personal = 'property.personal';

    // 繳款人
    case paymenter = 'related.paymenter';

    // 配偶
    case spouse = 'related.spouse';

    /**
     * 售出的人員類型
     *
     * @return array
     */
    public static function soldType(): array
    {
        return [
            PropertyMode::inhabitant->value,
            PropertyMode::promiser->value
        ];
    }
}
