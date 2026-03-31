<?php

declare(strict_types=1);

namespace App\Support\EzPlus;

use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class User
{
    /**
     * 取得人員資料
     *
     * @param string $accessToken Access Token
     *
     * @return array|null
     */
    public static function fetch(string $accessToken): ?array
    {
        try {
            $response = Http::withToken($accessToken)
                ->withOptions(['verify' => false])
                ->post(route('laravelPush.oauthVerify'));

            if ($response->ok()) {
                return $response->json('data');
            }

            Log::channel('backendLogin')->warning($response);
        } catch (Throwable $th) {
            Log::error($th);
        }

        return null;
    }
}
