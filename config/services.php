<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'ai_health' => [
        'endpoint' => env('AI_HEALTH_ENDPOINT'),
        'api_key' => env('AI_HEALTH_API_KEY'),
        'timeout' => env('AI_HEALTH_TIMEOUT', 60),
        'verify_ssl' => filter_var(env('AI_HEALTH_VERIFY_SSL', true), FILTER_VALIDATE_BOOLEAN),
    ],

    'ai_doctor' => [
        'provider' => env('AI_DOCTOR_PROVIDER', 'openai'),
        'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
        'api_key' => env('OPENAI_API_KEY'),
        'model' => env('AI_DOCTOR_MODEL', 'gpt-5.4-mini'),
        'timeout' => env('AI_DOCTOR_TIMEOUT', 45),
        'verify_ssl' => filter_var(env('AI_DOCTOR_VERIFY_SSL', true), FILTER_VALIDATE_BOOLEAN),
    ],

    'provider_catalog' => [
        'base_url' => env('PROVIDER_CATALOG_BASE_URL', 'http://127.0.0.1:8000/api/internal/v1'),
        'token' => env('PROVIDER_CATALOG_SYNC_TOKEN'),
        'timeout_seconds' => env('PROVIDER_CATALOG_SYNC_TIMEOUT', 20),
        'request_sync_cooldown_seconds' => env('PROVIDER_CATALOG_REQUEST_SYNC_COOLDOWN', 300),
    ],

];
