<?php

namespace App\Support\Insurance\Data;

class RankablePetProfileData
{
    public function __construct(
        public string $species,
        public ?string $breed,
        public ?string $birthDate,
        public ?string $microchipNumber = null,
        public bool $hasMicrochip = false,
        public ?string $registrationNumber = null,
        public bool $isRegistered = false,
        public array $medicalHistory = [],
        public array $chronicConditions = [],
        public array $lifestyleTags = [],
        public ?float $ownerBudgetMonthly = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'species' => $this->species,
            'breed' => $this->breed,
            'birth_date' => $this->birthDate,
            'microchip_number' => $this->microchipNumber,
            'has_microchip' => $this->hasMicrochip,
            'registration_number' => $this->registrationNumber,
            'is_registered' => $this->isRegistered,
            'medical_history' => $this->medicalHistory,
            'chronic_conditions' => $this->chronicConditions,
            'lifestyle_tags' => $this->lifestyleTags,
            'owner_budget_monthly' => $this->ownerBudgetMonthly,
        ];
    }
}
