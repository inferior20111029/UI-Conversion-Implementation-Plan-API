<?php

namespace App\Support\Insurance\Data;

interface InsuranceDataObject
{
    public static function fromArray(array $payload): static;

    public function toArray(): array;
}
