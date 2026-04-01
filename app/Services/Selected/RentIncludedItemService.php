<?php

declare(strict_types=1);

namespace App\Services\Selected;

use Illuminate\Support\Collection;

use App\Models\RentItemsOptions;

use App\Support\Abstract\Service;

final class RentIncludedItemService extends Service
{
    /**
     * 取得租金包含項目資料
     *
     * @return \Illuminate\Support\Collection
     */
    public function execute(): Collection
    {
        return RentItemsOptions::all()->select('id', 'name');
    }
}
