<?php

declare(strict_types=1);

namespace App\Services\Space\ParkingSpaceSelect;

use Illuminate\Support\Collection;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmParkingSpaceSelectRepository;

final class ShowService extends Service
{
    public function __construct(
        private readonly CrmParkingSpaceSelectRepository $crmParkingSpaceSelectRepository,
    ) {
    }

    /**
     * 回傳空間組態
     *
     * @return Collection
     */
    public function execute(): Collection
    {
        return $this->crmParkingSpaceSelectRepository
            ->findAll()
            ->groupBy('type');
    }
}
