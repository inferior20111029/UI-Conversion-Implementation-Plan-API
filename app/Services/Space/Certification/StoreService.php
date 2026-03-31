<?php

declare(strict_types=1);

namespace App\Services\Space\Certification;

use App\Support\Abstract\Service;

use App\Support\Tool\File\FileMagic;
use App\Repositories\Certification\BuildingSpaceCertificationRepository;
use App\Repositories\Certification\BuildingSpaceCertificationFileRepository;

final class StoreService extends Service
{
    use \App\Support\Trait\Certification\ColumnTrait;
    public function __construct(
        private readonly BuildingSpaceCertificationRepository $buildingSpaceCertificationRepository,
        private readonly BuildingSpaceCertificationFileRepository $buildingSpaceCertificationFileRepository,
    ) {
    }

    /**
     * 新增認證標章資料
     *
     * @return void
     */
    public function execute($request): void
    {
        $version = $this->buildingSpaceCertificationRepository->count(self::fetchUpdateColumnData($request));
        $id = $this->buildingSpaceCertificationRepository->insert(self::fetchUpdateColumnData($request, $version));

        $file = [];
        foreach (request()->post('file_id') as $fileId) {
            $file[] = [
                'certification_id' => $id->id,
                'avatar'           => (int)FileMagic::find($fileId)->get()?->id,
            ];
        }

        $this->buildingSpaceCertificationFileRepository->insert($file);
    }
}
