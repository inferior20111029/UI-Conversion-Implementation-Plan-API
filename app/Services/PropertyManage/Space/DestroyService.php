<?php

declare(strict_types=1);

namespace App\Services\PropertyManage\Space;

use App\Support\Abstract\Service;

use App\Models\RenterContract;

use App\Services\RenterContract\Component\DeleteData;

final class DestroyService extends Service
{
    /**
     * 刪除合約
     *
     * @param \App\Models\RenterContract $contract 合約資料
     *
     * @return void
     */
    public function execute(RenterContract $contract): void
    {
        (new DeleteData($contract))->execute();
    }
}
