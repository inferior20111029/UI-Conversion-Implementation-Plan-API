<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum SignatureMessage: string
{
    case unableToRepeatTheSignature = '無法重複簽名';

    case createFails = '簽名建立失敗';

    case notFoundSignatureData = '查無簽名資料';
}
