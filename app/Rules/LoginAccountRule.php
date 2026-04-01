<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

use App\Repositories\RealEstateAgent\RealEstateAgentRepository;

class LoginAccountRule implements DataAwareRule, ValidationRule
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
        $nationalIdNumber = (string) Arr::get($this->data, 'nationalIdNumber');

        $realEstateAgent = (new RealEstateAgentRepository())->findByRepeatNationalIdNumber($nationalIdNumber);
        $realEstateAgentAccount = (string) $realEstateAgent?->login?->account;

        if (
            empty($realEstateAgent)
            ||
            $value === $realEstateAgentAccount
        ) {
            return;
        }

        $validator = Validator::make($this->data, [
            'account' => 'unique:login,account'
        ]);

        if ($validator->fails()) {
            $fail($validator->errors()->first())->translate();
        }
    }
}
