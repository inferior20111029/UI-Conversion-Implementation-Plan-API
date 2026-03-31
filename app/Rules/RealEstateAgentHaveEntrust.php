<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

use App\Support\Enum\RequestFails;

use App\Repositories\PropertyManage\PropertyRepository;
use App\Repositories\RealEstateAgent\RealEstateAgentRepository;

class RealEstateAgentHaveEntrust implements DataAwareRule, ValidationRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    // ...

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $propertyUuid = (string) Arr::get($this->data, 'propertyUuid');
        $spaceId = (string) (new PropertyRepository())
            ->findByUuid(crm('company_id'), crm('community_id'), $propertyUuid)
            ?->crmBuildingSpace
            ?->space_id;

        $exists = (new RealEstateAgentRepository())
            ->checkHaveEntrust(
                crm('company_id'),
                crm('community_id'),
                $spaceId,
                $value
            );

        if (false === $exists) {
            $fail(RequestFails::UNABLE_TO_ASSIGN_THIS_REAL_ESTATE_AGENT->value)->translate();
        }
    }
}
