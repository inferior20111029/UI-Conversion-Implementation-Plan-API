<?php

declare(strict_types=1);

namespace App\Support\EzPlus;

use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

use App\Support\Parameter\EmailParameter;

class Email
{
    public static function send(EmailParameter $emailParameter): void
    {
        try {
            $response = Http::withOptions(["verify" => false])
                ->post(route('notify.email'), [
                    'companyID' => $emailParameter->companyId,
                    'title' => $emailParameter->title,
                    'content' => $emailParameter->content,
                    'mailTo' => $emailParameter->mailTo,
                    'attach' => $emailParameter->attach,
                ]);

            if (false === $response->ok()) {
                Log::channel('email')->error($response);
            }
        } catch (Throwable $th) {
            Log::channel('email')->error($th);
        }
    }
}
