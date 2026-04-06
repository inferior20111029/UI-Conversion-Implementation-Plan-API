<?php

namespace App\Support\Insurance\Data;

use JsonSerializable;

class ComparisonSnapshotData implements InsuranceDataObject, JsonSerializable
{
    public function __construct(
        public array $coverageFlags = [],
        public array $waitingPeriodDays = ['major_conditions' => null, 'general_conditions' => null],
        public array $medicalConstraints = [
            'designated_vet_required' => null,
            'skin_hair_outpatient_only' => null,
            'same_incident_window_days' => null,
            'same_incident_max_claim_count' => null,
        ],
        public array $commonExclusions = [],
    ) {
    }

    public static function fromArray(array $payload): static
    {
        return new static(
            coverageFlags: (array) ($payload['coverage_flags'] ?? []),
            waitingPeriodDays: (array) ($payload['waiting_period_days'] ?? ['major_conditions' => null, 'general_conditions' => null]),
            medicalConstraints: (array) ($payload['medical_constraints'] ?? [
                'designated_vet_required' => null,
                'skin_hair_outpatient_only' => null,
                'same_incident_window_days' => null,
                'same_incident_max_claim_count' => null,
            ]),
            commonExclusions: array_values((array) ($payload['common_exclusions'] ?? [])),
        );
    }

    public function toArray(): array
    {
        return [
            'coverage_flags' => $this->coverageFlags,
            'waiting_period_days' => $this->waitingPeriodDays,
            'medical_constraints' => $this->medicalConstraints,
            'common_exclusions' => $this->commonExclusions,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
