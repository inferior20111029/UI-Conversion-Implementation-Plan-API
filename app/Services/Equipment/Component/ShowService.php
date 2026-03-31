<?php

declare(strict_types=1);

namespace App\Services\Equipment\Component;

use Illuminate\Support\Carbon;
use App\Support\Abstract\Service;

use App\Repositories\Equipment\CrmEquipmentComponentRepository;

final class ShowService extends Service
{
    public function __construct(
        private readonly CrmEquipmentComponentRepository $crmEquipmentComponentRepository,
    ) {
    }

    /**
     * 回傳構件資料
     *
     * @return array
     */
    public function execute(): array
    {
        return $this->crmEquipmentComponentRepository
            ->fetchEquipmentById((int) request()->input('equipment_id'))
            ->map(fn ($item) => [
                'id'                => $item->id,
                'crm_equipment_id'  => $item->crm_equipment_id,
                'name'              => $item->name,
                'type'              => $item->type,
                'manufacturer'      => $item->manufacturer,
                'model'             => $item->model,
                'serial_number'     => $item->serial_number,
                'installation_date' => carbon::parse($item->installation_date)->format('Y-m-d'),
                'created_at'        => optional($item->created_at)->format('Y-m-d'),
                'updated_at'        => optional($item->updated_at)->format('Y-m-d'),
            ])->toArray();
    }
}