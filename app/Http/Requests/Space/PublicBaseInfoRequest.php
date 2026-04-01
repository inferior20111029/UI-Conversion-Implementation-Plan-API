<?php

declare(strict_types=1);

namespace App\Http\Requests\Space;

use Illuminate\Support\Arr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class PublicBaseInfoRequest extends FormRequest
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
        return match (request()->method()) {
            "GET"   => $this->getRule(),
            "POST"  => $this->storeRule(),
            "PATCH" => $this->updateRule(),
            default => []
        };
    }

    private function getRule(): array
    {
        return [
            'space_id' => 'required|uuid',
        ];
    }

    private function storeRule(): array
    {
        return [
            'space_id'       => 'required|uuid',
            'serial_number'  => 'required|string|max:50',
            'machine_number' => 'required|string|max:50',
            'type'           => ['required', Rule::in([0, 1])],
            'introduction'   => 'required|string',
            'management_measures_text.*' => 'required|string',
            'house_viewing.*' => 'required|string',
            'file.*'         => 'required|uuid|exists:files,uuid'
        ];
    }

    private function updateRule(): array
    {
        return Arr::except($this->storeRule(), ['space_id', 'fee_number_id']);
    }

    protected function failedValidation(Validator $validator): never
    {
        $this->throwException($validator);
    }
}
