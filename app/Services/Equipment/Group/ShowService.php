<?php

declare(strict_types=1);

namespace App\Services\Equipment\Group;

use App\Support\Abstract\Service;

use App\Support\Enum\FetchMessage;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\Equipment\CrmEquipmentGroupRepository;

final class ShowService extends Service
{
    public function __construct(
        private readonly CrmEquipmentGroupRepository $crmEquipmentGroupRepository,
    ) {
    }

    /**
     * 回傳元件類別資料
     *
     * @return array
     */
    public function execute(): array
    {
        $crmEquipmentGroup = $this->crmEquipmentGroupRepository->findAll();

        if ($crmEquipmentGroup->isEmpty()) {
            $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
        }

        return $crmEquipmentGroup->map(
            function ($CrmEquipmentGroup) {
                $count = 0;
                return [
                    'id'   => $CrmEquipmentGroup->id,
                    'name' => $CrmEquipmentGroup->name,
                    'list' =>  $CrmEquipmentGroup->crmEquipmentGroupMap->map(function ($groupMap) use (&$count) {
                        $crmEquipment = $groupMap->crmEquipment;
                        $count += $groupMap->count;
                        return [
                            'equipment_name' => $crmEquipment->name,
                            'equipment_id'   => $crmEquipment->id,
                            'type_name'      => $crmEquipment->crmTypeName->name,
                            'system_name'    => $crmEquipment->crmSystemName->name ?? '',
                            'count'          => $groupMap->count,
                        ];
                    }),
                    'count' => $count,
                ];
            }
        )->toArray();
    }
}