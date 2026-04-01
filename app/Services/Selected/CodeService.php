<?php

declare(strict_types=1);

namespace App\Services\Selected;

use App\Support\Abstract\Service;

final class CodeService extends Service
{
    /**
     * 取得代碼資料
     *
     * @param string $enum
     *
     * @return array
     */
    public function execute(string $enum): array
    {
        return array_map(function (string $value, string $names): array {
            return [
                'code' => $names,
                'name' => $value
            ];
        }, $enum::values(), $enum::names());
    }
}
