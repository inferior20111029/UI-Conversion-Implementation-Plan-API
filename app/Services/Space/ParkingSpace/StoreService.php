<?php

declare(strict_types=1);

namespace App\Services\Space\ParkingSpace;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmParkingSpaceRepository;

final class StoreService extends Service
{
    use \App\Support\Trait\Space\CrmParkingSpaceTrait;

    public function __construct(
        private readonly CrmParkingSpaceRepository  $crmParkingSpaceRepository,
    ) {
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function create(string $spaceId)
    {
        $id = request()->post('id');
        $this->crmParkingSpaceRepository->update($id, ['space_id' => $spaceId]);
    }
}
