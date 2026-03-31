<?php

declare(strict_types=1);

namespace App\Http\Requests\RealEstateAgent;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class MultipleDeleteAuthorizeRequest extends FormRequest
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
            'uuids' => 'required|array',
            'uuids.*' => 'required|uuid|exists:real_estate_agent_authorize,uuid'
        ];
    }

    protected function failedValidation(Validator $validator): never
    {
        $this->throwException($validator);
    }
}
