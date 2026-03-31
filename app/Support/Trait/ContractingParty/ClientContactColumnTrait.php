<?php

namespace App\Support\Trait\ContractingParty;

use App\Support\Enum\CrmClientContactType;

trait ClientContactColumnTrait
{
    /**
     * @param  array  $clientIds
     *
     * @return array
     */
    public function fetchContactColumnData(array $clientIds)
    {
        $contractingPartyData = request()->post('contact') ?? [];

        $results = [];

        foreach ($contractingPartyData as $key => $contact) {
            $clientId =  $clientIds[$key] ?? $key;
            foreach (CrmClientContactType::values() as $contactType) {
                $results = array_merge($results, $this->processContactType($contact, $contactType, $clientId));
            }
        }

        return $results;
    }

    /**
     * @param  array  $contact
     * @param  string  $contactType
     * @param  string|null  $client_id
     *
     * @return array
     */
    private function processContactType(array $contact, string $contactType, ?string $client_id): array
    {
        return collect(data_get($contact, $contactType, []))
            ->filter()
            ->map(fn ($value) => $this->createResultItem($client_id, $contactType, $value))
            ->all();
    }

    /**
     * @param  string|null  $clientId
     * @param  string  $contactType
     * @param $value
     *
     * @return array
     */
    private function createResultItem(?string $clientId, string $contactType, $value): array
    {
        if (is_array($value)) {
            return [
                'client_id' => $clientId ?? null,
                'type'      => $contactType,
                'value'     => $value['value'],
                'is_send'   => filter_var($value['is_send'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
            ];
        }

        return [
            'client_id' => $clientId ?? null,
            'type'      => $contactType,
            'value'     => $value,
            'is_send'   => 0
        ];
    }
}
