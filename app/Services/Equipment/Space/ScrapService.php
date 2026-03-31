<?php

declare(strict_types=1);

namespace App\Services\Equipment\Space;

use App\Support\Abstract\Service;

use App\Repositories\Equipment\CrmEquipmentScrapRepository;

final class ScrapService extends Service
{
    public function __construct(
        private readonly CrmEquipmentScrapRepository $crmEquipmentScrapRepository,
    ) {
    }

    /**
     * 報廢設備設備
     *
     * @return array
     */
    public function execute(): array
    {
        $equipmentIds = request()->post('id');

        if (empty($equipmentIds)) {
            return [];
        }

        $userId = crm('user_id');

        $data = array_map(function ($id) use ($userId) {
            return [
                'crm_equipment_id' => $id,
                'taggable_type'    => \App\Models\Login\LoginUser::class,
                'taggable_id'      => $userId,
                'created_at'       => now(),
                'updated_at'       => now(),
            ];
        }, $equipmentIds);

        $this->crmEquipmentScrapRepository->insert($data);

        return $data;
    }
}