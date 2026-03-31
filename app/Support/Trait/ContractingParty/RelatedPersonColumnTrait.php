<?php

namespace App\Support\Trait\ContractingParty;

use Illuminate\Support\Collection;
use App\Support\Enum\CrmClientContactType;

trait RelatedPersonColumnTrait
{
    /**
     * @param  string  $type
     *
     * @return Collection
     */
    public function fetchRelatedPersonColumnData(string $type = 'created'): Collection
    {
        $salutations = request()->post('salutation', []);

        return collect($salutations)
            ->map(function ($salutationGroup) use ($type) {
                return collect($salutationGroup)
                    ->filter(fn ($salutation) => !empty($salutation['identity_number']))
                    ->map(function ($salutation, $key) use ($type) {
                        $contact = collect([
                            'phone'        => $salutation['phone'] ?? null,
                            'telephone'    => $salutation['telephone'] ?? null,
                            'email'        => $salutation['email'] ?? null,
                            'email_backup' => $salutation['email_backup'] ?? null,
                        ]);

                        $results = collect(CrmClientContactType::values())
                            ->flatMap(fn ($contactType) => $this->processContactType($contact->toArray(), $contactType, null))
                            ->toArray();

                        return [
                            'identity_number' => $salutation['identity_number'] ?? '',
                            'birthday'        => $this->convertDate($salutation['birthday']) ?? null,
                            'account'         => '',
                            'name'            => $salutation['name'] ?? '',
                            'sex'             => $salutation['sex'] ?? '',
                            'basic_remark'    => $salutation['basic_remark'] ?? '',
                            'life'            => $salutation['life'] ?? '',
                            'company_id'      => crm('company_id'),
                            'salutation'      => $salutation['salutation'] ?? '',
                            'is_spouse'       => filter_var($salutation['is_spouse'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
                            $type === 'edit' ? 'updated_at' : 'created_at' => now(),
                            'contact'         => $results
                        ];
                    });
            });
    }

    /**
     * @param  Collection  $relatedPersonInfo
     * @param  array  $clientIds
     * @param  string  $spaceId
     * @param  int  $id
     *
     * @return Collection
     */
    public function fetchRelatedPersonInsert(Collection $relatedPersonInfo, array $clientIds, string $spaceId, int $id)
    {
        return $relatedPersonInfo->flatMap(function ($items, $key) use ($clientIds, $spaceId, $id) {
            $clientId = $clientIds[$key] ?? $key;
            return collect($items)->map(function ($item, $key) use ($clientId, $spaceId, $id) {

                $relatedClientId = self::isValidUUID($key) ? $key : $this->crmClientRepository->updateOrCreate($item)->id;

                foreach ($item['contact'] as &$contact) {
                    $contact['client_id'] = $relatedClientId;
                }

                return [
                    'property_info_id'  => $id,
                    'related_client_id' => $relatedClientId,
                    'client_id'         => $clientId,
                    'space_id'          => $spaceId,
                    'salutation'        => $item['is_spouse'] ? '配偶' : $item['salutation'],
                    'is_spouse'         => filter_var($item['is_spouse'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
                    'contact'           => $item['contact'],
                ];
            })->all();
        });
    }

    /**
     * @param  string|int|null  $uuid
     *
     * @return bool
     */
    private function isValidUUID(string|int|null $uuid): bool
    {
        if (strlen($uuid) !== 36) {
            return false;
        }

        return preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[4][a-f0-9]{3}-[89ab][a-f0-9]{3}-[a-f0-9]{12}$/i', $uuid) === 1;
    }

}