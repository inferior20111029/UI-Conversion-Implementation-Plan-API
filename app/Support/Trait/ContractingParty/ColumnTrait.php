<?php

namespace App\Support\Trait\ContractingParty;

use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use App\Support\Tool\File\FileMagic;
use App\Support\Enum\PropertyTitleType;

use App\Models\CrmPropertyInfoList;

trait ColumnTrait
{
    /**
     * @param $spaceId
     * @param  string  $type
     *
     * @return array
     */
    public function fetchColumnData($spaceId, string $type = 'created'): array
    {
        $data = request()->only([
            'sign_date',
            'transfer_date',
            'build_date',
            'transfer_cause',
            'transfer_item',
        ]);

        $data['transfer_item']  = json_encode($data['transfer_item'], true);
        $data['sign_date']      = $this->convertDate($data['sign_date']) ?? null;
        $data['transfer_date']  = $this->convertDate($data['transfer_date']) ?? null;
        $data['build_date']     = $this->convertDate($data['build_date']) ?? null;
        $data['transfer_cause'] = $data['transfer_cause'] ?? null;

        $data['space_id'] = $spaceId;
        $data[$type === 'edit' ? 'updated_at' : 'created_at'] = now();

        return $data;
    }

    /**
     * @param  int|string  $id
     * @param  string  $identity
     * @param  array  $fileIds
     *
     * @return array
     */
    public function fetchFileColumnData(int|string $id, string $identity, array $fileIds): array
    {
        if(count($fileIds) == 0) {
            return [];
        }

        return array_filter(array_map(function ($fileId) use ($id, $identity) {
            return [
                $identity    => $id,
                'file_id'    => (int) FileMagic::find($fileId)->get()?->id,
                'updated_at' => now(),
                'created_at' => now(),
            ];
        }, $fileIds));
    }

    /**
     * @param  string  $type
     *
     * @return array
     */
    public function fetchRelationshipColumnData($spaceId, string $type = 'created'): array
    {
        $relationship = request()->post('relationship');
        $currentTimestamp = now();

        $filteredPropertyTitleTypes = [];
        $clientInfo = [];
        $clientCompanyInfo = [];
        $clientCompanyFileInfo = [];

        foreach ($relationship as $key => $item) {
            $clientModes = explode(", ", str_replace("'", "", $item['client_mode']));

            $clientIdKey = $item['client_id'] ?? $key;
            $additionalInfo = [
                'portion_percent'      => $item['portion_percent'] ?? 0,
                'space_id'             => $spaceId,
                'portion_area'         => $item['portion_area'] ?? 0,
                'portion_square_meter' => $item['portion_square_meter'] ?? 0,
                'type'                 => $item['type'] === '個人' ? 1 : 0,
                'updated_at'           => $currentTimestamp,
            ];

            if ($type !== 'edit') {
                $additionalInfo['created_at'] = $currentTimestamp;
            }

            foreach ($clientModes as $allowedName) {
                $filteredPropertyTitleTypes[$clientIdKey][] = [
                    ...$this->filterPropertyTitleTypes($allowedName)[0],
                    ...$additionalInfo
                ];
            }

            if ($item['type'] === '個人') {
                $clientData = self::fetchClientData($item, $currentTimestamp);
                $clientCompanyInfo[] = [];
            }

            if ($item['type'] === '法人') {
                $clientData = self::fetchClientData($item, $currentTimestamp);

                $clientCompanyInfo[] = [
                    'id'                     => $item['client_company_id'] ?? str()->uuid()->toString(),
                    'identity_number'        => $item['identity_number'] ?? null,
                    'company_name'           => $item['company_name'] ?? null,
                    'company_number'         => $item['company_number'] ?? null,
                    'company_type'           => $item['company_type'] ?? null,
                    'company_representative' => $item['company_representative'] ?? $item['name'] ?? null,
                    'company_address'        => $item['company_address'] ?? null,
                    'company_url'            => $item['company_url'] ?? null,
                    'company_fax'            => $item['company_fax'] ?? null,
                    'company_telephone'      => $item['company_telephone'] ?? null,
                    $type === 'edit' ? 'updated_at' : 'created_at' => $currentTimestamp,
                ];
            }

            if ($type === 'edit') {
                $deletedFileIds = !empty($item['del_file_id']) ? (array) $item['del_file_id'] : [];

                $clientData['id'] = $clientIdKey;

                $this->crmClientDocumentRepository->forceDelete($item['client_company_id'] ?? '1', $deletedFileIds);
            } else {
                $clientData['created_at']        = $currentTimestamp;
            }

            $clientInfo[] = $clientData;
            $clientCompanyFileInfo[] = self::fetchFileColumnData($clientIdKey, 'client_company_id', $item['file_id'] ?? []);
        }

        return [$filteredPropertyTitleTypes, $clientInfo, $clientCompanyInfo, $clientCompanyFileInfo];
    }

    /**
     * @param  string  $allowedName
     *
     * @return array
     */
    private function filterPropertyTitleTypes(string $allowedName): array
    {
        $filtered = array_filter(PropertyTitleType::cases(), function ($propertyTitleType) use ($allowedName) {
            return $propertyTitleType->value === $allowedName;
        });

        return array_values(array_map(function ($item, $key) {
            return [
                'mode' => $item->name ?? '',
                'mode_sort' => $key + 1,
            ];
        }, $filtered, array_keys($filtered)));
    }

    /**
     * @param  array  $item
     * @param  Carbon  $currentTimestamp
     *
     * @return array
     */
    private function fetchClientData(array $item, Carbon $currentTimestamp)
    {
        return [
            'identity_number'    => $item['identity_number'] ?? null,
            'company_id'         => crm('company_id'),
            'birthday'           => self::convertDate($item['birthday'] ?? null) ?? null,
            'sex'                => $item['sex'] ?? 1,
            'life'               => $item['life'] ?? 0,
            'account'            => $item['account'] ?? '',
            'name'               => $item['name'] ?? '',
            'basic_remark'       => $item['basic_remark'] ?? null,
            'mailing_address'    => $item['mailing_address'] ?? null,
            'residence_address'  => $item['residence_address'] ?? null,
            'transfer_account'   => $item['transfer_account'] ?? null,
            'occupation'         => $item['occupation'] ?? null,
            'employer'           => $item['employer'] ?? null,
            'occupation_remark'  => $item['occupation_remark'] ?? null,
            'updated_at'         => $currentTimestamp,
        ];
    }

    /**
     * @param  CrmPropertyInfoList  $data
     * @param  string|null  $originalName
     *
     * @return array
     */
    public function fetchCommonColumnData(CrmPropertyInfoList $data, ?string $originalName = null): array
    {
        $commonData = [
            'id'             => $data['id'],
            'sign_date'      => self::convertToRepublicDate($data['sign_date']),
            'transfer_date'  => self::convertToRepublicDate($data['transfer_date']),
            'build_date'     => self::convertToRepublicDate($data['build_date']),
            'transfer_cause' => $data['transfer_cause'],
            'is_edit'        => $data['is_edit'] === 1,
            'transfer_item'  => json_decode($data['transfer_item'], true),
        ];

        if (!is_null($originalName)) {
            $clientNamesString = $data->crmPropertyTransactionInfo->map(fn ($item) => $item->crmClient->name)->implode(', ');

            $clientContact = $data->crmPropertyTransactionInfo
                ->map(fn ($item) => $item->crmClient->crmClientContact)
                ->flatMap(fn ($contacts) => $contacts->filter(fn ($contact) => $contact->type === 'phone'))
                ->values()
                ->toArray();

            $commonData['inhabitant'] = $clientNamesString;
            $commonData['contact'] = $clientContact;

            $commonData['original_inhabitant'] = $originalName;
        }

        return $commonData;
    }

    /**
     * @param  Collection  $uploadRecord
     *
     * @return array
     */
    private static function fetchUploadRecord(Collection $uploadRecord): array
    {
        return $uploadRecord->map(function ($item) {
            $file = $item?->file;

            return [
                'file_id'       => $item?->file_id,
                'original_name' => $file?->original_name ?? '',
                'file_uuid'     => $file?->uuid ?? '',
                'url'           => FileMagic::find($file?->id)->url()
            ];
        })->toArray();
    }

    /**
     * @param  CrmPropertyInfoList  $data
     * @param  string  $originalName
     *
     * @return array
     */
    public function fetchPaginateColumnData(CrmPropertyInfoList $data, string $originalName): array
    {
        return [
            ...['is_edit' => $data['is_edit']],
            ...self::fetchCommonColumnData($data, $originalName)
        ];
    }

    /**
     * @param  array  $filteredPropertyTitleTypes
     * @param  array  $clientIds
     * @param  string  $spaceId
     * @param  int  $propertyInfoId
     *
     * @return void
     */
    public function transactionInfoInsert(array $filteredPropertyTitleTypes, array $clientIds, string $spaceId, int $propertyInfoId): void
    {
        $transactionInfoInsert = [];
        foreach ($filteredPropertyTitleTypes as $key => $inhabitantGroup) {
            $clientId = $clientIds[$key] ?? $key;

            foreach ($inhabitantGroup as $inhabitant) {
                $transactionInfoInsert[] = [
                    ...$inhabitant,
                    ...[
                        'client_id'        => $clientId,
                        'space_id'         => $spaceId,
                        'property_info_id' => $propertyInfoId,
                    ]
                ];
            }
        }

        $this->crmPropertyTransactionInfoRepository->insert($transactionInfoInsert);
    }

    /**
     * @param CrmPropertyInfoList $data
     *
     */
    public function transactionCompanyInfoInsert(array $clientCompanyInfo, array $clientIds, array $clientCompanyFileInfo, string $type = 'created')
    {
        $transactionRecords = array_filter(array_map(function ($key, ?array $companyInfo) use ($clientIds, $clientCompanyFileInfo) {
            $clientId = $clientIds[$key] ?? $key;

            if (!isset($companyInfo['identity_number'])) {
                return null;
            }

            return [...['client_id' => $clientId], ...$companyInfo];
        }, array_keys($clientCompanyInfo), $clientCompanyInfo));

        $transactionFileRecords = array_filter(array_map(function ($key, ?array $fileInfo) use ($transactionRecords) {
            $record = $transactionRecords[$key] ?? $key;

            return array_map(function ($file) use ($record) {

                return [...$file, ...['client_company_id' => $record['id'] ?? null]];
            }, $fileInfo);
        }, array_keys($clientCompanyFileInfo), $clientCompanyFileInfo));

        if (!empty($transactionRecords)) {
            if ($type === 'edit') {
                $this->crmClientHasCompanyRepository->upsert($transactionRecords);
            } else {
                $this->crmClientHasCompanyRepository->insert($transactionRecords);
            }
        }

        if (!empty($transactionFileRecords)) {
            $this->crmClientDocumentRepository->insert(Arr::collapse($transactionFileRecords));
        }
    }

    /**
     * @param $propertyTransactionInfo
     * @param $spaceId
     *
     * @return array
     */
    private function propertyInfo($propertyTransactionInfo, $spaceId, $id): array
    {
        $propertyTransactionInfoGroupBy = $propertyTransactionInfo->groupBy('client_id')->map(function (Collection $items) {
            $modes = $items->pluck('mode')->map(fn ($mode) => PropertyTitleType::array()[$mode])->toArray();

            return [
                "client_id"            => $items->first()->client_id,
                "mode"                 => $modes,
                "portion_percent"      => $items->first()->portion_percent,
                "portion_area"         => $items->first()->portion_area,
                "portion_square_meter" => $items->first()->portion_square_meter,
                "type"                 => $items->first()->type == 1 ? '個人' : '法人',
            ];
        });

        $crmClient = $this->crmClientRepository->find(
            $propertyTransactionInfoGroupBy->keys()->toArray()
        )->map(function ($client) {
            $companyInfo = [];
            if (isset($client['crmClientHasCompany'])) {
                $company = $client['crmClientHasCompany'];

                $companyInfo = [
                    'client_company_id'      => $company['id'],
                    'company_name'           => $company['company_name'],
                    'company_number'         => $company['company_number'],
                    'company_type'           => $company['company_type'],
                    'company_representative' => $company['company_representative'] ?? $client['name'],
                    'company_address'        => $company['company_address'],
                    'company_telephone'      => $company['company_telephone'],
                    'company_url'            => $company['company_url'],
                    'file'                   => self::fetchUploadRecord($company['crmClientDocument']),
                ];
            }

            return [
                ...[
                    'client_id'         => $client['id'],
                    'name'              => $client['name'],
                    'sex'               => $client['sex'],
                    'birthday'          => self::convertToRepublicDate($client['birthday']),
                    'identity_number'   => $client['identity_number'],
                    'mailing_address'   => $client['mailing_address'],
                    'residence_address' => $client['residence_address'],
                    'transfer_account'  => $client['transfer_account'],
                    'occupation'        => $client['occupation'],
                    'employer'          => $client['employer'],
                    'basic_remark'      => $client['basic_remark'],
                    'occupation_remark' => $client['occupation_remark'],
                    'life'              => $client['life'],
                    'client_contact'    => $client['crmClientContact'],
                ],
                ...$companyInfo
            ];
        });

        $crmClientRelatedPerson = $this->crmClientRelatedPersonRepository->find(
            $spaceId,
            $propertyTransactionInfoGroupBy->keys()->toArray(),
            $id
        )->map(fn ($item) => [
            'client_id'         => $item['client_id'],
            'related_client_id' => $item['related_client_id'],
            'salutation'        => $item['salutation'],
            'is_spouse'         => $item['is_spouse'],
            'name'              => $item->relatedClient['name'] ?? '',
            'sex'               => $item->relatedClient['sex'] ?? '',
            'birthday'          => self::convertToRepublicDate($item->relatedClient['birthday']),
            'identity_number'   => $item->relatedClient['identity_number'] ?? '',
            'mailing_address'   => $item->relatedClient['mailing_address'] ?? '',
            'residence_address' => $item->relatedClient['residence_address'] ?? '',
            'transfer_account'  => $item->relatedClient['transfer_account'] ?? '',
            'occupation'        => $item->relatedClient['occupation'] ?? '',
            'employer'          => $item->relatedClient['employer'] ?? '',
            'basic_remark'      => $item->relatedClient['basic_remark'] ?? '',
            'occupation_remark' => $item->relatedClient['occupation_remark'] ?? '',
            'life'              => $item->relatedClient['life'] ?? '',
            'contact'           => $item->relatedClient?->crmClientContact->toArray() ?? [ ],
        ]);

        return [
            $propertyTransactionInfoGroupBy,
            $crmClient,
            $crmClientRelatedPerson
        ];
    }

    /**
     * 新增 email & phone
     * @param  array  $relationshipPhone
     * @param  array  $relatedPersonPhone
     * @param  array  $contactData
     *
     * @return void
     */
    private function insertClientContact(array $relationshipPhone, array $relatedPersonPhone, array $contactData): void
    {
        $combinedContacts = [
            ...$relationshipPhone,
            ...$relatedPersonPhone,
            ...Arr::collapse($contactData) ?? []
        ];

        $uniqueContacts = array_map(
            'unserialize',
            array_unique(array_map('serialize', $combinedContacts))
        );

        $filteredContacts = array_filter($uniqueContacts, fn($contact) => isset($contact['value']));

        $uniqueClientIds = array_unique(
            array_column($uniqueContacts, 'client_id')
        );

        $this->crmClientContactRepository->forceDelete($uniqueClientIds);
        $this->crmClientContactRepository->insert($filteredContacts);
    }

    /**
     * 新增產權人資料
     *
     * @param  array  $clientInfo
     *
     * @return array
     */
    private function updateOrCreateClient(array $clientInfo): array
    {
        return array_map(function ($relationship) {
            return $this->crmClientRepository->updateOrCreate($relationship)->id;
        }, $clientInfo);
    }

    /**
     * 編輯產權人資料
     *
     * @param  array  $clientInfo
     *
     * @return array
     */
    private function updateOrCreateClientID(array $clientInfo): array
    {
        return array_map(function ($relationship) {
            if((string) Str::isUuid($relationship['id'])) {
                return $this->crmClientRepository->updateOrCreateId($relationship)->id;
            } else {
                return $this->crmClientRepository->updateOrCreate($relationship)->id;
            }
        }, $clientInfo);
    }
}
