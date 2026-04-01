<?php

declare(strict_types=1);

namespace App\Services\Space\WarrantySelect;

use App\Support\Abstract\Service;

use App\Repositories\Warranty\CrmWarrantySelectRepository;

final class DestroyService extends Service
{
    public function __construct(
        private readonly CrmWarrantySelectRepository $crmWarrantySelectRepository,
    ) {
    }

    /**
     * @param  int  $id
     *
     * @return void
     */
    public function execute(int $id): void
    {
        $this->crmWarrantySelectRepository->destroy([$id]);
    }
}
