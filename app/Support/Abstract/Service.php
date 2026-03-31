<?php

declare(strict_types=1);

namespace App\Support\Abstract;

use Throwable;
use Symfony\Component\HttpFoundation\Response;

use App\Support\Response\ApiMessage;

abstract class Service
{
    use \App\Support\Trait\Paginate\PaginateTrait;

    /**
     * 回傳失敗訊息
     *
     * @param string|array $message 回傳的訊息
     * @param int|string $httpCode 回傳的 Http Code
     * @param null|Throwable $previous Exception Previous
     *
     * @return never
     */
    protected function fails(
        string|array $message,
        int|string $httpCode = Response::HTTP_BAD_REQUEST,
        ?Throwable $previous = null
    ): never {
        (new ApiMessage())->throwException($message, $httpCode, $previous);
    }
}
