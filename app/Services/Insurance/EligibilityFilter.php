<?php

namespace App\Services\Insurance;

use App\Models\InsurancePlan;
use App\Support\Insurance\Data\RankablePetProfileData;
use App\Support\Pets\PetBreedMatcher;
use Carbon\Carbon;

class EligibilityFilter
{
    public function evaluate(InsurancePlan $plan, RankablePetProfileData $petProfile): array
    {
        $reasonCodes = [];
        $coverageRule = $plan->coverage_rule_snapshot ?? [];
        $eligibility = $plan->eligibility_snapshot;

        $species = $petProfile->species ?: null;
        $breed = $petProfile->breed ?: null;
        $ageMonths = $this->ageInMonths($petProfile->birthDate);
        $ageYears = $this->ageInYears($petProfile->birthDate);

        $supportedSpecies = $coverageRule['eligible_species'] ?? $eligibility->speciesSupported;
        if ($species !== null && $supportedSpecies !== [] && ! $this->containsNormalized($supportedSpecies, $species)) {
            $reasonCodes[] = 'species_not_supported';
        }

        $eligibleBreeds = $coverageRule['eligible_breeds'] ?? $eligibility->breedRules;
        if ($breed !== null && $eligibleBreeds !== [] && ! PetBreedMatcher::matches((array) $eligibleBreeds, $breed)) {
            $reasonCodes[] = 'breed_not_supported';
        }

        if ($eligibility->requiresMicrochip === true && ! $petProfile->hasMicrochip) {
            $reasonCodes[] = 'microchip_required';
        }

        $minAgeMonths = isset($coverageRule['min_age_months']) ? (int) $coverageRule['min_age_months'] : $eligibility->minAgeMonths;
        if ($minAgeMonths !== null && $ageMonths !== null && $ageMonths < $minAgeMonths) {
            $reasonCodes[] = 'below_minimum_age';
        }

        $maxAgeYears = isset($coverageRule['max_age_years']) ? (int) $coverageRule['max_age_years'] : $eligibility->maxAgeYears;
        if ($maxAgeYears !== null && $ageYears !== null && $ageYears > $maxAgeYears) {
            $reasonCodes[] = 'above_maximum_age';
        }

        return [
            'eligible' => $reasonCodes === [],
            'reason_codes' => $reasonCodes,
        ];
    }

    private function containsNormalized(array $haystack, string $needle): bool
    {
        return in_array(strtolower($needle), array_map(fn (mixed $value): string => strtolower((string) $value), $haystack), true);
    }

    private function ageInYears(?string $birthDate): ?int
    {
        if ($birthDate === null || $birthDate === '') {
            return null;
        }

        return Carbon::parse($birthDate)->age;
    }

    private function ageInMonths(?string $birthDate): ?int
    {
        if ($birthDate === null || $birthDate === '') {
            return null;
        }

        return (int) Carbon::parse($birthDate)
            ->startOfDay()
            ->diffInMonths(now()->copy()->startOfDay());
    }
}
