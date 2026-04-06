<?php

namespace App\Support\Insurance\Data;

use JsonSerializable;

class CoverageSummarySnapshotData implements InsuranceDataObject, JsonSerializable
{
    public function __construct(
        public array $medical = ['enabled' => false, 'outpatient' => false, 'hospitalization' => false, 'surgery' => false],
        public array $liability = ['enabled' => false],
        public array $lostPetAd = ['enabled' => false],
        public array $ownerHospitalBoarding = ['enabled' => false],
        public array $funeral = ['enabled' => false],
        public array $reacquisition = ['enabled' => false],
    ) {
    }

    public static function fromArray(array $payload): static
    {
        return new static(
            medical: (array) ($payload['medical'] ?? ['enabled' => false, 'outpatient' => false, 'hospitalization' => false, 'surgery' => false]),
            liability: (array) ($payload['liability'] ?? ['enabled' => false]),
            lostPetAd: (array) ($payload['lost_pet_ad'] ?? ['enabled' => false]),
            ownerHospitalBoarding: (array) ($payload['owner_hospital_boarding'] ?? ['enabled' => false]),
            funeral: (array) ($payload['funeral'] ?? ['enabled' => false]),
            reacquisition: (array) ($payload['reacquisition'] ?? ['enabled' => false]),
        );
    }

    public function enabledFlags(): array
    {
        return [
            'medical' => (bool) ($this->medical['enabled'] ?? false),
            'liability' => (bool) ($this->liability['enabled'] ?? false),
            'lost_pet_ad' => (bool) ($this->lostPetAd['enabled'] ?? false),
            'owner_hospital_boarding' => (bool) ($this->ownerHospitalBoarding['enabled'] ?? false),
            'funeral' => (bool) ($this->funeral['enabled'] ?? false),
            'reacquisition' => (bool) ($this->reacquisition['enabled'] ?? false),
        ];
    }

    public function toArray(): array
    {
        return [
            'medical' => $this->medical,
            'liability' => $this->liability,
            'lost_pet_ad' => $this->lostPetAd,
            'owner_hospital_boarding' => $this->ownerHospitalBoarding,
            'funeral' => $this->funeral,
            'reacquisition' => $this->reacquisition,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
