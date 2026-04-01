<?php

declare(strict_types=1);

namespace App\Services\Space\Certification;

use App\Support\Abstract\Service;

use App\Repositories\Certification\BuildingSpaceCertificationRepository;
use App\Repositories\Certification\BuildingSpaceCertificationFileRepository;

final class DestroyService extends Service
{
    public function __construct(
        private readonly BuildingSpaceCertificationRepository $buildingSpaceCertificationRepository,
        private readonly BuildingSpaceCertificationFileRepository $buildingSpaceCertificationFileRepository,
    ) {
    }

    /**
     * @param  int  $id
     *
     * @return void
     */
    public function execute(int $id): void
    {
        $this->buildingSpaceCertificationRepository->destroy([$id]);
        $this->buildingSpaceCertificationFileRepository->forceDelete([$id]);
    }
}
