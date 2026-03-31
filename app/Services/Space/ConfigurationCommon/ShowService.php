<?php

declare(strict_types=1);

namespace App\Services\Space\ConfigurationCommon;

use Illuminate\Support\Arr;
use App\Support\Abstract\Service;

use App\Support\Enum\CrmHouseType;
use App\Repositories\Space\CrmBuildingCommonSpaceRepository;

final class ShowService extends Service
{
    public function __construct(
        private readonly CrmBuildingCommonSpaceRepository $crmBuildingCommonSpaceRepository,
    ) {
    }

    /**
     * 回傳空間組態
     *
     * @return array
     */
    public function execute(): array
    {
        $filterKey  = request()->get('filter_key', []);
        $comid      = crm('community_id');
        $companyId  =  crm('company_id');
        $filteredData = array_filter($filterKey, fn ($value) => !is_null($value));

        $crmBuildingSpace = $this->crmBuildingCommonSpaceRepository
            ->getPaginatedSpaceConfigurations($comid, $companyId, $filteredData);

        return $this->paginateResponseFormat(
            $crmBuildingSpace,
            $crmBuildingSpace->getCollection()->map(fn ($item) => [
                ...Arr::except($item->toArray(), ['crm_building_common_info', 'updated_at', 'created_at']) ,
                ...[
                    'block_id'               => $item->crmBuildingCommonInfo?->block_id,
                    'doorplate'              => $item->crmBuildingCommonInfo?->doorplate,
                    'main_application_value' => CrmHouseType::array()[$item->crmBuildingCommonInfo['main_application']] ?? null
                ]
            ])
        );
    }
}