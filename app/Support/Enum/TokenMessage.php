<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum TokenMessage: string
{
    case FETCH_SUCCESS = 'Access Token 取得成功';
    case EXPIRED = 'Access Token 已過期';
    case INVALID = '無效的 Access Token';
    case IS_BLACK = '已禁止的 Access Token';
}
