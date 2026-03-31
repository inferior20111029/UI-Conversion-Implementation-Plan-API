<?php

declare(strict_types=1);

namespace App\Services\Equipment\Space;

use App\Support\Abstract\Service;
use App\Support\Enum\FetchMessage;

use App\Repositories\Equipment\CrmEquipmentRepository;

use Illuminate\Support\Collection;

use Symfony\Component\HttpFoundation\Response;

final class ShowService extends Service
{
    use \App\Support\Trait\Equipment\EquipmentTrait;
    public function __construct(
        private readonly CrmEquipmentRepository $crmEquipmentRepository,
    ) {
    }

    /**
     * 取得戶別列表下的元件資訊
     *
     * @param  string  $spaceId
     *
     * @return Collection
     */
    public function execute(string $spaceId): Collection
    {
       $equipments = $this->crmEquipmentRepository
            ->fetchEquipmentBySpace($spaceId);

        if ($equipments->isNotEmpty()) {
            return $this->transformEquipments($equipments);
        }

        $this->fails(FetchMessage::NOT_FOUND->value, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param  Collection  $equipments
     *
     * @return Collection
     */
    public function transformEquipments(Collection $equipments): Collection
    {
        return $equipments->map(fn ($item) =>  [
                'id'          => $item?->id,
                'name'        => $item?->name,
                'type_name'   => $item->crmTypeName?->name,
                'is_scrap'    => !empty($item->crmEquipmentScrap),
                'system_name' => $item->crmSystemName?->name,
                'component'   => $item?->crmEquipmentComponent->map(fn( $component) => [
                    'id'   => $component['id'],
                    'name' => $component['name'],
                    'type' => $component['type'],
                ])
            ]);
    }
}