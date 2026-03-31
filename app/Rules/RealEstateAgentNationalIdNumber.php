<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

use App\Support\Enum\RequestFails;

use App\Repositories\RealEstateAgent\RealEstateAgentRepository;

class RealEstateAgentNationalIdNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ((new RealEstateAgentRepository())->checkRepeatNationalIdNumber($value)) {
            $message = [
                RequestFails::CAN_NOT_DUPLICATE_REGISTRATION->value,
                RequestFails::NATION_ID_NUMBER_ALREADY_EXISTS->value
            ];

            $fail(implode('，', $message))->translate();
        }
    }
}
