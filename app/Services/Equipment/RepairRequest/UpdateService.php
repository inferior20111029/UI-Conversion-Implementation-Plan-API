<?php

declare(strict_types=1);

namespace App\Services\Equipment\RepairRequest;

use App\Support\Abstract\Service;

use App\Repositories\Equipment\CrmEquipmentRepository;
use App\Repositories\Equipment\RepairRecordRepository;
use App\Repositories\Equipment\MaintainCategoryRepository;
use App\Repositories\Equipment\RepairRecordFileRepository;

final class UpdateService extends Service
{
    use \App\Support\Trait\RepairRequest\MaintainCategoryTrait;
    use \App\Support\Trait\RepairRequest\ColumnTrait;

    public function __construct(
        private readonly CrmEquipmentRepository      $crmEquipmentRepository,
        private readonly MaintainCategoryRepository  $maintainCategoryRepository,
        private readonly RepairRecordFileRepository  $repairRecordFileRepository,
        private readonly RepairRecordRepository      $repairRecordRepository,
    ) {
    }

    /**
     * 回傳提報修繕資料
     *
     * @return array
     */
    public function execute(string $id): array
    {
        $repairRecord = $this->repairRecordRepository->findById($id);
        return self::fetchEditData($repairRecord);
    }

    /**
     * 更新提報修繕內容
     *
     * @param string $id
     * @return void
     */
    public function update(string $id): void
    {
        $this->repairRecordRepository->upsert(self::fetchColumnData('edit') + ['id' => $id]);
        $this->repairRecordFileRepository->forceDelete($id);
        $this->repairRecordFileRepository->insert(self::fetchFileData($id));
    }
}
