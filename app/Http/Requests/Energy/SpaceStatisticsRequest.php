<?php

namespace App\Http\Requests\Energy;

use Illuminate\Support\Arr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

use App\Support\Enum\CertificationBuildingType;

class SpaceStatisticsRequest extends FormRequest
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
            'space_id'      => 'required|uuid',
            'fee_number_id' => 'required|uuid',
            'start_at'      => 'required|date',
            'end_at'        => 'required|date',
            'consumption'   => 'required|nullable|numeric|max:100000000',
            'cost'          => 'required|nullable|numeric|max:100000000',
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
