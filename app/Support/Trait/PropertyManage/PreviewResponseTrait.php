<?php

declare(strict_types=1);

namespace App\Support\Trait\PropertyManage;

use App\Models\Property;

trait PreviewResponseTrait
{
    private function getAttachedEquipments(?Property $property): array
    {
        return $property?->attachedEquipments->where('display_state', 1)
            ?->map(fn ($item) => $item->equipment->name)
            ?->values()->toArray() ?? [];
    }

    private function getPropertyContactInfo(Property $propertyData): array
    {
        return $propertyData->propertyContactInfo->map(fn ($item) => [
            'info' => $item['info'],
            'type' => (string) $item['type'],
        ])->toArray();
    }

    private function getPropertyContactPerson(Property $property): string
    {
        $typeMapping = [
            'landlord' => '房東',
            'agent'    => '仲介',
        ];

        $contactType = $typeMapping[$property->propertyContactPerson->type] ?? '未知';
        $contactName = $property->propertyContactPerson->name;

        return "{$contactType}-{$contactName}";
    }
}
