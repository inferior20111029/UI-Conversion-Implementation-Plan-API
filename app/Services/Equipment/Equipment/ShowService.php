<?php

declare(strict_types=1);

namespace App\Services\Equipment\Equipment;

use App\Support\Abstract\Service;

use App\Repositories\Equipment\CrmEquipmentRepository;

final class ShowService extends Service
{
    use \App\Support\Trait\Equipment\EquipmentTrait;

    public function __construct(
        private readonly CrmEquipmentRepository $crmEquipmentRepository,
    ) {
    }

    /**
     * 回傳元件資料
     *
     * @return array
     */
    public function execute(): array
    {
        $filterKey  = request()->get('filter_key');

        $filteredData = array_filter($filterKey, fn ($value): bool => !is_null($value));

        $crmEquipment = $this->crmEquipmentRepository
            ->crmEquipmentPage($filteredData);

        $transformedList = $crmEquipment
            ->getCollection()
            ->transform([$this, 'fetchColumnData']);

        return $this->paginateResponseFormat($crmEquipment, $transformedList);
    }
}