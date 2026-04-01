<?php

declare(strict_types=1);

namespace App\Services\Space\ParkingSpaceConfiguration;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmParkingSpaceRepository;

final class DestroyService extends Service
{
    public function __construct(
        private readonly CrmParkingSpaceRepository $crmParkingSpaceRepository,
    ) {
    }

    /**
     * 刪除空間配置
     *
     * @return void
     */
    public function execute(string $uuid): void
    {
        $this->crmParkingSpaceRepository->destroy([$uuid]);
    }

    /**
     * 取消配置戶別
     *
     * @return void
     */
    public function cancel(string $id): void
    {
        $this->crmParkingSpaceRepository->cancel($id);
    }
}
