<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Constants\ExceptionsConstants;

class ApiException extends Exception
{
    /**
     * Error Exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render(): JsonResponse
    {
        $code = empty($this->getCode())
            ? Response::HTTP_BAD_REQUEST
            : $this->getCode();

        $message = $this->fetchResponseMessage();
        $messageType = gettype($message);

        return response()
            ->json(compact('code', 'messageType', 'message'), $code)
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report(): void
    {
        $excludeCode = [0, 1, 2, 3, 4];

        // 如果 http code 為 0 或 2** 或 4** 不紀錄 Log
        if (in_array(intval($this->getCode() / Response::HTTP_CONTINUE), $excludeCode)) {
            return;
        }

        if (!empty($this->getPrevious())) {
            Log::error($this->getPrevious());

            return;
        }

        $message = $this->fetchResponseMessage();
        $logMessage = is_array($message) ? json_encode($message) : $message;

        Log::error($logMessage);
    }

    /**
     * 取得回傳訊息
     *
     * @return array|string
     */
    private function fetchResponseMessage(): array|string
    {
        $messageData = explode(ExceptionsConstants::SPLIT_MARK, $this->getMessage());
        return 1 < count($messageData)
            ? (array) $messageData
            : (string) collect($messageData)->flatten()->first();
    }
}
