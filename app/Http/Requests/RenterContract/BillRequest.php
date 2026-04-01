<?php

declare(strict_types=1);

namespace App\Http\Requests\RenterContract;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class BillRequest extends FormRequest
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
            'startTime' => 'required|date',
            'endTime' => 'date|after:startTime|nullable',
            'includeTax' => 'required|integer|between:0,1',
            'paid' => 'required|integer|between:0,1',
            'billAmount' => 'array|nullable',
            'billAmount.*.lineItem' => 'required|string|distinct|max:255',
            'billAmount.*.price' => 'required|numeric|between:-100000000,100000000'
        ];
    }

    protected function failedValidation(Validator $validator): never
    {
        $this->throwException($validator);
    }
}
