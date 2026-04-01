<?php

declare(strict_types=1);

namespace App\Services\RenterContract\Component;

use App\Repositories\RenterContract\RenterContractCacheRepository;

final class UpdateCache
{
    use \App\Support\Trait\RenterContract\ColumnTrait;

    /**
     * 更新暫存
     *
     * @param UpdateInstance $updateInstance
     *
     * @return void
     */
    public function execute(UpdateInstance $updateInstance): void
    {
        $hasCache = (int) $updateInstance->request->post('cacheState') ;

        $id = $hasCache == 1 ? (int) $updateInstance->contractData->id : 0;
        $spaceId = $updateInstance->contractData->taggable_id;

        (new RenterContractCacheRepository())->update([
            'renter_contract_id' => $id,
            'space_id' => $spaceId
        ]);
    }
}
