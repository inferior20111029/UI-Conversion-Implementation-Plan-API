<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum FetchMessage: string
{
    case SUCCESS = '取得成功';

    case NOT_FOUND = '查無資料';

    case NOT_FOUND_CAR_PARKING_DATA = '查無停車場資料';
}
