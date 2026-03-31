<?php

declare(strict_types=1);

namespace App\Http\Requests\RealEstateAgent;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

use App\Rules\RealEstateAgentIdentificationCodeExists;

class AuthorizeRequest extends FormRequest
{
    use \App\Support\Trait\Request\ExceptionTrait;

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
        return [
            'identificationCode' => ['required', 'ulid', 'exists:real_estate_agent,identification_code', new RealEstateAgentIdentificationCodeExists]
        ];
    }

    protected function failedValidation(Validator $validator): never
    {
        $this->throwException($validator);
    }
}
