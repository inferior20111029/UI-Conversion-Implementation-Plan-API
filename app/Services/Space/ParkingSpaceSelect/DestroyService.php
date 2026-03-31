<?php

declare(strict_types=1);

namespace App\Services\Space\ParkingSpaceSelect;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmParkingSpaceSelectRepository;

final class DestroyService extends Service
{
    public function __construct(
        private readonly CrmParkingSpaceSelectRepository $crmParkingSpaceSelectRepository
    ) {
    }

    /**
     * 刪除空間配置
     *
     * @return void
     */
    public function execute(string $uuid): void
    {
        $this->crmParkingSpaceSelectRepository->destroy([$uuid]);
    }
}
