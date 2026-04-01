<?php

declare(strict_types=1);

namespace App\Services\Equipment\Equipment;

use App\Support\Abstract\Service;

use App\Repositories\Equipment\CrmEquipmentRepository;
use App\Repositories\Equipment\CrmEquipmentCategoryRepository;
use App\Repositories\Equipment\CrmEquipmentComponentRepository;
use App\Repositories\Equipment\CrmEquipmentUploadRecordRepository;

final class UpdateService extends Service
{
    use \App\Support\Trait\Equipment\ColumnTrait;
    use \App\Support\Trait\Equipment\EquipmentTrait;

    public function __construct(
        private readonly CrmEquipmentCategoryRepository     $crmEquipmentCategoryRepository,
        private readonly CrmEquipmentRepository             $crmEquipmentRepository,
        private readonly CrmEquipmentUploadRecordRepository $crmEquipmentUploadRecordRepository,
        private readonly CrmEquipmentComponentRepository    $crmEquipmentComponentRepository,
    ) {
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function execute($id): array
    {
        return [
            'category'   => self::fetchCategory(),
            'household'  => self::fetchBuildingSpaces(),
        ] +  $this->fetchColumnData($this->crmEquipmentRepository->findById($id));
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function show($id): array
    {
        return $this->fetchColumnData($this->crmEquipmentRepository->findById($id));
    }

    /**
     * 更新元件
     *
     * @param $id
     * @return void
     */
    public function update($id): void
    {
        $updateData = $this->fetchEquipmentColumnData('edit');
        $this->crmEquipmentRepository->update($id, $updateData);

        if (!is_null(request()->post('del_files'))) {
            // 刪除檔案
            $this->crmEquipmentUploadRecordRepository->delByAvatar(
                request()->post('del_files')
            );
        }
        // 新增檔案
        $insertData = $this->fetchEquipmentColumnFileData($id);
        $this->crmEquipmentUploadRecordRepository->insert($insertData);

        $this->crmEquipmentComponentRepository->forceDeleteById(request()->post('del_component_id') ?? []);
        $this->upsertEquipmentComponent((int) $id);
    }
}