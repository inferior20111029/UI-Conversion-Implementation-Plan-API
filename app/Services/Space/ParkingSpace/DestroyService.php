<?php

declare(strict_types=1);

namespace App\Services\Space\ParkingSpace;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmParkingSpaceRepository;

final class DestroyService extends Service
{
    public function __construct(
        private readonly CrmParkingSpaceRepository $crmParkingSpaceRepository,
    ) {
    }

    /**
     * 取消配置戶別
     *
     * @return void
     */
    public function destroy(string $id): void
    {
        $this->crmParkingSpaceRepository->cancel($id);
    }
}