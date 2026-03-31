<?php

declare(strict_types=1);

namespace App\Support\Parameter;

use App\Models\Fees;
use App\Models\Decoration;
use App\Models\RenterContract;
use App\Models\ContractBank;
use App\Models\ContractPaymentCycle;

class RenterContractParameter
{
    private readonly RenterContract|array $contract;

    private readonly array $itemsIncluded;

    private readonly array $persons;

    private readonly array $document;

    private readonly ContractPaymentCycle|array $paymentCycle;

    private readonly array $notify;

    private readonly Decoration $decoration;

    private readonly Fees|array $fees;

    private readonly array $carpark;

    private readonly array $equipment;

    private readonly array $bill;

    private readonly array $billAmount;

    private readonly ContractBank $bank;

    public function __construct(array $params = [])
    {
        foreach ($params as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function __get($key)
    {
        return $this->{$key};
    }

    public function hasCarparkData(): bool
    {
        return !empty($this->carpark);
    }
}
