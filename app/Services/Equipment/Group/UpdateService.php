<?php

declare(strict_types=1);

namespace App\Services\Equipment\Group;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;
use App\Support\Enum\FetchMessage;

use App\Repositories\Equipment\CrmEquipmentRepository;
use App\Repositories\Equipment\CrmEquipmentGroupRepository;
use App\Repositories\Equipment\CrmEquipmentGroupMapRepository;

final class UpdateService extends Service
{
    use \App\Support\Trait\EquipmentGroup\ColumnTrait;

    public function __construct(
        private readonly CrmEquipmentRepository         $crmEquipmentRepository,
        private readonly CrmEquipmentGroupMapRepository $crmEquipmentGroupMapRepository,
        private readonly CrmEquipmentGroupRepository    $crmEquipmentGroupRepository,
    ) {
    }

    public function execute($id): array
    {
        $crmEquipmentGroup  = $this->crmEquipmentGroupRepository->findById($id);

        if (empty($crmEquipmentGroup)) {
            $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
        }

        $crmEquipmentGroupMap = $crmEquipmentGroup->crmEquipmentGroupMap->map(
            function ($item) {
                return [
                    'equipment_id'=> $item?->equipment_id,
                    'name'        => $item?->crmEquipment->name ?? '',
                    'type_name'   => $item?->crmEquipment?->crmTypeName->name ?? '', // 類別名稱
                    'system_name' => $item?->crmEquipment?->crmSystemName->name ?? '', // 系統名稱
                    'location'    => $item?->crmEquipment?->location ?? '',
                    'area'        => $item?->crmEquipment?->area,
                    'space'       => $item?->crmEquipment?->space,
                    'brand'       => $item?->crmEquipment?->brand,
                    'model'       => $item?->crmEquipment?->model,
                    'public_type' => $item?->crmEquipment?->public_type,
                    'count'       => $item?->count,
                ];
            }
        );

        return [
            'id'   => $crmEquipmentGroup->id,
            'name' => $crmEquipmentGroup->name,
            'list' => $crmEquipmentGroupMap
        ];
    }

    /**
     * 更新元件群組
     *
     * @param $id
     * @return void
     */
    public function update($id): void
    {
        $equipmentList = request()->post('equipment_list');
        $equipmentDel  = request()->post('equipment_del');

        if (request()->post('name')) {
            $equipmentGroup = [
                'name' => request()->post('name')
            ];

            $this->crmEquipmentGroupRepository->update($id, $equipmentGroup);
        }

        if ($equipmentList) {
            collect($equipmentList)->map(function ($item) use ($id) {
                return [
                    'company_id'         => crm('company_id'),
                    'comid'              => crm('community_id'),
                    'equipment_group_id' => $id,
                    'equipment_id'       => $item['id'],
                    'count'              => $item['count'] ?? 0,
                ];
            })->map(function ($item) {
                $this->crmEquipmentGroupMapRepository->updateOrCreate($item);
            });
        }

        if (!is_null($equipmentDel)) {
            $this->crmEquipmentGroupMapRepository->delGroupByEquipment($equipmentDel, $id);
        }
    }
}
