<?php

declare(strict_types=1);

namespace App\Services\RentalSaleAmounts\AveragePriceVariance;

use App\Support\Abstract\Service;

use App\Models\SaleAveragePrice;
use App\Support\Enum\FetchMessage;
use App\Models\SaleAveragePriceCalculate;

use Illuminate\Database\Eloquent\Collection;

use Symfony\Component\HttpFoundation\Response;
use App\Repositories\RentalSaleAmounts\UnitPriceRepository;
use App\Repositories\RentalSaleAmounts\SaleAveragePriceRepository;
use App\Repositories\Space\CrmLanguageConfigurationRepository;
use App\Repositories\Space\CrmLanguageSystemRepository;

final class ShowService extends Service
{
    public function __construct(
        private readonly UnitPriceRepository                 $unitPriceRepository,
        private readonly SaleAveragePriceRepository          $saleAveragePriceRepository,
        private readonly CrmLanguageConfigurationRepository  $crmLanguageConfigurationRepository,
        private readonly CrmLanguageSystemRepository         $crmLanguageSystemRepository,
    ) {
    }

    /**
     * 回傳售價平均差資料
     *
     * @return array
     */
    public function execute(): array
    {
        $saleAveragePrice = $this->saleAveragePriceRepository->find();

        $languageId = $this->crmLanguageSystemRepository->findByType('floor')
            ->first()?->language_id;

        $totalFloor = $this->crmLanguageConfigurationRepository->count([
            'language_id' => $languageId,
            'configuration_type' => 'floor',
        ]);

        if($totalFloor === 0) {
            $this->fails('尚未設定樓層', Response::HTTP_NOT_FOUND);
        }

        return [
            'id'            => $saleAveragePrice->id ?? null,
            'total_floor'   => $saleAveragePrice->total_floor ?? $totalFloor,
            'middle_floor'  => $saleAveragePrice->middle_floor ?? null,
            'median_amount' => $saleAveragePrice->median_amount ?? null,
            'downward_mean_deviation' => $saleAveragePrice->downward_mean_deviation ?? null,
            'upward_mean_deviation'   => $saleAveragePrice->upward_mean_deviation ?? null,
            'equipment_group'         => json_decode($saleAveragePrice->equipment_group ?? '[]', true),
            'equipment_group_option'  => $this->unitPriceRepository
                ->findByUnitPrice()
                ->filter(fn ($item) => $item->crmEquipmentGroup !== null)
                ->map(fn ($item) => [
                    'equipment_group_id'   => $item->crmEquipmentGroup->id,
                    'equipment_group_name' => $item->crmEquipmentGroup->name,
                ])
                ->groupBy('equipment_group_name')
                ->map(fn ($items) => $items->first())
                ->values()
                ->toArray(),
            'floor_amount'           => self::getFormattedData($saleAveragePrice) ?? null,
        ];
    }

    /**
     * @param  SaleAveragePrice|null  $saleAveragePrice
     *
     * @return array|null
     */
    public function getFormattedData(?SaleAveragePrice $saleAveragePrice): ?array
    {
        return $saleAveragePrice?->calculate
            ->groupBy('building_name')
            ->map(fn ($buildingGroup) => $this->formatBuildingGroup($buildingGroup))
            ->values()
            ->toArray();
    }

    /**
     * @param  Collection  $buildingGroup
     *
     * @return array
     */
    private function formatBuildingGroup(Collection $buildingGroup): array
    {
        return [
            'name'           => $buildingGroup->first()['building_name'],
            'building_value' => $buildingGroup->first()['building'],
            'floor' => $buildingGroup->groupBy('floor_name')
                ->map(fn ($floorGroup) => $this->formatFloorGroup($floorGroup))
                ->values()
                ->toArray(),
        ];
    }

    /**
     * @param  Collection  $floorGroup
     *
     * @return array
     */
    private function formatFloorGroup(Collection $floorGroup): array
    {
        return [
            'name'        => $floorGroup->first()['floor_name'],
            'floor_value' => $floorGroup->first()['floor'],
            'node' => $floorGroup->map(fn($item) => $this->formatNode($item))
                ->values()
                ->toArray(),
        ];
    }

    /**
     * @param  SaleAveragePriceCalculate  $item
     *
     * @return array
     */
    private function formatNode(SaleAveragePriceCalculate $item): array
    {
        return [
            'name'                => $item['unit_price_name'],
            'suggest'             => $item['suggest'],
            'default'             => $item['default'],
            'equipment_group_id'  => $item['equipment_group_id'],
            'equipment_group_name'=> $item['equipment_group_name'],
            'layout_setting_id'   => $item['layout_setting_id'],
            'layout_setting_name' => $item['layout_setting_name'],
        ];
    }
}