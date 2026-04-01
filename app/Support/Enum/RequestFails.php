<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum RequestFails: string
{
    case CAN_NOT_DUPLICATE_REGISTRATION = '無法重複註冊';

    case NATION_ID_NUMBER_FORMAT_ERROR = '身份證字號格式錯誤';

    case NATION_ID_NUMBER_ALREADY_EXISTS = '此身份證字號已經存在';

    case NOT_FOUND_IDENTIFICATION_CODE = '查無識別碼';

    case NOT_FOUND_BANK_CODE = '查無銀行代碼';

    case UNABLE_TO_ASSIGN_THIS_REAL_ESTATE_AGENT = '無法指派該仲介，委託時間未到';

    case NO_LISTING_INFORMATION_FOUND = '查無房屋上架資料';

    case FORMAT_NEED_UUID_OR_BASE64_IMAGE = '格式必須為 UUID 或是 Base64 圖片';
}
