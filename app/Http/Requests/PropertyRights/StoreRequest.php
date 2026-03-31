<?php

declare(strict_types=1);

namespace App\Http\Requests\PropertyRights;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

use App\Support\Enum\SaleStageType;
use App\Support\Enum\LayoutSetting;
use App\Support\Enum\HouseState;
use App\Support\Enum\HousePlanning;
use App\Support\Enum\HouseLiveState;
use App\Support\Enum\HouseRentState;
use App\Support\Enum\HousePlanningType;
use App\Support\Enum\ExclusiveAreaName;
use App\Support\Enum\AreaAllowCalculate;

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
        return [
            'price' => 'integer|between:0,4000000000|nullable',
            'rentPrice' => 'integer|between:0,4000000000|nullable',
            'depositPayer' => 'string|max:20|nullable',
            'deposit' => 'integer|between:0,4000000000|nullable',
            'earnestPayment' => 'array|nullable',
            'earnestPayment.*.uuid' => 'uuid|exists:space_earnest_payment,uuid|nullable',
            'earnestPayment.*.payer' => 'required|string|max:20',
            'earnestPayment.*.amountOfMoney' => 'required|integer|between:0,4000000000',
            'planning' => 'array|nullable',
            'planning.*.type' => 'required|max:255|string|in:' . HousePlanningType::implode(with: 'names'),
            'planning.*.planning' => 'required|max:255|string|in:' . HousePlanning::implode(with: 'names'),
            'state' => 'array|required_array_keys:live,house,old,rentalAndSale|nullable',
            'state.live' => 'string|max:255|in:' . HouseLiveState::implode(with: 'names'),
            'state.house' => 'string|max:255|in:' . HouseState::implode(with: 'names'),
            'state.old' => 'integer|between:0,250|nullable',
            'state.rentalAndSale' => 'string|max:255|in:' . HouseRentState::implode(with: 'names'),
            'state.saleStage' => 'string|max:255|nullable|in:' . SaleStageType::implode(with: 'names'),
            'document' => 'array|max:6|nullable',
            'document.*' => 'required|uuid|exists:file,uuid',
            'crmLayoutSettingId' => 'required_without:layout|integer|exists:crm_layout_setting,id|nullable',
            'layout' => 'required_without:crmLayoutSettingId|array|nullable|required_array_keys:' . LayoutSetting::implode(with: 'names'),
            'areaSetting' => 'array|nullable',
            'areaSetting.decimalPlace' => 'integer|between:0,6|nullable',
            'landArea' => 'array|nullable',
            'landArea.dedicated' => 'integer|between:0,15000000|nullable',
            'landArea.agreement' => 'integer|between:0,15000000|nullable',
            'exclusiveArea' => 'array|nullable|required_array_keys:' . ExclusiveAreaName::implode(with: 'names'),
            'customExclusiveArea' => 'array|nullable',
            'customExclusiveArea.*.name' => 'required|string|max:255|distinct|not_in:' . ExclusiveAreaName::implode(with: 'names'),
            'customExclusiveArea.*.ping' => 'required|integer|between:0,15000000',
            'publicHoldingArea' => 'array|nullable',
            'publicHoldingArea.*.constructionNumber' => 'required|string|max:255|distinct',
            'publicHoldingArea.*.total' => 'integer|between:0,4000000000|nullable',
            'publicHoldingArea.*.ownershipDenominator' => 'integer|between:0,8000000|nullable',
            'publicHoldingArea.*.ownershipMolecular' => 'integer|between:0,8000000|nullable',
            'agreedDedicatedAreaSetting' => 'array|nullable',
            'agreedDedicatedAreaSetting.preservation' => 'integer|between:0,15000000|nullable',
            'agreedDedicatedArea' => 'array|nullable',
            'agreedDedicatedArea.*.name' => 'required|string|max:255|distinct',
            'agreedDedicatedArea.*.ping' => 'integer|between:0,4000000000|nullable'
        ] + $this->layoutRule() + $this->exclusiveAreaRule();
    }

    /**
     * 面積規則
     *
     * @return array
     */
    private function exclusiveAreaRule(): array
    {
        $areaNames = ExclusiveAreaName::names();

        $rule = [];
        foreach ($areaNames as $value) {
            $rule["exclusiveArea.{$value}.ping"] = 'integer|between:0,15000000';
            $rule["exclusiveArea.{$value}.allowCalculate"] = 'integer|in:' . AreaAllowCalculate::implode();
        }

        return $rule;
    }

    /**
     * 格局設定
     *
     * @return array
     */
    private function layoutRule(): array
    {
        return collect(LayoutSetting::names())
            ->mapWithKeys(fn (string $layoutName): array => ["layout.{$layoutName}" => 'integer|between:0,255'])
            ->toArray();
    }

    protected function failedValidation(Validator $validator): never
    {
        $this->throwException($validator);
    }
}
