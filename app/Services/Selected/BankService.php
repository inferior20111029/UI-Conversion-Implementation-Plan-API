<?php

declare(strict_types=1);

namespace App\Services\Selected;

use Illuminate\Support\Arr;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;

use App\Support\Enum\FetchMessage;

final class BankService extends Service
{
    public const JSON_FILE_RESOURCE_PATH = 'json/bank.json';

    /**
     * 取得銀行資料
     *
     * @return array
     */
    public function execute(): array
    {
        $bankData = $this->fetchBankData();
        return $this->fetchResponse($bankData);
    }

    /**
     * 取得銀行資料
     * @throws \App\Exceptions\ApiException
     * @return array
     */
    private function fetchBankData(): array
    {
        if (file_exists(resource_path(self::JSON_FILE_RESOURCE_PATH))) {
            $jsonContent = file_get_contents(resource_path(self::JSON_FILE_RESOURCE_PATH));

            if (str($jsonContent)->isJson()) {
                return json_decode($jsonContent, true);
            }
        }

        $this->fails('銀行列表：' . FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
    }

    /**
     * 取得回傳資料
     *
     * @return array
     */
    private function fetchResponse(array $bankData): array
    {
        return Arr::map($bankData, function (array $value): array {
            return [
                'code' => (string) Arr::get($value, 'Value'),
                'name' => (string) Arr::get($value, 'Key'),
                'shortName' => (string) Arr::get($value, 'Short')
            ];
        });
    }
}
