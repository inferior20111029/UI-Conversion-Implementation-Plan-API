<?php

namespace App\Support\Trait\ContractingParty;

use App\Support\Enum\CrmClientContactType;

trait IntroducerColumnTrait
{
    /**
     * 介紹人
     *
     * @param  int  $propertyInfoId
     * @param  string  $type
     *
     * @return array
     */
    private function fetchIntroducerColumnData(int $propertyInfoId, string $type = 'created'): array
    {
        $introducer = request()->post('introducer');

        $clientInfo     = [];
        $additionalInfo = [];
        $contactData    = [];

        if (is_null($introducer)) {
            return [$clientInfo, $additionalInfo, $contactData];
        }

        $currentTimestamp = now();

        foreach ($introducer as $key => $item) {

            $contact = [
                'phone'        => $item['phone'] ?? null,
                'telephone'    => $item['telephone'] ?? null,
                'email'        => $item['email'] ?? null,
                'email_backup' => $item['email_backup'] ?? null,
            ];

            $contactData[] = collect(CrmClientContactType::values())
                ->flatMap(fn ($contactType) => $this->processContactType($contact, $contactType, null))
                ->toArray();

            $clientIdKey = $item['client_id'] ?? $key;

            $info = [
                'property_info_id'     => $propertyInfoId,
                'community'            => $item['community'] ?? null,
                'construction_company' => $item['construction_company'] ?? null,
                'housing_situation'    => $item['housing_situation'] ?? null,
                'tax_id'               => $item['tax_id'] ?? null,
                'company_address'      => $item['company_address'] ?? null,
                'updated_at'           => $currentTimestamp,
            ];

            if ($type !== 'edit') {
                $info['created_at'] = $currentTimestamp;
            }

            $clientData = self::fetchClientData($item, $currentTimestamp);

            if ($type === 'edit') {
                $clientData['id'] = $clientIdKey;
            } else {
                $clientData['created_at'] = $currentTimestamp;
            }

            $clientInfo[] = $clientData;
            $additionalInfo[] = $info;
        }

        return [$clientInfo, $additionalInfo, $contactData];
    }

    /**
     * @param  int  $propertyInfoId
     *
     * @return array
     */
    private function fetchIntroducerUpdateData(int $propertyInfoId): array
    {
        return $this->crmIntroducerInfoRepository->findByPropertyInfoId($propertyInfoId)
            ->map(function ($item) {
                $crmClient = $item->crmClient;

                return [
                    'clean_id'             => $item['client_id'],
                    'name'                 => $crmClient?->name,
                    'sex'                  => $crmClient?->sex,
                    'birthday'             => self::convertToRepublicDate($crmClient?->birthday ?? null) ?? null,
                    'identity_number'      => $crmClient?->identity_number,
                    'mailing_address'      => $crmClient?->mailing_address,
                    'residence_address'    => $crmClient?->residence_address,
                    'transfer_account'     => $crmClient?->transfer_account,
                    'occupation'           => $crmClient?->occupation,
                    'employer'             => $crmClient?->employer,
                    'basic_remark'         => $crmClient?->basic_remark,
                    'occupation_remark'    => $crmClient?->occupation_remark,
                    'life'                 => $crmClient?->life,
                    'community'            => $item['community'] ?? null,
                    'id'                   => $item['id'] ?? null,
                    'housing_situation'    => $item['housing_situation'] ?? null,
                    'tax_id'               => $item['tax_id'] ?? null,
                    'company_address'      => $item['company_address'] ?? null,
                    'construction_company' => $item['construction_company'] ?? null,
                    'contact'              => $crmClient->crmClientContact->toArray(),
                ];
            })
            ->toArray();
    }

    /**
     * @param  array  $contactData
     * @param  array  $introducerInfo
     * @param  array  $clientIds
     * @param  string  $type
     *
     * @return array|null
     */
    private function transactionIntroducerInfoInsert(array $contactData, array $introducerInfo, array $clientIds, string $type = 'created'): ?array
    {
        $transactionRecords = array_filter(array_map(function ($key, ?array $companyInfo) use ($clientIds) {
            $clientId = $clientIds[$key] ?? $key;

            return [...['client_id' => $clientId], ...$companyInfo];
        }, array_keys($introducerInfo), $introducerInfo));

        $transactionContacts = array_filter(
            array_map(
                function ($key, ?array $contactInfo) use ($clientIds) {
                    $clientId = $clientIds[$key] ?? $key;

                    if (!is_array($contactInfo)) {
                        return null;
                    }

                    return array_map(function ($info) use ($clientId) {
                        $info['client_id'] = $clientId;
                        return $info;
                    }, $contactInfo);
                },
                array_keys($contactData),
                $contactData
            )
        );

        $transactionContacts = [...$transactionContacts];

        if (!empty($transactionRecords)) {
            if ($type === 'edit') {
                foreach ($transactionRecords as $data) {
                    $this->crmIntroducerInfoRepository->updateOrCreate($data);
                }
            } else {
                $this->crmIntroducerInfoRepository->insert($transactionRecords);
            }
        }

        return $transactionContacts;
    }

    /**
     * 刪除介紹人id
     *
     * @return array
     */
    private function getIntroducerDeletionIds(): array
    {
        $introducerDel = request()->post('introducer_del', '');

        return is_string($introducerDel) ? explode(',', $introducerDel) : [];
    }
}
