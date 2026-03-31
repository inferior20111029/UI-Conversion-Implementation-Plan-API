<?php

declare(strict_types=1);

namespace App\Services\Space\ParkingSpaceSelect;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmParkingSpaceSelectRepository;

final class UpdateService extends Service
{
    use \App\Support\Trait\Space\CrmParkingSpaceTrait;

    public function __construct(
        private readonly CrmParkingSpaceSelectRepository $crmParkingSpaceSelectRepository
    ) {
    }

    /**
     * 更新車位參數值
     *
     * @param string $id
     * @return void
     */
    public function update(string $id): void
    {
        $value = request()->post('value');
        $this->crmParkingSpaceSelectRepository->update($id, compact('value'));
    }
}
