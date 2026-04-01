<?php

declare(strict_types=1);

namespace App\Services\HouseholdType\ContractingParty;

use App\Support\Abstract\Service;
use App\Support\Enum\FetchMessage;
use App\Support\Enum\TransferCauseType;
use App\Support\Enum\PropertyTitleType;

use Symfony\Component\HttpFoundation\Response;
use App\Repositories\HouseholdType\CrmClientRepository;
use App\Repositories\HouseholdType\CrmPropertyInfoRepository;
use App\Repositories\HouseholdType\CrmIntroducerInfoRepository;
use App\Repositories\HouseholdType\CrmPropertyInfoDocumentRepository;
use App\Repositories\HouseholdType\CrmClientRelatedPersonRepository;
use App\Repositories\HouseholdType\CrmPropertyTransactionInfoRepository;

final class ShowService extends Service
{
    use \App\Support\Trait\ContractingParty\ConvertDateTrait;
    use \App\Support\Trait\ContractingParty\ColumnTrait;
    use \App\Support\Trait\ContractingParty\IntroducerColumnTrait;

    public function __construct(
        private readonly CrmPropertyInfoRepository            $crmPropertyInfoRepository,
        private readonly CrmPropertyInfoDocumentRepository    $crmPropertyInfoDocumentRepository,
        private readonly CrmPropertyTransactionInfoRepository $crmPropertyTransactionInfoRepository,
        private readonly CrmClientRepository                  $crmClientRepository,
        private readonly CrmClientRelatedPersonRepository     $crmClientRelatedPersonRepository,
        private readonly CrmIntroducerInfoRepository          $crmIntroducerInfoRepository,
    ) {
    }

    /**
     * 立約人資料
     *
     * @return array
     */
    public function execute(string $spaceId): array
    {
        $crmPropertyInfo = $this->crmPropertyInfoRepository->paginateWithSecondRecord($spaceId);

        if ($crmPropertyInfo->isEmpty()) {
            $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
        }

        $clientNames = $crmPropertyInfo->where('is_edit', 0)->flatMap(function ($item) {
            return $item->crmPropertyTransactionInfo->map(fn ($transaction) => $transaction->crmClient->name);
        })->values();

        return $crmPropertyInfo->map(function ($item, $index) use ($clientNames, $crmPropertyInfo) {
            $isLastItem = $index === $crmPropertyInfo->count() - 1;
            $originalName = $isLastItem ? '起造人' : ($clientNames[$index] ?? null);

            return $this->fetchPaginateColumnData($item, $originalName);
        })->toArray();
    }

    /**
     * @return array
     */
    public function create(): array
    {
        $allowedNames = [
            'inhabitant', 'related_main', 'related_promiser', 'related_surety', 'related_loaner',
            'related_paymenter', 'related_stockholder', 'related_commission', 'related_cohabitant'
        ];

        $filteredPropertyTitleTypes = array_filter(PropertyTitleType::cases(), function ($propertyTitleType) use ($allowedNames) {
            return in_array($propertyTitleType->name, $allowedNames);
        });

        return [
            'transfer_cause' => TransferCauseType::values(),
            'property_title' => array_values($filteredPropertyTitleTypes),
        ];
    }

    private function filterPropertyTitleTypes(string $allowedName): array
    {
        return array_filter(
            PropertyTitleType::cases(),
            function ($propertyTitleType) use ($allowedName) {
                return $propertyTitleType->name === $allowedName;
            }
        );
    }

    /**
     * @return array
     */
    public function update(string $spaceId, int $id): array
    {
        $crmPropertyInfo = $this->crmPropertyInfoRepository->find($id);
        $crmPropertyInfoDocument = $this->crmPropertyInfoDocumentRepository->findByPropertyInfoId($id);
        $propertyTransactionInfo = $this->crmPropertyTransactionInfoRepository->findBySpaceId($spaceId, $id);

        [$propertyTransactionInfoGroupBy, $crmClient, $crmClientRelatedPerson]
            = self::propertyInfo($propertyTransactionInfo, $spaceId, $id);

        return [
            'property_info'             => self::fetchCommonColumnData($crmPropertyInfo),
            'property_info_file'        => self::fetchUploadRecord($crmPropertyInfoDocument),
            'property_transaction_info' => $propertyTransactionInfoGroupBy->values()->toArray(),
            'relationship'  => $crmClient->toArray(),
            'salutation'    => $crmClientRelatedPerson,
            'introducer'    => self::fetchIntroducerUpdateData($id),
        ];
    }

    /**
     * @param  string  $identityNumber
     *
     * @return array|never
     */
    public function fetchIdentityNumber(string $identityNumber)
    {
        $result = $this->crmClientRepository->findIdentityNumber($identityNumber);

        if ($result === null) {
            $this->fails(FetchMessage::NOT_FOUND->value, '400');
        }

        return $result->toArray();
    }
}
