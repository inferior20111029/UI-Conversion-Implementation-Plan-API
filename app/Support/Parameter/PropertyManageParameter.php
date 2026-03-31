<?php

declare(strict_types=1);

namespace App\Support\Parameter;

use App\Models\Fees;
use App\Models\ItemCheckIn;
use App\Models\Decoration;
use App\Models\Property;
use App\Models\PropertyContactPerson;

class PropertyManageParameter
{
    public readonly Property $contract;
    public readonly array $itemsIncluded;
    public readonly array $persons;
    public readonly array $document;
    public readonly array $notify;
    public readonly ?Decoration $decoration;
    public readonly ?Fees $fees;
    public readonly ?ItemCheckIn $itemCheckIn;
    public readonly array $transportation;
    public readonly array $livability;
    public readonly array $carpark;
    public readonly array $equipment;
    public readonly ?PropertyContactPerson $contactPerson;
    public readonly array $contactInfo;

    public function __construct(array $params = [])
    {
        $this->contract       = $params['property'] ?? new Property();
        $this->itemsIncluded  = $params['itemsIncluded'] ?? [];
        $this->persons        = $params['persons'] ?? [];
        $this->document       = $params['document'] ?? [];
        $this->decoration     = $params['decoration'] ?? null;
        $this->fees           = $params['fees'] ?? null;
        $this->transportation = $params['transportation'] ?? [];
        $this->livability     = $params['livability'] ?? [];
        $this->carpark        = $params['carpark'] ?? [];
        $this->equipment      = $params['equipment'] ?? [];
        $this->itemCheckIn    = $params['checkInInfo'] ?? null;
        $this->contactPerson  = $params['contactPerson'] ?? [];
        $this->contactInfo    = $params['contactInfo'] ?? [];
    }
    public function __get($key)
    {
        return $this->{$key};
    }
}
