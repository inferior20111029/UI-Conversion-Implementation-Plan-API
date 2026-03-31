<?php

declare(strict_types=1);

namespace App\Http\Requests\RenterContract;

use Illuminate\Support\Arr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

use App\Support\Enum\RequestFails;

class SpaceRequest extends FormRequest
{
    use \App\Support\Trait\Request\ExceptionTrait;
    use \App\Support\Trait\Request\RenterContractTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rule = $this->rule();

        if (in_array(request()->method(), ['PUT', 'PATCH'])) {
            $cacheState = (int) request()->post('cacheState');

            if ($cacheState === 0) {
                Arr::forget($rules, 'signature');
            }
        }

        return $rule;
    }

    public function messages()
    {
        return [
            'nationalIdNumber.is_n_i' => RequestFails::NATION_ID_NUMBER_FORMAT_ERROR->value,
            'associatedPersons.*.nationalIdNumber.is_n_i' => '相關人員-' . RequestFails::NATION_ID_NUMBER_FORMAT_ERROR->value,
        ];
    }

    protected function failedValidation(Validator $validator): never
    {
        $this->throwException($validator);
    }
}
