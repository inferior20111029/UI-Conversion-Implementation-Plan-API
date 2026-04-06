<?php

namespace App\Console\Commands;

use App\Services\Insurance\CatalogSyncService;
use Illuminate\Console\Command;
use Throwable;

class InsuranceCatalogSyncCommand extends Command
{
    protected $signature = 'insurance-catalog:sync {--full : Perform a full sync and reconcile missing plans} {--per-page=100 : Number of records to fetch per page}';

    protected $description = 'Sync provider catalog plans into the consumer insurance projection tables.';

    public function handle(CatalogSyncService $catalogSyncService): int
    {
        try {
            $summary = $catalogSyncService->sync(
                full: (bool) $this->option('full'),
                perPage: (int) $this->option('per-page'),
            );
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->info('Insurance catalog sync completed.');
        foreach ($summary as $key => $value) {
            $this->line(sprintf('%s: %s', $key, is_scalar($value) || $value === null ? (string) $value : json_encode($value)));
        }

        return self::SUCCESS;
    }
}
