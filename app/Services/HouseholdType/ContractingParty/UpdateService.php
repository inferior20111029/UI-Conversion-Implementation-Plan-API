<?php

declare(strict_types=1);

namespace App\Services\HouseholdType\ContractingParty;

use Illuminate\Support\Arr;

use App\Support\Abstract\Service;

use App\Support\Tool\File\FileMagic;
use App\Repositories\HouseholdType\CrmClientRepository;
use App\Repositories\HouseholdType\CrmClientContactRepository;
use App\Repositories\HouseholdType\CrmPropertyInfoRepository;
use App\Repositories\HouseholdType\CrmClientDocumentRepository;
use App\Repositories\HouseholdType\CrmIntroducerInfoRepository;
use App\Repositories\HouseholdType\CrmClientHasCompanyRepository;
use App\Repositories\HouseholdType\CrmClientRelatedPersonRepository;
use App\Repositories\HouseholdType\CrmPropertyInfoDocumentRepository;
use App\Repositories\HouseholdType\CrmPropertyTransactionInfoRepository;

final class UpdateService extends Service
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
     * 更新立約人資料
     *
     * @param  string  $spaceId
     * @param  int  $id
     *
     * @return void
     */
    public function execute(string $spaceId, int $id): void
    {
        $this->crmPropertyInfoRepository->upsert(
            self::fetchColumnData($spaceId, 'edit') + ['id' => $id]
        );

        [$filteredPropertyTitleTypes, $clientInfo, $clientCompanyInfo, $clientCompanyFileInfo] = $this->fetchRelationshipColumnData($spaceId, 'edit');

        // 共有物分割圖上傳
        $fileId = request()->post('del_file_id', []);
        $this->crmPropertyInfoDocumentRepository->forceDelete($id, is_array($fileId) ? $fileId : [$fileId]);
        $fileIds = request()->post('file_id');
        $this->crmPropertyInfoDocumentRepository->insert(self::fetchFileColumnData($id, 'property_info_id', $fileIds));

        // 更新產權人資料
        $clientIds = self::updateOrCreateClientID($clientInfo);

        // 更新法人產權人資料
        self::transactionCompanyInfoInsert($clientCompanyInfo, $clientIds, $clientCompanyFileInfo, 'edit');

        // 新增產權異動資料
        $this->crmPropertyTransactionInfoRepository->forceDelete($spaceId, $id, $clientIds);
        self::transactionInfoInsert($filteredPropertyTitleTypes, $clientIds, $spaceId, $id);

        // 新增phone & mail
        $relationshipPhone = self::fetchContactColumnData($clientIds);

        // 新增配偶親屬資料
        $relatedPersonInfo = self::fetchRelatedPersonColumnData('edit');

        $relatedPersonInsert = self::fetchRelatedPersonInsert($relatedPersonInfo, $clientIds, $spaceId, $id);
        $modifiedCollection = $relatedPersonInsert->map(function ($item) {
            unset($item['contact']);
            return $item;
        });

        $this->crmClientRelatedPersonRepository->forceDeleteOfInfoId((int) $id);
        $this->crmClientRelatedPersonRepository->insert($modifiedCollection->toArray());

        // 配偶親屬phone & mail
        $relatedPersonPhone = Arr::collapse($relatedPersonInsert->pluck('contact'));

        // 刪除介紹人
        if (self::getIntroducerDeletionIds()[0] !== '') {
            $this->crmIntroducerInfoRepository->forceDelete(
                self::getIntroducerDeletionIds()
            );
        }

        // 介紹人
        [$clientIntroducerInfo, $additionalInfo, $contactData] = self::fetchIntroducerColumnData($id, 'edit');

        if ($clientIntroducerInfo) {
            $clientIds = self::updateOrCreateClientID($clientIntroducerInfo);
            $contactData = self::transactionIntroducerInfoInsert($contactData, $additionalInfo, $clientIds, 'edit');
        }

        // 新增phone & mail
        self::insertClientContact($relationshipPhone, $relatedPersonPhone, $contactData);
    }
}