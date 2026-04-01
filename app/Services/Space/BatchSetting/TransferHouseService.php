<?php

declare(strict_types=1);

namespace App\Services\Space\BatchSetting;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmBuildingSpaceRepository;

final class TransferHouseService extends Service
{
    public function __construct(
        private readonly CrmBuildingSpaceRepository       $crmBuildingSpaceRepository,
    ) {
    }

    /**
     * 批次列入資產
     *
     * @return void
     */
    public function execute($request): void
    {
        $spaceIds   = $request->post('space_id');
        $companyId  = crm('company_id');
        $comid      = crm('community_id');

        $upsertData = array_map(function ($spaceId) use ($companyId, $comid) {
            return [
                'house_status' => '成屋',
                'space_id'     => $spaceId,
                'company_id'   => $companyId,
                'comid'        => $comid,
            ];
        }, $spaceIds);

        $this->crmBuildingSpaceRepository->upsert($upsertData);
    }
}