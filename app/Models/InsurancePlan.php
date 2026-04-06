<?php

namespace App\Models;

use App\Casts\Insurance\ClaimRequirementSnapshotCast;
use App\Casts\Insurance\ComparisonSnapshotCast;
use App\Casts\Insurance\CoverageSummarySnapshotCast;
use App\Casts\Insurance\EligibilitySnapshotCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InsurancePlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'insurance_provider_id',
        'source_provider_id',
        'source_plan_id',
        'code',
        'name',
        'summary',
        'plan_type',
        'currency',
        'annual_premium_min',
        'annual_premium_max',
        'species_supported',
        'terms_url',
        'effective_from',
        'effective_to',
        'scoring_weight_snapshot',
        'coverage_rule_snapshot',
        'claim_strategy_snapshot',
        'target_audience_snapshot',
        'ranking_strategy_snapshot',
        'eligibility_snapshot',
        'coverage_summary_snapshot',
        'comparison_snapshot',
        'claim_requirement_snapshot',
        'source_status',
        'source_updated_at',
        'algorithm_version',
        'is_listable',
        'first_synced_at',
        'synced_at',
        'last_seen_at',
        'source_deleted_at',
    ];

    protected $casts = [
        'annual_premium_min' => 'float',
        'annual_premium_max' => 'float',
        'species_supported' => 'array',
        'effective_from' => 'date',
        'effective_to' => 'date',
        'scoring_weight_snapshot' => 'array',
        'coverage_rule_snapshot' => 'array',
        'claim_strategy_snapshot' => 'array',
        'target_audience_snapshot' => 'array',
        'ranking_strategy_snapshot' => 'array',
        'eligibility_snapshot' => EligibilitySnapshotCast::class,
        'coverage_summary_snapshot' => CoverageSummarySnapshotCast::class,
        'comparison_snapshot' => ComparisonSnapshotCast::class,
        'claim_requirement_snapshot' => ClaimRequirementSnapshotCast::class,
        'source_updated_at' => 'datetime',
        'is_listable' => 'boolean',
        'first_synced_at' => 'datetime',
        'synced_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'source_deleted_at' => 'datetime',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(InsuranceProvider::class, 'insurance_provider_id');
    }
}
