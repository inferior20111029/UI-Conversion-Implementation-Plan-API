<?php

declare(strict_types=1);

namespace App\Services\Equipment\EquipmentBatch;

use App\Support\Abstract\Service;
use Illuminate\Support\Arr;

use App\Repositories\Equipment\CrmEquipmentRepository;
use App\Repositories\Equipment\CrmEquipmentCategoryRepository;
use App\Repositories\Equipment\CrmEquipmentUploadRecordRepository;

final class UpdateService extends Service
{
    use \App\Support\Trait\Equipment\ColumnTrait;
    use \App\Support\Trait\Equipment\EquipmentTrait;

    public function __construct(
        private readonly CrmEquipmentCategoryRepository     $crmEquipmentCategoryRepository,
        private readonly CrmEquipmentRepository             $crmEquipmentRepository,
        private readonly CrmEquipmentUploadRecordRepository $crmEquipmentUploadRecordRepository,
    ) {
    }

    /**
     * 更新元件
     *
     * @param $id
     *
     * @return void
     */
    public function execute(): void
    {
        $ids = request()->post('ids');
        $this->crmEquipmentRepository->batchUpdate($ids, [
            'properties' => request()->post('properties'),
            'updated_at' => now(),
        ]);

        if (!is_null(request()->post('files_type'))) {
            // 刪除檔案
            $this->crmEquipmentUploadRecordRepository->delByEquipmentId($ids);

            // 新增檔案
            $insertData = [];
            foreach ($ids as $id) {
                $insertData[] = $this->fetchEquipmentColumnFileData($id, request()->post('files_type'));
            }

            $this->crmEquipmentUploadRecordRepository->insert(Arr::collapse($insertData));
        }
    }
}
