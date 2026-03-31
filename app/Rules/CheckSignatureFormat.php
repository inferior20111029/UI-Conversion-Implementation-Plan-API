<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

use App\Support\Enum\RequestFails;
use App\Support\Tool\File\FileMagic;

class CheckSignatureFormat implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (
            false === FileMagic::isBase64Image($value)
            &&
            false === str($value)->isUuid()
        ) {
            $fail(RequestFails::FORMAT_NEED_UUID_OR_BASE64_IMAGE->value)->translate();
        }
    }
}
