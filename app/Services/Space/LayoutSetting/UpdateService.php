<?php

declare(strict_types=1);

namespace App\Services\Space\LayoutSetting;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmLayoutSettingRepository;
use App\Repositories\Space\CrmLayoutSettingDetailRepository;

final class UpdateService extends Service
{
    use \App\Support\Trait\LayoutSetting\ColumnTrait;

    public function __construct(
        private readonly CrmLayoutSettingRepository       $crmLayoutSettingRepository,
        private readonly CrmLayoutSettingDetailRepository $crmLayoutSettingDetailRepository,
    ) {
    }

    /**
     * 回傳格局設定
     *
     * @return array
     */
    public function execute(string $id): array
    {
        $crmLayoutSetting = $this->crmLayoutSettingRepository->findById($id);

        return [
            ...[
                'id'         => $crmLayoutSetting->id,
                'name'       => $crmLayoutSetting->name,
                'type'       => $crmLayoutSetting->type,
                'floor_type' => $crmLayoutSetting->floor_type,
            ],
            ...$crmLayoutSetting->crmLayoutSettingDetail
                ->pluck('quantity', 'type')
                ->toArray()
        ];
    }

    /**
     * 更新格局設定資料
     *
     * @param  int  $id
     *
     * @return void
     */
    public function update($id): void
    {
        $this->crmLayoutSettingRepository->update(
            $id,
            ['updated_at' => now()] + $this->fetchColumnData()
        );

        $this->crmLayoutSettingDetailRepository->forceDelete($id);

        $this->crmLayoutSettingDetailRepository->insert(
           $this->fetchDetailColumnData($id)
        );
    }
}
