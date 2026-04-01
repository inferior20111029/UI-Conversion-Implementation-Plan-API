<?php

declare(strict_types=1);

namespace App\Services\Space\BatchSetting;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmLayoutSettingRepository;
use App\Repositories\Space\CrmBuildingSpaceRepository;
use App\Repositories\Space\CrmBuildingSpaceLayoutRepository;

final class LayoutSettingService extends Service
{
    use \App\Support\Trait\Certification\ColumnTrait;
    public function __construct(
        private readonly CrmLayoutSettingRepository       $crmLayoutSettingRepository,
        private readonly CrmBuildingSpaceRepository       $crmBuildingSpaceRepository,
        private readonly CrmBuildingSpaceLayoutRepository $crmBuildingSpaceLayoutRepository,
    ) {
    }

    /**
     * 批次設定新增格局
     *
     * @return void
     */
    public function execute($request): void
    {
        $crmLayoutSettingId = $request->post('crm_layout_setting_id');
        $spaceIds           = $request->post('space_id');
        $companyId          = crm('company_id');
        $comid              = crm('community_id');

        $upsertData = array_map(function ($spaceId) use ($crmLayoutSettingId, $companyId, $comid) {
            return [
                'crm_layout_setting_id' => $crmLayoutSettingId ?? 0,
                'space_id'              => $spaceId,
                'company_id'            => $companyId,
                'comid'                 => $comid,
            ];
        }, $spaceIds);

        $this->crmBuildingSpaceRepository->upsert($upsertData);
        $this->crmBuildingSpaceLayoutRepository->forceDelete($spaceIds);

        if (!$crmLayoutSettingId) {
            $data = $request->post('data');

            $upsertDataWithQuantities = array_reduce(array_keys($data), function ($carry, $type) use ($data, $spaceIds) {
                foreach ($spaceIds as $spaceId) {
                    $carry[] = [
                        'space_id' => $spaceId,
                        'type'     => $type,
                        'quantity' => $data[$type],
                    ];
                }
                return $carry;
            }, []);

            $this->crmBuildingSpaceLayoutRepository->upsert($upsertDataWithQuantities);
        }
    }
}