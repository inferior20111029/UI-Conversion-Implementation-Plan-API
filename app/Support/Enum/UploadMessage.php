<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum UploadMessage: string
{
    case SUCCESS = '上傳成功';

    case FAILS = '上傳失敗';
}
