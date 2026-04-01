<?php

declare(strict_types=1);

namespace App\Services\Equipment\RepairRequest;

use App\Support\Abstract\Service;
use App\Repositories\Equipment\RepairRecordRepository;

final class DestroyService extends Service
{
    public function __construct(
        private readonly RepairRecordRepository $repairRecordRepository,
    ) {
    }

    /**
     * 刪除修繕提報
     *
     *
     * @return void
     */
    public function execute(int $id): void
    {
        $this->repairRecordRepository->destroy([$id]);
    }
}
