<?php

declare(strict_types=1);

namespace App\Services\Space\BatchSetting;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmBuildingSpaceRepository;
use App\Repositories\Warranty\CrmBuildingSpaceWarrantyRepository;

final class TransferWarrantyDateService extends Service
{
    public function __construct(
        private readonly CrmBuildingSpaceRepository         $crmBuildingSpaceRepository,
        private readonly CrmBuildingSpaceWarrantyRepository $crmBuildingSpaceWarrantyRepository,
    ) {
    }

    /**
     * 批次交屋日期設定 & 保固日期
     *
     * @return void
     */
    public function execute($request): void
    {
        $spaceIds     = $request->post('space_id');
        $handoverDate = $request->post('handover_date');
        $warrantyData = $request->post('warranty');
        $companyId    = crm('company_id');
        $comid        = crm('community_id');

        $spaceData = array_map(fn ($spaceId) => [
            'handover_date' => $handoverDate,
            'space_id'      => $spaceId,
            'company_id'    => $companyId,
            'comid'         => $comid,
        ], $spaceIds);

        $this->crmBuildingSpaceRepository->upsert($spaceData);

        $this->crmBuildingSpaceWarrantyRepository->forceDelete($spaceIds);

        $warrantyUpsertData = collect($spaceIds)->flatMap(function ($spaceId) use ($warrantyData) {
            return collect($warrantyData)->map(fn ($warranty) => [
                'crm_warranty_select_id' => $warranty['id'],
                'warranty_date'          => $warranty['date'],
                'space_id'               => $spaceId,
            ]);
        })->toArray();

        $this->crmBuildingSpaceWarrantyRepository->upsert($warrantyUpsertData);
    }
}