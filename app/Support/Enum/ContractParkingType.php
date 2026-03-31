<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum ContractParkingType: string
{
    use \App\Support\Trait\Enum\Convert;

    case car = "汽車";

    case scooter = "機車";
}
