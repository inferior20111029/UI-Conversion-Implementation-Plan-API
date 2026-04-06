<?php

namespace App\Support\Insurance\Data;

use App\Models\InsurancePlan;

class RankablePlanSnapshotData
{
    public function __construct(
        public array $scoringWeightSnapshot,
        public array $coverageRuleSnapshot,
        public array $claimStrategySnapshot,
        public array $targetAudienceSnapshot,
        public array $rankingStrategySnapshot,
        public EligibilitySnapshotData $eligibilitySnapshot,
        public CoverageSummarySnapshotData $coverageSummarySnapshot,
        public ComparisonSnapshotData $comparisonSnapshot,
        public ClaimRequirementSnapshotData $claimRequirementSnapshot,
    ) {
    }

    public static function fromInsurancePlan(InsurancePlan $plan): static
    {
        return new static(
            scoringWeightSnapshot: $plan->scoring_weight_snapshot ?? [],
            coverageRuleSnapshot: $plan->coverage_rule_snapshot ?? [],
            claimStrategySnapshot: $plan->claim_strategy_snapshot ?? [],
            targetAudienceSnapshot: $plan->target_audience_snapshot ?? [],
            rankingStrategySnapshot: $plan->ranking_strategy_snapshot ?? [],
            eligibilitySnapshot: $plan->eligibility_snapshot,
            coverageSummarySnapshot: $plan->coverage_summary_snapshot,
            comparisonSnapshot: $plan->comparison_snapshot,
            claimRequirementSnapshot: $plan->claim_requirement_snapshot,
        );
    }
}
