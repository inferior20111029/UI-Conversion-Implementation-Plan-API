<?php

declare(strict_types=1);

namespace App\Services\Space\ParkingSpaceConfiguration;

use Illuminate\Support\Facades\DB;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmBuildingSpaceRepository;
use App\Repositories\Space\CrmParkingSpaceRepository;
use App\models\CrmBuildingSpaceState;

final class StoreService extends Service
{
    use \App\Support\Trait\Space\CrmParkingSpaceTrait;

    public function __construct(
        private readonly CrmBuildingSpaceRepository $crmBuildingSpaceRepository,
        private readonly CrmParkingSpaceRepository  $crmParkingSpaceRepository,
        private readonly CrmBuildingSpaceState      $crmBuildingSpaceState,
    ) {
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function create()
    {
        $data = $this->postColumn();

        DB::transaction(function () use ($data) {
            $id = $this->crmParkingSpaceRepository->create($data)->id;
            $this->crmBuildingSpaceState->space_id = $id;
            $this->crmBuildingSpaceState->rental_and_sale = (string) request()->post('rental_and_sale');
            $this->crmBuildingSpaceState->save();
        });
    }
}
