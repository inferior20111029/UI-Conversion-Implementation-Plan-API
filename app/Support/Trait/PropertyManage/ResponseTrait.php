<?php

declare(strict_types=1);

namespace App\Support\Trait\PropertyManage;

use App\Models\Property;

use App\Support\Tool\File\FileMagic;

trait ResponseTrait
{
    private function getDecorationData(Property $propertyData): array
    {
        return [
            'degree' => $propertyData->decoration?->degree,
            'time'   => $propertyData->decoration?->time,
        ];
    }

    private function getAttachedEquipments(?Property $propertyData)
    {
        return $propertyData->attachedEquipments->map(function ($item) {
            return [
                'id'            => $item->crm_equipment_id,
                'display_state' => $item->display_state,
            ];
        });
    }

    private function getFeesData(?Property $propertyData): array
    {
        return [
            'price'               => $propertyData->fees?->price,
            'unit_price'          => $propertyData->fees?->unit_price,
            'deposit'             => $propertyData->fees?->deposit,
            'depositTotalMonth'   => $propertyData->fees?->deposit_total_month,
            'is_management_fee'   => $propertyData->fees?->management_fee > 0 ? '1' : '0',
            'managementFee'       => $propertyData->fees?->management_fee,
        ];
    }

    private function getAttachedCarparks(?Property $propertyData): array
    {
        return $propertyData->attachedCarparks->map(fn ($item) => [
            'attribute'              => $item->crmParkingSpace->parking_type ?? '',
            'parkingNumber'          => $item->crmParkingSpace->parking_number ?? '',
            'type'                   => $item['type'],
            'crmParkingSpaceId'      => $item['crm_parking_space_id'],
            'price'                  => $item['price'],
            'rent_inclusive'         => $item['price'] === 0 ? 1 : 0,
            'license_plate_number'   => $item['license_plate_number'],
        ])->toArray();
    }

    private function getItemCheckInData(?Property $propertyData): array
    {
        return [
            'date'            => $propertyData?->itemCheckIn->check_in_date ?? null,
            'lease_term'      => $propertyData?->itemCheckIn->minimum_period ?? null,
            'lease_term_type' => $propertyData?->itemCheckIn->minimum_rental_period_type ?? null,
        ];
    }

    private function getNeighborhoodTransportation(Property $propertyData): array
    {
        $transportationData = $propertyData->neighborhoodTransportation
            ->map(fn ($item) => [
                'type' => (string) $item['neighborhood_transportation_id'],
                'name' => (string) $item['name'],
            ])
            ->toArray();

        return !empty($transportationData)
            ? $transportationData
            : [['type' => '', 'name' => '']];
    }

    private function getPropertyContactInfo(Property $propertyData): array
    {
        return $propertyData->propertyContactInfo->map(fn ($item) => [
            'info' => $item['info'],
            'type' => (string) $item['type'],
        ])->toArray();
    }

    private function getPropertyContactPerson(Property $propertyData): array
    {
        return [
            'name' => $propertyData->propertyContactPerson->name,
            'type' => (string) $propertyData->propertyContactPerson->type,
        ];
    }

    private function getDocuments(?Property $propertyData): array
    {
        return $propertyData?->document
            ->map(function ($document) {
                $file = $document->file;

                return [
                    'uuid'             => $file?->uuid ?? '',
                    'fileOriginalName' => $file?->original_name ?? '',
                    'file_url'         => $file ? FileMagic::find($file)->url() : '',
                    'type'             => $document->type,
                    'url'              => $document->url,
                ];
            })
            ->groupBy(function ($item) {
                return in_array($item['type'], ['video', 'URL']) ? $item['type'] : 'picture';
            })
            ->map(function ($group, $key) {
                if ($key === 'picture') {
                    return $group->map(function ($item) {
                        return [
                            'status'  => 'success',
                            'uuid'    => $item['uuid'] ?? '',
                            'url'     => $item['file_url'] ?? '',
                        ];
                    })->values()->toArray();
                }

                if ($key === 'URL') {
                    return $group->first()['url'] ?? '';
                }

                return $group->map(function ($item) {
                    return [
                        'status'           => 'success',
                        'fileOriginalName' => $item['fileOriginalName'] ?? '',
                        'uuid'             => $item['uuid'] ?? '',
                        'url'              => $item['file_url'] ?? '',
                    ];
                })->values()->toArray();

            })->toArray() + [
                'video' => [
                        [
                            'status'           => 'success',
                            'fileOriginalName' => '',
                            'uuid'             => '',
                            'url'              => '',
                        ]
                ],
                'URL' => '',
                'picture' => [
                    [
                        'status' => 'success',
                        'uuid'   => '',
                        'url'    => '',
                    ]
                ],
            ];
    }
}