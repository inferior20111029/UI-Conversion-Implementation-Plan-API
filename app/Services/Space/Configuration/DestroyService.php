<?php

declare(strict_types=1);

namespace App\Services\Space\Configuration;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmBuildingSpaceRepository;

final class DestroyService extends Service
{
    public function __construct(
        private readonly CrmBuildingSpaceRepository $crmBuildingSpaceRepository,
    ) {
    }

    /**
     * 刪除空間配置
     *
     *
     * @return void
     */
    public function execute(string $uuid): void
    {
        $this->crmBuildingSpaceRepository->destroy([$uuid]);
    }

    /**
     * 批次刪除空間配置
     *
     *
     * @return void
     */
    public function batch()
    {
        $this->crmBuildingSpaceRepository->destroy(request()->post('space_id'));
    }
}
