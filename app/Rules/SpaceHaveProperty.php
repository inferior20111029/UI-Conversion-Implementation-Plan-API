<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

use App\Support\Enum\RequestFails;

use App\Repositories\PropertyManage\PropertyRepository;

class SpaceHaveProperty implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $propertyRepository = new PropertyRepository;

        $propertyData = !empty(crm()->user())
            ? $propertyRepository->findByUuid(crm('company_id'), crm('community_id'), $value)
            : $propertyRepository->frontendFindByUuid($value);

        if (empty($propertyData)) {
            $fail(RequestFails::NO_LISTING_INFORMATION_FOUND->value)->translate();
        }
    }
}
