<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum DecorationType: string
{
    use \App\Support\Trait\Enum\Convert;

    case unfinished = '尚未裝潢';

    case basicFitOut = '簡易裝潢';

    case moderateFitOut = '中擋裝潢';

    case luxuryFitOut = '高擋裝潢';
}
