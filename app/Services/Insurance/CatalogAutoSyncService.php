<?php

namespace App\Services\Insurance;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class CatalogAutoSyncService
{
    private const LAST_RUN_CACHE_KEY = 'insurance_catalog:auto_sync:last_run_at';
    private const LOCK_CACHE_KEY = 'insurance_catalog:auto_sync:lock';

    public function __construct(
        private readonly CatalogSyncService $catalogSyncService,
    ) {
    }

    public function refreshIfStale(?int $cooldownSeconds = null): void
    {
        $cooldownSeconds ??= (int) config('services.provider_catalog.request_sync_cooldown_seconds', 300);
        $now = time();
        $lastRunAt = (int) Cache::get(self::LAST_RUN_CACHE_KEY, 0);

        if ($lastRunAt !== 0 && ($now - $lastRunAt) < $cooldownSeconds) {
            return;
        }

        $lock = Cache::lock(self::LOCK_CACHE_KEY, $cooldownSeconds);
        if (! $lock->get()) {
            return;
        }

        try {
            $lastRunAt = (int) Cache::get(self::LAST_RUN_CACHE_KEY, 0);
            if ($lastRunAt !== 0 && ($now - $lastRunAt) < $cooldownSeconds) {
                return;
            }

            $this->catalogSyncService->sync(full: false, perPage: 100);

            Cache::put(self::LAST_RUN_CACHE_KEY, time(), now()->addDay());
        } catch (Throwable $exception) {
            // Keep serving the existing catalog if sync fails, but throttle retries.
            Log::warning('Insurance catalog auto-sync failed.', [
                'exception' => $exception->getMessage(),
            ]);

            Cache::put(self::LAST_RUN_CACHE_KEY, time(), now()->addSeconds(10));
        } finally {
            $lock->release();
        }
    }
}
