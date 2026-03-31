<?php

declare(strict_types=1);

namespace App\Http\Requests\RealEstateAgent;

use Illuminate\Support\Arr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

use App\Support\Enum\Sex;
use App\Support\Enum\RequestFails;
use App\Support\Constants\RequestRule;

use App\Rules\LoginAccountRule;
use App\Rules\RealEstateAgentNationalIdNumber;

class StoreRequest extends FormRequest
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
            'POST' => $this->storeRule(),
            'PATCH' => $this->updateRule(),
            default => []
        };
    }

    private function storeRule(): array
    {
        return [
            'account' => ['required', 'string', 'max:255', new LoginAccountRule()],
            'avatar' => 'uuid|exists:file,uuid|nullable',
            'name' => 'required|string|max:255',
            'sex' => 'required|string|in:' . Sex::implode(with: 'names'),
            'birthday' => 'date|nullable',
            'nationalIdNumber' => ['required', 'string', 'max:10', 'isNI', new RealEstateAgentNationalIdNumber()],
            'cellphoneAreaCode' => 'required|string|max:6',
            'cellphone' => 'required|string|max:15',
            'contactNumbersAreaCode' => 'required|string|max:6',
            'contactNumbers' => 'string|max:15|nullable',
            'email' => 'required|string|email:rfc,dns',
            'companyCellphoneAreaCode' => 'required|string|max:6',
            'companyCellphone' => 'string|max:15|nullable',
            'companyName' => 'string|max:255|nullable',
            'companyBranchName' => 'string|max:255|nullable',
            'companyAddress' => 'string|max:255|nullable',
            'companyUrl' => 'string|url|nullable'
        ] + RequestRule::PASSWORD;
    }

    private function updateRule(): array
    {
        $rule = $this->storeRule();

        Arr::forget(
            $rule,
            [...['account', 'nationalIdNumber'], ...array_keys(RequestRule::PASSWORD)]
        );

        return $rule;
    }

    public function messages()
    {
        return [
            'nationalIdNumber.is_n_i' => RequestFails::NATION_ID_NUMBER_FORMAT_ERROR->value,
        ];
    }

    protected function failedValidation(Validator $validator): never
    {
        $this->throwException($validator);
    }
}
