<?php

declare(strict_types=1);

namespace App\Support\Abstract;

abstract class QueryFilter
{
    /**
     * 取得 文字 Request 資料
     *
     * @param string $keyName
     *
     * @return string
     */
    protected function fetchStringRequest(string $keyName): string
    {
        return match (request()->method()) {
            'GET' => (string) request()->get($keyName),
            default => ''
        };
    }

    /**
     * 取得陣列 Request 資料
     *
     * @param string $keyName
     * @param string $dataType 資料類型，可以選擇將資料轉換為 int 或是 string，預設為：string
     *
     * @return array
     */
    protected function fetchArrayRequest(string $keyName, string $dataType = 'string'): array
    {
        $method = match ($dataType) {
            'int',
            'integer', => 'intval',
            default => 'strval'
        };

        return match (request()->method()) {
            'GET' => array_values(
                array_unique(
                    array_map($method, (array) request()->get($keyName))
                )
            ),
            default => 0
        };
    }

    /**
     * 取得 整數 Request 資料
     *
     * @param string $keyName
     *
     * @return int
     */
    protected function fetchIntegerRequest(string $keyName): int
    {
        return match (request()->method()) {
            'GET' => request()->integer($keyName),
            default => 0
        };
    }

    /**
     * 確認是否有 int Request
     *
     * @param string $keyName
     *
     * @return bool
     */
    protected function haveIntRequest(string $keyName): bool
    {
        return '' !== $this->fetchStringRequest($keyName);
    }
}
