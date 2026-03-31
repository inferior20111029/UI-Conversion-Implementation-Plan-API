<?php

declare(strict_types=1);

namespace App\Services\Space\LayoutSetting;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmLayoutSettingRepository;
use App\Repositories\Space\CrmLayoutSettingDetailRepository;

final class StoreService extends Service
{
    use \App\Support\Trait\LayoutSetting\ColumnTrait;

    public function __construct(
        private readonly CrmLayoutSettingRepository       $crmLayoutSettingRepository,
        private readonly CrmLayoutSettingDetailRepository $crmLayoutSettingDetailRepository,
    ) {
    }

    public function create()
    {
        $id = $this->crmLayoutSettingRepository->insert(
            [
                'company_id' => crm('company_id'),
                'comid'      => crm('community_id'),
                'updated_at' => now(),
                'created_at' => now(),
            ] + $this->fetchColumnData()
        )->id;

       $this->crmLayoutSettingDetailRepository->insert($this->fetchDetailColumnData($id));
    }
}
