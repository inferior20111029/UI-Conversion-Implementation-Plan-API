<?php

declare(strict_types=1);

namespace App\Support\EzPlus;

use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class RealEstateAgentVerifyNotify
{
    public static function execute(string $realEstateAgentUuid): void
    {
        try {
            $response = Http::withOptions(['verify' => false])
                ->post(route('reverb.realEstateAgentVerify'), [
                    'uuid' => $realEstateAgentUuid,
                ]);

            if (false === $response->ok()) {
                Log::channel('reverb')->error($response);
            }
        } catch (Throwable $th) {
            Log::channel('reverb')->error($th);
        }
    }
}
