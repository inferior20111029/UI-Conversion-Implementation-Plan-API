<?php

declare(strict_types=1);

namespace App\Support\Response;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Illuminate\Pagination\LengthAwarePaginator;

use Symfony\Component\HttpFoundation\Response;

use App\Exceptions\ApiException;
use App\Support\Constants\ExceptionsConstants;

class ApiMessage
{
    /**
     * Response 成功訊息
     *
     * @param string $message 回傳的訊息
     * @param array|\Illuminate\Support\Collection|\Illuminate\Support\LazyCollection|\Illuminate\Pagination\LengthAwarePaginator $responseData 回傳的資料
     * @param int $code Http Code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseSuccess(
        string $message,
        array|Collection|LazyCollection|LengthAwarePaginator $responseData,
        int $code
    ): JsonResponse {
        if ($code > Response::HTTP_IM_USED) {
            throw new Exception('the http status code an incorrect response, only return success');
        }

        $response = compact('code', 'message');

        if (is_array($responseData)) {
            $responseData = collect($responseData);
        }

        if ($responseData->isNotEmpty()) {
            $response += ['data' => $responseData];
        }

        return response()->json($response, $code)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    /**
     * throw Exception
     *
     * @param string|array $message 回傳的訊息
     * @param int|string $httpCode 回傳的 Http Code
     * @param mixed $previous Exception Previous
     *
     * @return never
     */
    public function throwException(string|array $message, int|string $httpCode, mixed $previous = null): never
    {
        if (
            0 === $httpCode
            ||
            !is_numeric($httpCode)
            ||
            (is_numeric($httpCode) && intval($httpCode / 100) > 5)
        ) {
            $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        if ($httpCode < Response::HTTP_BAD_REQUEST) {
            throw new Exception('the http status code an incorrect response, only return error');
        }

        if (is_array($message)) {
            $message = implode(ExceptionsConstants::SPLIT_MARK, $message);
        }

        throw new ApiException($message, intval($httpCode), $previous);
    }
}
