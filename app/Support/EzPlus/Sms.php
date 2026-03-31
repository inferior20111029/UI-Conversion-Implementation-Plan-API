<?php

declare(strict_types=1);

namespace App\Support\EzPlus;

use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

use App\Support\Parameter\SmsParameter;

class Sms
{
    public static function send(SmsParameter $smsParameter): void
    {
        try {
            $response = Http::withOptions(["verify" => false])
                ->post(route('notify.sms'), [
                    'companyID' => $smsParameter->companyId,
                    'sendTo' => $smsParameter->sendTo,
                    'message' => $smsParameter->message,
                    'longSend' => $smsParameter->longSend,
                ]);

            if (false === $response->ok()) {
                Log::channel('sms')->warning($response);
            }
        } catch (Throwable $e) {
            Log::channel('sms')->error($e);
        }
    }
}
