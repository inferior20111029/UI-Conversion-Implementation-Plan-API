<?php

declare(strict_types=1);

namespace App\Services\Space\Energy;

use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;

use App\Support\Abstract\Service;

use App\Repositories\Energy\SpaceStatisticsRepository;
use App\Repositories\Space\CrmHouseFeeNumberRepository;

final class ShowService extends Service
{
    public function __construct(
        private readonly CrmHouseFeeNumberRepository $crmHouseFeeNumberRepositoryRepository,
        private readonly SpaceStatisticsRepository $spaceStatisticsRepository,
    ) {
    }

    /**
     * @param $request
     * @param $type
     *
     * @return array
     */
    public function execute($request, $type): array
    {
        $spaceId = $request->space_id;

        $crmHouseFeeNumber = $this->crmHouseFeeNumberRepositoryRepository
            ->findByEnergy($spaceId, $type);

        return self::transform($crmHouseFeeNumber);
    }

    /**
     * @param Collection $data
     *
     * @return array
     */
    private function transform(Collection $data): array
    {
        $treeData = [];
        $items    = [];

        foreach ($data as $feeNumber) {
            $id = $feeNumber['id'];
            $parentId = $feeNumber['parent_id'];

            $items[$id] = [
                'id'       => $id,
                'label'    => $feeNumber['value'],
                'children' => $items[$id]['children'] ?? []
            ];

            if ($parentId !== null) {
                $items[$parentId]['children'][] = &$items[$id];
            } else {
                $treeData[] = &$items[$id];
            }
        }

        return $treeData;
    }

    /**
     * 取得能校資料
     *
     * @param $request
     * @param $id
     *
     * @return Collection
     */
    public function fetchEnergyData($request, $id): Collection
    {
        return $this->spaceStatisticsRepository
            ->findByNumberId($request->space_id, $id)
            ->map(fn ($item) => [
                'id'            => $item['id'],
                'space_id'      => $item['space_id'] ?? null,
                'fee_number_id' => $item['fee_number_id'] ?? null,
                'start_at'      => Carbon::parse($item['start_at'])->format('Y-m-d'),
                'end_at'        => Carbon::parse($item['end_at'])->format('Y-m-d'),
                'consumption'   => $item['consumption'] ?? null,
                'cost'          => $item['cost'] ?? null,
                'created_at'    => Carbon::parse($item['created_at'])->format('Y-m-d'),
                'updated_at'    => Carbon::parse($item['updated_at'])->format('Y-m-d'),
            ]);
    }
}