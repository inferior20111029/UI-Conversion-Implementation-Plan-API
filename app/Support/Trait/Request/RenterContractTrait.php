<?php

declare(strict_types=1);

namespace App\Support\Trait\Request;

use App\Support\Enum\BankType;
use App\Support\Enum\TimeCycle;
use App\Support\Enum\DecorationType;
use App\Support\Enum\DecorationTime;
use App\Support\Enum\ContractNotifyType;
use App\Support\Enum\ContractPersonType;
use App\Support\Enum\ContractParkingType;

use App\Rules\BankCodeRule;
use App\Rules\CheckSignatureFormat;

trait RenterContractTrait
{
    public function rule(): array
    {
        $dayOfMonthType = implode(',', [TimeCycle::monthly->value, TimeCycle::yearly->value]);
        $cacheState = (int) request()->post('cacheState');

        $rules = [
            'name' => 'required|string|max:255',
            'nationalIdNumber' => 'required|string|max:10|isNI',
            'cellphone' => 'required|string|max:15',
            'birthday' => 'required|date',
            'startTime' => 'required|date',
            'endTime' => 'required|date|after:startTime',
            'allowDeclare' => 'required|integer|between:0,1',
            'allowEarlyTermination' => 'required|integer|between:0,1',
            'allowSublease' => 'required|integer|between:0,1',
            'restore' => 'required|integer|between:0,1',
            'remark' => 'string|nullable',
            'document' => 'array|max:6|nullable',
            'document.*' => 'required|uuid|exists:file,uuid',
            'associatedPersons' => 'array|nullable',
            'associatedPersons.*.type' => 'required|string|max:255|in:' . ContractPersonType::implode(with: 'names'),
            'associatedPersons.*.name' => 'required|string|max:255',
            'associatedPersons.*.nationalIdNumber' => 'required|string|max:10|isNI',
            'associatedPersons.*.cellphone' => 'required|string|max:15',
            'associatedPersons.*.birthday' => 'required|date',
            'paymentCycle' => 'required|array',
            'paymentCycle.type' => 'required|string|max:255|in:' . TimeCycle::implode(with: 'names'),
            'paymentCycle.month' => 'integer|between:1,12|required_if:paymentCycle.type,' . TimeCycle::yearly->value,
            'paymentCycle.dayOfWeek' => 'integer|between:0,6|required_if:paymentCycle.type,' . TimeCycle::weekly->value,
            'paymentCycle.dayOfMonth' => "integer|between:1,31|required_if:paymentCycle.type,{$dayOfMonthType}",
            'notify' => 'array|nullable',
            'notify.*.type' => 'required|string|max:255|in:' . ContractNotifyType::implode(with: 'names'),
            'notify.*.triggerTime' => 'date_format:Y-m-d H:i|required_if:notify.*.type,' . ContractNotifyType::customization->name,
            'decoration' => 'required|array',
            'decoration.degree' => 'required|string|max:255|in:' . DecorationType::implode(with: 'names'),
            'decoration.time' => 'required|string|max:255|in:' . DecorationTime::implode(with: 'names'),
            'fees' => 'required|array',
            'fees.price' => 'required|numeric|between:-100000000,100000000',
            'fees.deposit' => 'numeric|between:-100000000,100000000|nullable',
            'fees.depositTotalMonth' => 'integer|between:1,12|nullable',
            'fees.managementFee' => 'numeric|between:-100000000,100000000|nullable',
            'carpark' => 'array|nullable',
            'carpark.*.type' => 'required|string|in:' . ContractParkingType::implode(with: 'names'),
            'carpark.*.crmParkingSpaceId' => 'required|uuid|exists:crm_parking_space,id',
            'carpark.*.price' => 'required|numeric|between:-100000000,100000000',
            'carpark.*.licensePlateNumber' => 'required|string|max:20',
            'equipment' => 'array|nullable',
            'equipment.*' => 'required|integer|min:1|exists:crm_equipment,id',
            'itemsIncluded' => 'array|nullable',
            'itemsIncluded.*' => 'required|integer|exists:rent_items_options,id',
            'bank' => 'array|nullable',
            'bank.type' => 'string|max:20|in:' . BankType::implode(with: 'names'),
            'bank.code.*' => ['required_if:bank.type,' . BankType::entity->name, 'string', 'max:5', new BankCodeRule()],
            'bank.account.*' => ['required_if:bank.type,' . BankType::entity->name, 'string', 'max:18'],
        ];

        if ($cacheState === 0) {
            $rules['signature'] = ['required', 'string', new CheckSignatureFormat()];
        }

        return $rules;
    }
}
