<?php

namespace App\Support\Insurance\Data;

use JsonSerializable;

class EligibilitySnapshotData implements InsuranceDataObject, JsonSerializable
{
    public function __construct(
        public array $speciesSupported = [],
        public array $breedRules = [],
        public ?int $minAgeMonths = null,
        public ?int $maxAgeYears = null,
        public ?int $renewableMaxAgeYears = null,
        public ?bool $requiresMicrochip = null,
        public ?bool $requiresRegistration = null,
        public array $excludedPetUsages = [],
    ) {
    }

    public static function fromArray(array $payload): static
    {
        return new static(
            speciesSupported: array_values((array) ($payload['species_supported'] ?? [])),
            breedRules: array_values((array) ($payload['breed_rules'] ?? [])),
            minAgeMonths: isset($payload['min_age_months']) ? (int) $payload['min_age_months'] : null,
            maxAgeYears: isset($payload['max_age_years']) ? (int) $payload['max_age_years'] : null,
            renewableMaxAgeYears: isset($payload['renewable_max_age_years']) ? (int) $payload['renewable_max_age_years'] : null,
            requiresMicrochip: array_key_exists('requires_microchip', $payload) ? (bool) $payload['requires_microchip'] : null,
            requiresRegistration: array_key_exists('requires_registration', $payload) ? (bool) $payload['requires_registration'] : null,
            excludedPetUsages: array_values((array) ($payload['excluded_pet_usages'] ?? [])),
        );
    }

    public function supportsSpecies(?string $species): bool
    {
        if ($species === null || $this->speciesSupported === []) {
            return true;
        }

        return in_array(strtolower($species), array_map('strtolower', $this->speciesSupported), true);
    }

    public function supportsBreed(?string $breed): bool
    {
        if ($breed === null || $this->breedRules === []) {
            return true;
        }

        return in_array(strtolower($breed), array_map('strtolower', $this->breedRules), true);
    }

    public function toArray(): array
    {
        return [
            'species_supported' => $this->speciesSupported,
            'breed_rules' => $this->breedRules,
            'min_age_months' => $this->minAgeMonths,
            'max_age_years' => $this->maxAgeYears,
            'renewable_max_age_years' => $this->renewableMaxAgeYears,
            'requires_microchip' => $this->requiresMicrochip,
            'requires_registration' => $this->requiresRegistration,
            'excluded_pet_usages' => $this->excludedPetUsages,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
