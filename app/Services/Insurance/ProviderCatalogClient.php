<?php

namespace App\Services\Insurance;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ProviderCatalogClient
{
    public function fetchPlansPage(?string $updatedSince, int $page = 1, int $perPage = 100): array
    {
        $baseUrl = rtrim((string) config('services.provider_catalog.base_url', ''), '/');
        $token = (string) config('services.provider_catalog.token', '');

        if ($baseUrl === '' || $token === '') {
            throw new RuntimeException('Provider catalog sync credentials are not configured.');
        }

        $response = $this->request($baseUrl, $token)
            ->get('/catalog/plans', array_filter([
                'updated_since' => $updatedSince,
                'page' => $page,
                'per_page' => $perPage,
            ], fn (mixed $value): bool => $value !== null));

        $response->throw();

        $payload = $response->json();
        if (($payload['status'] ?? null) !== 'success') {
            throw new RuntimeException('Provider catalog export returned an unexpected payload.');
        }

        return $payload;
    }

    private function request(string $baseUrl, string $token): PendingRequest
    {
        return Http::acceptJson()
            ->baseUrl($baseUrl)
            ->timeout((int) config('services.provider_catalog.timeout_seconds', 20))
            ->withHeaders([
                'X-Internal-Sync-Token' => $token,
            ]);
    }
}
