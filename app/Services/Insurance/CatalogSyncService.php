<?php

namespace App\Services\Insurance;

use App\Models\InsurancePlan;
use App\Models\InsuranceProvider;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Log;
use Throwable;

class CatalogSyncService
{
    public function __construct(
        private readonly ProviderCatalogClient $providerCatalogClient,
    ) {
    }

    public function sync(bool $full = false, int $perPage = 100): array
    {
        $updatedSince = $full ? null : $this->incrementalCursor();
        $page = 1;
        $lastPage = 1;
        $seenPlanIds = [];
        $catalogVersion = null;
        $stats = [
            'fetched' => 0,
            'providers_created' => 0,
            'providers_updated' => 0,
            'plans_created' => 0,
            'plans_updated' => 0,
            'failed_records' => 0,
            'reconciled' => 0,
        ];

        do {
            $payload = $this->providerCatalogClient->fetchPlansPage($updatedSince, $page, $perPage);
            $catalogVersion = $payload['meta']['catalog_generated_at'] ?? $catalogVersion;
            $lastPage = (int) ($payload['meta']['last_page'] ?? 1);

            foreach ((array) ($payload['data'] ?? []) as $record) {
                try {
                    $sourcePlanId = (int) ($record['source_plan_id'] ?? 0);
                    if ($sourcePlanId === 0) {
                        throw new \RuntimeException('Missing source_plan_id');
                    }

                    $seenPlanIds[] = $sourcePlanId;
                    $stats['fetched']++;
                    $this->syncRecord($record, $stats);
                } catch (Throwable $exception) {
                    $stats['failed_records']++;
                    Log::warning('Insurance catalog sync skipped invalid plan record.', [
                        'exception' => $exception->getMessage(),
                        'record' => $record,
                    ]);
                }
            }

            $page++;
        } while ($page <= $lastPage);

        if ($full) {
            $stats['reconciled'] = $this->reconcileMissingPlans($seenPlanIds);
        }

        return $stats + [
            'catalog_version' => $catalogVersion,
            'updated_since' => $updatedSince,
        ];
    }

    private function incrementalCursor(): ?string
    {
        $latest = InsurancePlan::query()->max('source_updated_at');
        if ($latest instanceof CarbonInterface) {
            return $latest->toISOString();
        }

        return $latest ? (string) $latest : null;
    }

    private function syncRecord(array $record, array &$stats): void
    {
        $now = now();

        $provider = InsuranceProvider::query()->firstOrNew([
            'source_provider_id' => (int) $record['source_provider_id'],
        ]);
        $providerCreated = ! $provider->exists;
        $provider->name = (string) ($record['provider_name'] ?? 'Unknown Provider');
        $provider->save();
        $stats[$providerCreated ? 'providers_created' : 'providers_updated']++;

        $plan = InsurancePlan::query()->firstOrNew([
            'source_plan_id' => (int) $record['source_plan_id'],
        ]);
        $planCreated = ! $plan->exists;
        $sourceStatus = (string) ($record['status'] ?? 'inactive');

        $plan->fill([
            'insurance_provider_id' => $provider->id,
            'source_provider_id' => (int) $record['source_provider_id'],
            'code' => (string) $record['code'],
            'name' => (string) $record['name'],
            'summary' => $record['summary'] ?? null,
            'plan_type' => $record['plan_type'] ?? null,
            'currency' => (string) ($record['currency'] ?? 'TWD'),
            'annual_premium_min' => (float) ($record['annual_premium_min'] ?? 0),
            'annual_premium_max' => (float) ($record['annual_premium_max'] ?? 0),
            'species_supported' => array_values((array) ($record['species_supported'] ?? [])),
            'terms_url' => $record['terms_url'] ?? null,
            'effective_from' => $record['effective_from'] ?? null,
            'effective_to' => $record['effective_to'] ?? null,
            'scoring_weight_snapshot' => $record['scoring_weight'] ?? [],
            'coverage_rule_snapshot' => $record['coverage_rule'] ?? [],
            'claim_strategy_snapshot' => $record['claim_strategy'] ?? [],
            'target_audience_snapshot' => $record['target_audience'] ?? [],
            'ranking_strategy_snapshot' => $record['ranking_strategy'] ?? [],
            'eligibility_snapshot' => $record['eligibility_snapshot'] ?? [],
            'coverage_summary_snapshot' => $record['coverage_summary_snapshot'] ?? [],
            'comparison_snapshot' => $record['comparison_snapshot'] ?? [],
            'claim_requirement_snapshot' => $record['claim_requirement_snapshot'] ?? [],
            'source_status' => $sourceStatus,
            'source_updated_at' => $record['source_updated_at'] ?? null,
            'algorithm_version' => $record['algorithm_version'] ?? null,
            // v1 has no dedicated manual merchandising override yet, so active source plans
            // should always recover to listable after a successful sync.
            'is_listable' => $sourceStatus === 'active',
            'first_synced_at' => $plan->first_synced_at ?? $now,
            'synced_at' => $now,
            'last_seen_at' => $now,
            'source_deleted_at' => null,
        ]);
        $plan->save();

        $stats[$planCreated ? 'plans_created' : 'plans_updated']++;
    }

    private function reconcileMissingPlans(array $seenPlanIds): int
    {
        $query = InsurancePlan::query();
        if ($seenPlanIds !== []) {
            $query->whereNotIn('source_plan_id', $seenPlanIds);
        }

        $count = (clone $query)->count();

        $query->update([
            'source_status' => 'inactive',
            'is_listable' => false,
            'source_deleted_at' => now(),
            'synced_at' => now(),
        ]);

        return $count;
    }
}
