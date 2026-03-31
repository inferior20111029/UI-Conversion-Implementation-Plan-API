<?php

declare(strict_types=1);

namespace App\Http\Requests\VisitReserve;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

use App\Rules\SpaceHaveProperty;

class FrontendStoreRequest extends FormRequest
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
            'propertyUuid' => ['required', 'uuid', 'exists:property,uuid', new SpaceHaveProperty()],
            'appointmentTime' => ['required', 'date_format:Y-m-d H:i:s'],
            'numberOfVisitors' => ['integer', 'min:0', 'max:65000'],
            'visitorsName' => ['required', 'string', 'max:50'],
            'visitorsCellphone' => ['required', 'string', 'min:10', 'max:15']
        ];
    }

    protected function passedValidation(): void
    {
        $this->merge([
            'numberOfVisitors' => $this->integer('numberOfVisitors'),
            'realEstateAgentId' => auth()->user()->realEstateAgent->id
        ]);
    }

    protected function failedValidation(Validator $validator): never
    {
        $this->throwException($validator);
    }
}
