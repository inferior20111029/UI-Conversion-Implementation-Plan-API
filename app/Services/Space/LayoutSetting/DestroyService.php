<?php

declare(strict_types=1);

namespace App\Services\Space\LayoutSetting;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmLayoutSettingRepository;

final class DestroyService extends Service
{
    public function __construct(
        private readonly CrmLayoutSettingRepository $crmLayoutSettingRepository,
    ) {
    }

    /**
     * 刪除格局設定
     *
     * @return void
     */
    public function execute($id): void
    {
        $this->crmLayoutSettingRepository->destroy([$id]);
    }

    /**
     * 批次刪除格局設定
     *
     * @return void
     */
    public function batch(): void
    {
        $this->crmLayoutSettingRepository->destroy(request()->post('ids'));
    }
}
