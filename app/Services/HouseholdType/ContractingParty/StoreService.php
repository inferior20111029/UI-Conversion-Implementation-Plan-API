<?php

declare(strict_types=1);

namespace App\Services\HouseholdType\ContractingParty;

use Illuminate\Support\Arr;

use App\Support\Abstract\Service;

use App\Repositories\HouseholdType\CrmPropertyInfoRepository;
use App\Repositories\HouseholdType\CrmPropertyInfoDocumentRepository;
use App\Repositories\HouseholdType\CrmPropertyTransactionInfoRepository;
use App\Repositories\HouseholdType\CrmClientRepository;
use App\Repositories\HouseholdType\CrmClientDocumentRepository;
use App\Repositories\HouseholdType\CrmClientContactRepository;
use App\Repositories\HouseholdType\CrmClientRelatedPersonRepository;
use App\Repositories\HouseholdType\CrmClientHasCompanyRepository;
use App\Repositories\HouseholdType\CrmIntroducerInfoRepository;

final class StoreService extends Service
{
    use \App\Support\Trait\ContractingParty\ColumnTrait;
    use \App\Support\Trait\ContractingParty\ClientContactColumnTrait;
    use \App\Support\Trait\ContractingParty\ConvertDateTrait;
    use \App\Support\Trait\ContractingParty\RelatedPersonColumnTrait;
    use \App\Support\Trait\ContractingParty\IntroducerColumnTrait;

    public function __construct(
        private readonly CrmPropertyInfoRepository            $crmPropertyInfoRepository,
        private readonly CrmPropertyInfoDocumentRepository    $crmPropertyInfoDocumentRepository,
        private readonly CrmClientRepository                  $crmClientRepository,
        private readonly CrmPropertyTransactionInfoRepository $crmPropertyTransactionInfoRepository,
        private readonly CrmClientContactRepository           $crmClientContactRepository,
        private readonly CrmClientRelatedPersonRepository     $crmClientRelatedPersonRepository,
        private readonly CrmClientHasCompanyRepository        $crmClientHasCompanyRepository,
        private readonly CrmClientDocumentRepository          $crmClientDocumentRepository,
        private readonly CrmIntroducerInfoRepository          $crmIntroducerInfoRepository,
    ) {
    }

    /**
     * 新增立約人資料
     *
     * @return void
     * @throws \Exception
     */
    public function create(string $spaceId): void
    {
        $this->crmPropertyInfoRepository->updateSpaceEdit($spaceId);
        $propertyInfoId = $this->crmPropertyInfoRepository->insertGetId(self::fetchColumnData($spaceId));

        [$filteredPropertyTitleTypes, $clientInfo, $clientCompanyInfo, $clientCompanyFileInfo] = $this->fetchRelationshipColumnData($spaceId);
        $fileIds = request()->post('file_id');

        // 共有物分割圖上傳
        $this->crmPropertyInfoDocumentRepository->insert(self::fetchFileColumnData($propertyInfoId, 'property_info_id', $fileIds));

        // 新增產權人資料
        $clientIds = self::updateOrCreateClient($clientInfo);

        // 新增法人產權人資料
        self::transactionCompanyInfoInsert($clientCompanyInfo, $clientIds, $clientCompanyFileInfo);

        // 新增產權人資料
        self::transactionInfoInsert($filteredPropertyTitleTypes, $clientIds, $spaceId, $propertyInfoId);

        // 產權人phone & mail 資料
        $relationshipPhone = self::fetchContactColumnData($clientIds);

        // 新增配偶親屬資料
        $relatedPersonInfo = self::fetchRelatedPersonColumnData();

        $relatedPersonInsert = self::fetchRelatedPersonInsert($relatedPersonInfo, $clientIds, $spaceId, $propertyInfoId);
        $modifiedCollection = $relatedPersonInsert->map(function ($item) {
            unset($item['contact']);
            return $item;
        });

        $this->crmClientRelatedPersonRepository->insert($modifiedCollection->toArray());

        // 配偶親屬phone & mail 資料
        $relatedPersonPhone = Arr::collapse($relatedPersonInsert->pluck('contact'));

        // 介紹人
        [$clientIntroducerInfo, $additionalInfo, $contactData] = $this->fetchIntroducerColumnData($propertyInfoId);

        if ($clientIntroducerInfo) {
            $clientIds = self::updateOrCreateClient($clientIntroducerInfo);
            $contactData = self::transactionIntroducerInfoInsert($contactData, $additionalInfo, $clientIds);
        }

        // 新增phone & mail
        self::insertClientContact($relationshipPhone, $relatedPersonPhone, $contactData);
    }
}
