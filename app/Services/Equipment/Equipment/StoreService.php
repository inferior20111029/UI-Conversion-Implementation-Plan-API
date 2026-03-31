<?php

declare(strict_types=1);

namespace App\Services\Equipment\Equipment;

use App\Support\Abstract\Service;

use App\Repositories\Equipment\CrmEquipmentRepository;
use App\Repositories\Equipment\CrmEquipmentComponentRepository;
use App\Repositories\Equipment\CrmEquipmentUploadRecordRepository;

final class StoreService extends Service
{
    use \App\Support\Trait\Equipment\ColumnTrait;
    use \App\Support\Trait\Equipment\EquipmentTrait;

    public function __construct(
        private readonly CrmEquipmentRepository             $crmEquipmentRepository,
        private readonly CrmEquipmentUploadRecordRepository $crmEquipmentUploadRecordRepository,
        private readonly CrmEquipmentComponentRepository    $crmEquipmentComponentRepository,
    ) {
    }

    /**
     * 取得資料
     *
     * @return array
     */
    public function execute(): array
    {
        return [
            'category'   => self::fetchCategory(),
            'household'  => self::fetchBuildingSpaces(),
            'properties' => self::fetchProperties(),
        ];
    }

    public function create()
    {
        $insertData = $this->fetchEquipmentColumnData();
        $id = $this->crmEquipmentRepository->insert($insertData)->id;

        $insertData = $this->fetchEquipmentColumnFileData($id);
        $this->crmEquipmentUploadRecordRepository->insert($insertData);

        $this->upsertEquipmentComponent($id);
    }
}
