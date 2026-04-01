<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Response\ApiMessage;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="仲介管理系統 API 文件",
 *      description="L5 Swagger OpenApi description",
 *      @OA\License(
 *         name="Apache 2.0",
 *         url="https://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\SecurityScheme(
 *      securityScheme="Community-Id-Header",
 *      in="header",
 *      name="Community-Id-Header",
 *      type="apiKey",
 * )
 *
 * @OA\SecurityScheme(
 *      securityScheme="Authorization",
 *      type="http",
 *      scheme="bearer",
 * )
 *
 * @OA\Server(
 *      url="/api/v1",
 *      description="本地端後台 API"
 * )
 *
 *  @OA\Server(
 *      url="/laravel-leasehold/api/v1",
 *      description="PPMS API"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;

    /**
     * Response 成功訊息
     *
     * @param string $message 回傳的訊息
     * @param array|\Illuminate\Support\Collection|\Illuminate\Support\LazyCollection $responseData 回傳的資料
     * @param int $httpCode Http Code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success(
        string $message,
        array|Collection|LazyCollection $responseData = [],
        int $httpCode = Response::HTTP_OK
    ): JsonResponse {
        return (new ApiMessage())->responseSuccess($message, $responseData, $httpCode);
    }

    /**
     * 回傳失敗訊息
     *
     * @param string|array $message 回傳的訊息
     * @param int|string $httpCode 回傳的 Http Code
     * @param null|Throwable $previous Exception Previous
     *
     * @return never
     */
    protected function fails(string|array $message, int|string $httpCode = Response::HTTP_BAD_REQUEST, ?Throwable $previous = null): never
    {
        (new ApiMessage())->throwException($message, $httpCode, $previous);
    }
}
