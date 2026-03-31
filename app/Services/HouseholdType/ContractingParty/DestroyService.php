<?php

declare(strict_types=1);

namespace App\Services\HouseholdType\ContractingParty;

use App\Support\Abstract\Service;

use App\Repositories\HouseholdType\CrmClientHasCompanyRepository;
use App\Repositories\HouseholdType\CrmClientRelatedPersonRepository;
use App\Repositories\HouseholdType\CrmPropertyTransactionInfoRepository;

final class DestroyService extends Service
{
    public function __construct(
        private readonly CrmPropertyTransactionInfoRepository $crmPropertyTransactionInfoRepository,
        private readonly CrmClientRelatedPersonRepository     $crmClientRelatedPersonRepository,
        private readonly CrmClientHasCompanyRepository        $crmClientHasCompanyRepository,
    ) {
    }

    /**
     * 刪除立約人綁定資料
     *
     * @param  string  $spaceId
     *
     * @return void
     */
    public function execute(string $spaceId): void
    {
        $clientIds = request()->get('client_id');

        $this->crmPropertyTransactionInfoRepository->forceDelete($spaceId, $clientIds);
        $this->crmClientHasCompanyRepository->forceDelete($clientIds);
        $this->crmClientRelatedPersonRepository->forceDeleteByClientId($clientIds);
    }
}
