<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

use App\Support\Enum\RequestFails;

use App\Repositories\RealEstateAgent\RealEstateAgentRepository;

class RealEstateAgentIdentificationCodeExists implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $realEstateAgent = (new RealEstateAgentRepository())->findByIdentificationCode($value);

        if (empty($realEstateAgent)) {
            $fail(RequestFails::NOT_FOUND_IDENTIFICATION_CODE->value)->translate();
        }
    }
}
