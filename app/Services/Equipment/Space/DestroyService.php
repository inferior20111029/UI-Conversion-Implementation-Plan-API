<?php

declare(strict_types=1);

namespace App\Services\Equipment\Space;

use App\Support\Abstract\Service;

use App\Repositories\Equipment\CrmEquipmentComponentRepository;
use App\Repositories\Equipment\CrmEquipmentRepository;


final class DestroyService extends Service
{
    public function __construct(
        private readonly CrmEquipmentComponentRepository $crmEquipmentComponentRepository,
        private readonly CrmEquipmentRepository          $crmEquipmentRepository,
    ) {
    }

    /**
     * 刪除戶別下構件
     *
     * @return void
     */
    public function deleteComponent($id): void
    {
        $this->crmEquipmentComponentRepository->forceDeleteById([$id]);
    }

    /**
     * 刪除戶別下元件資料
     *
     * @return void
     */
    public function deleteEquipment($id): void
    {
        $this->crmEquipmentRepository->upsert([
            'id'         => $id ,
            'company_id' => crm('company_id'),
            'comid'      => crm('community_id'),
            'space_id'   => null,
            'status'     => 0
        ]);
    }
}
