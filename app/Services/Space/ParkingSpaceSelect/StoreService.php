<?php

declare(strict_types=1);

namespace App\Services\Space\ParkingSpaceSelect;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmParkingSpaceSelectRepository;

final class StoreService extends Service
{
    use \App\Support\Trait\Space\CrmParkingSpaceTrait;

    public function __construct(
        private readonly CrmParkingSpaceSelectRepository $crmParkingSpaceSelectRepository,
    ) {
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function create()
    {
        $type = request()->post('type');
        $value = request()->post('value');

        $this->crmParkingSpaceSelectRepository->insert([
            'company_id' => crm('company_id'),
            'type'       => $type,
            'value'      => $value,
        ]);
    }
}
