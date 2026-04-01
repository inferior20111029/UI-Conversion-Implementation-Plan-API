<?php

declare(strict_types=1);

namespace App\Services\Equipment\Group;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;
use App\Support\Enum\FetchMessage;

use App\Repositories\Equipment\CrmEquipmentRepository;
use App\Repositories\Equipment\CrmEquipmentGroupRepository;
use App\Repositories\Equipment\CrmEquipmentGroupMapRepository;

final class StoreService extends Service
{
    use \App\Support\Trait\EquipmentGroup\ColumnTrait;

    public function __construct(
        private readonly CrmEquipmentRepository         $crmEquipmentRepository,
        private readonly CrmEquipmentGroupMapRepository $crmEquipmentGroupMapRepository,
        private readonly CrmEquipmentGroupRepository    $crmEquipmentGroupRepository,
    ) {
    }

    public function execute(): array
    {
        $filterKey  = request()->get('filter_key');

        $filteredData = array_filter($filterKey, function ($value) {
            return !is_null($value);
        });

        $crmEquipment = $this->crmEquipmentRepository
            ->findByGroupPaginate($filteredData)
            ->transform([$this, 'fetchShowColumnData'])
            ->toArray();

        return $crmEquipment ?? $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
    }

    public function create()
    {
        $equipmentList = request()->post('equipment_list');

        $companyId   = crm('company_id');
        $communityId = crm('community_id');

        $equipmentGroup = [
            'company_id' => $companyId,
            'comid'      => $communityId,
            'name'       => request()->post('name')
        ];

        $id = $this->crmEquipmentGroupRepository->create($equipmentGroup)->id;

        $equipmentData = array_map(function ($item) use ($id, $companyId, $communityId) {
            return [
                'company_id'         => $companyId,
                'comid'              => $communityId,
                'equipment_group_id' => $id,
                'equipment_id'       => $item['id'],
                'count'              => $item['count'],
                'created_at'         => now(),
                'updated_at'         => now(),
            ];
        }, $equipmentList);

        $this->crmEquipmentGroupMapRepository->insert($equipmentData);
    }
}
