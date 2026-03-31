<?php

declare(strict_types=1);

namespace App\Services\Equipment\RepairRequest;

use App\Support\Abstract\Service;
use App\Repositories\Equipment\CrmEquipmentRepository;
use App\Repositories\Equipment\MaintainCategoryRepository;
use App\Repositories\Equipment\RepairRecordRepository;
use App\Repositories\Equipment\RepairRecordFileRepository;

final class StoreService extends Service
{
    use \App\Support\Trait\RepairRequest\MaintainCategoryTrait;
    use \App\Support\Trait\RepairRequest\ColumnTrait;
    use \App\Support\Trait\RepairRequest\ReportRepairTrait;

    public function __construct(
        private readonly CrmEquipmentRepository      $crmEquipmentRepository,
        private readonly MaintainCategoryRepository  $maintainCategoryRepository,
        private readonly RepairRecordFileRepository  $repairRecordFileRepository,
        private readonly RepairRecordRepository      $repairRecordRepository,
    ) {
    }

    /**
     * @return array
     */
    public function execute(): array
    {
        $equipments = $this->crmEquipmentRepository->findBySpaceId(request()->space_id ?? '')
            ->groupBy('space')
            ->map(function ($group, $key) {
                return [
                    'space'     => $key,
                    'equipment' => $group->map(function ($item) {
                        return [
                            'id'   => $item->id,
                            'name' => $item->name,
                        ];
                    }),
                ];
            })
            ->values()
            ->toArray();

        return [
            'equipment' => $equipments,
            'maintain'  => self::transformCategories(),
        ];
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function create()
    {
        $id = $this->repairRecordRepository->create(self::fetchColumnData())->id;
        $this->repairRecordFileRepository->insert(self::fetchFileData($id));
        self::reportRepair($id);
    }
}