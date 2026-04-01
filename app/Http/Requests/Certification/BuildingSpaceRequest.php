<?php

namespace App\Http\Requests\Certification;

use Illuminate\Support\Arr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

use App\Support\Enum\CertificationBuildingType;

class BuildingSpaceRequest extends FormRequest
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
            "DELETE" => $this->deleteRule(),
            default => []
        };
    }

    private function getRule(): array
    {
        return [
            'space_id' => 'required|uuid',
            'type'     => ['required', Rule::in(CertificationBuildingType::array())],
        ];
    }

    private function deleteRule(): array
    {
        return [
            'id' => 'required|integer',
        ];
    }

    private function storeRule(): array
    {
        return [
            'name'           => 'required|string',
            'space_id'       => 'required|uuid',
            'application_at' => 'required|date',
            'type'           => ['required', Rule::in(CertificationBuildingType::array())],
            'file.*'         => 'required|uuid|exists:file,uuid'
        ];
    }

    private function updateRule(): array
    {
        return Arr::only($this->storeRule(), ['name', 'application_at']);
    }

    protected function failedValidation(Validator $validator): never
    {
        $this->throwException($validator);
    }
}
