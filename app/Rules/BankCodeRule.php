<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Validation\ValidationRule;

use App\Services\Selected\BankService;

use App\Support\Enum\RequestFails;

class BankCodeRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $bankData = (new BankService())->execute();
        $exists = Arr::where($bankData, fn(array $data): bool => Arr::get($data, 'code') === (string) $value);

        if (empty($exists)) {
            $fail(RequestFails::NOT_FOUND_BANK_CODE->value)->translate();
        }
    }
}
