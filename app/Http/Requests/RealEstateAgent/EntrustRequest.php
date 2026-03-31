<?php

declare(strict_types=1);

namespace App\Http\Requests\RealEstateAgent;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

use App\Support\Enum\EntrustState;

class EntrustRequest extends FormRequest
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
            'realEstateAgentUuid' => 'required|uuid|exists:real_estate_agent,uuid',
            'startTime' => 'required_if:entrustState,' . EntrustState::ENABLE->value . '|date',
            'endTime' => 'required_if:entrustState,' . EntrustState::ENABLE->value . '|date|after:startTime',
            'whileSoldOut' => 'required_if:entrustState,' . EntrustState::ENABLE->value . '|integer|between:0,1',
            'entrustState' => 'required|integer|between:0,1'
        ];
    }

    protected function failedValidation(Validator $validator): never
    {
        $this->throwException($validator);
    }
}
