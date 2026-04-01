<?php

declare(strict_types=1);

namespace App\Services\Equipment\RepairRequest;

use App\Support\Abstract\Service;
use App\Repositories\Equipment\RepairRecordRepository;

final class ShowService extends Service
{
    use \App\Support\Trait\RepairRequest\ColumnTrait;

    public function __construct(
        private readonly RepairRecordRepository $repairRecordRepository,
    ) {
    }

    /**
     * 回傳修繕(戶別)資料
     *
     * @return array
     */
    public function execute(): array
    {
        $spaceId   = (string) request()->get('space_id', '');
        $filterKey = request()->get('filter_key', []);

        $filteredData = array_filter($filterKey, fn ($value) => !is_null($value) && $value !== '');

        $status = $filteredData['status'] ?? null;
        $filteredData['status'] = $this->findRepairType($status);

        $repairRecord = $this->repairRecordRepository->page($spaceId, $filteredData);

        return $this->paginateResponseFormat(
            $repairRecord,
            $repairRecord->getCollection()->transform([$this, 'fetchSpaceColumnData'])
        );
    }

    public function findRepairType($status)
    {
        if (empty($status)) {
            return [];
        }

        $repairTypes = $this->repairType();

        $result = array_filter($repairTypes, function($value) use ($status) {
            return strpos($value, (string)$status) !== false;
        });

        return !empty($result) ? array_keys($result) : [];
    }
}
