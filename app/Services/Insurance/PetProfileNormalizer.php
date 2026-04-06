<?php

namespace App\Services\Insurance;

use App\Models\Pet;
use App\Support\Insurance\Data\RankablePetProfileData;

class PetProfileNormalizer
{
    public function normalize(Pet $pet): RankablePetProfileData
    {
        $pet->loadMissing(['healthRecords', 'activities']);

        $medicalHistory = $pet->healthRecords
            ->where('type', 'checkup')
            ->pluck('value')
            ->filter()
            ->map(fn (mixed $value): string => (string) $value)
            ->values()
            ->all();

        $lifestyleTags = $pet->activities
            ->pluck('type')
            ->filter()
            ->map(fn (mixed $value): string => strtolower((string) $value))
            ->unique()
            ->values()
            ->all();

        return new RankablePetProfileData(
            species: strtolower((string) $pet->type),
            breed: $pet->breed ? (string) $pet->breed : null,
            birthDate: $pet->birthday ? $pet->birthday->toDateString() : null,
            microchipNumber: $pet->microchip_number ? (string) $pet->microchip_number : null,
            hasMicrochip: filled($pet->microchip_number),
            medicalHistory: $medicalHistory,
            chronicConditions: [],
            lifestyleTags: $lifestyleTags,
            ownerBudgetMonthly: null,
        );
    }
}
