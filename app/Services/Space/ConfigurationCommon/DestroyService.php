<?php

declare(strict_types=1);

namespace App\Services\Space\ConfigurationCommon;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmBuildingCommonSpaceRepository;

final class DestroyService extends Service
{
    public function __construct(
        private readonly CrmBuildingCommonSpaceRepository $crmBuildingCommonSpaceRepository,
    ) {
    }

    /**
     * 刪除空間配置
     *
     * @return void
     */
    public function execute(string $uuid): void
    {
        $this->crmBuildingCommonSpaceRepository->destroy([$uuid]);
    }
}
