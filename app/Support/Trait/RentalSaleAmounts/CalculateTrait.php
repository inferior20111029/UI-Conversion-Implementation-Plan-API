<?php

declare(strict_types=1);

namespace App\Support\Trait\RentalSaleAmounts;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

use App\Support\Tool\File\FileMagic;
use App\Support\Data\RealEstateAgentData;

trait CalculateTrait
{
    /**
     * 取得欄位資料
     *
     * @param \Illuminate\Http\Request $request Request
     *
     * @return \App\Support\Data\RealEstateAgentData
     */
    public function fetchColumnData(Request $request): RealEstateAgentData
    {
        return new RealEstateAgentData([
            'uuid' => str()->uuid()->toString(),
            'password' => Hash::make((string) $request->post('password')),
            'avatar' => (int) FileMagic::find((string) $request->post('avatar'))->get()?->id,
            'createBy' => crm('user_id') ?? 0,
            'enableState' => $request->integer('enableState')
        ] + $request->all() + crm()->only('company_id', 'community_id'));
    }

    /**
     * @param  string  $key
     * @param  int  $default
     *
     * @return int
     */
    private function getRequestInt(string $key, int $default = 0): int
    {
        $value = request()->get($key, (string) $default);
        return is_numeric($value) ? (int) $value : $default;
    }

    /**
     * @param  int  $totalFloor
     * @param  int  $medianFloor
     * @param  int  $medianAmount
     * @param  int  $downwardMeanDeviation
     * @param  int  $upwardMeanDeviation
     *
     * @return Collection
     */
    private function calculateFloorAmounts(
        int $totalFloor,
        int $medianFloor,
        int $medianAmount,
        int $downwardMeanDeviation,
        int $upwardMeanDeviation
    ): Collection
    {
        return collect(range(1, $totalFloor))->mapWithKeys(function ($i) use ($medianFloor, $medianAmount, $downwardMeanDeviation, $upwardMeanDeviation) {
            $deviation = $i >= $medianFloor
                ? ($i - $medianFloor) * $downwardMeanDeviation
                : ($medianFloor - $i) * $upwardMeanDeviation;

            return [$i => $medianAmount + $deviation];
        });
    }

    /**
     * @param  array  $equipmentGroupId
     *
     * @return Collection
     */
    private function getUnitPrices(array $equipmentGroupId): Collection
    {
        return $this->unitPriceRepository->findCalculate($equipmentGroupId)->map(fn ($item) => [
            'name'                 => $item->name,
            'suggest'              => $item->suggest,
            'default'              => $item->default,
            'equipment_group_id'   => $item->crmEquipmentGroup?->id,
            'equipment_group_name' => $item->crmEquipmentGroup?->name,
            'layout_setting_name'  => $item->crmLayoutSetting?->name,
            'layout_setting_id'    => $item->crmLayoutSetting?->id,
        ]);
    }

    /**
     * @param  Collection  $floorAmounts
     * @param  Collection  $unitPrices
     *
     * @return Collection
     */
    private function calculateUnitPricesPerFloor(Collection $floorAmounts, Collection $unitPrices): Collection
    {
        return $floorAmounts->map(fn ($floorAmount) =>
        $unitPrices->map(fn ($item) => [
            'name'                 => $item['name'],
            'suggest'              => $item['suggest'],
            'default'              => $item['default'] + $floorAmount,
            'equipment_group_id'   => $item['equipment_group_id'],
            'equipment_group_name' => $item['equipment_group_name'],
            'layout_setting_name'  => $item['layout_setting_name'],
            'layout_setting_id'    => $item['layout_setting_id'],
        ])
        )->values();
    }

    /**
     * @param  array  $condition
     *
     * @return Collection
     */
    private function getCrmLanguageConfigurations(array $condition): Collection
    {
        $languageSystem = $this->crmLanguageSystemRepository->findAll($condition)
            ->whereIn('space_type', ['building', 'floor'])
            ->pluck('language_id', 'space_type');

        return $this->crmLanguageConfigurationRepository->findCalculate($condition)
            ->filter(fn ($item) => $item->language_id === ($languageSystem[$item->configuration_type] ?? null));
    }

    /**
     * @param  Collection  $crmLanguageConfigurations
     * @param  Collection  $calculatedUnitPricesPerFloor
     * @param  int|null  $id
     *
     * @return array
     */
    private function buildBuildingConfigurations(Collection $crmLanguageConfigurations, Collection $calculatedUnitPricesPerFloor, ?int $id = 0): array
    {

        $buildings = $crmLanguageConfigurations->where('configuration_type', 'building')->values();
        $floors = $crmLanguageConfigurations->where('configuration_type', 'floor')->values();

        if ($id !== null && $id !== 0) {
            return $buildings->flatMap(fn ($building) =>
            $this->insertBuildFloorConfigurations($floors, $calculatedUnitPricesPerFloor, $building->toArray(), $id)
            )->toArray();
        }

        return $buildings->map(fn ($building) => [
            'name'     => $building['configuration_name'],
            'building_value' => $building['configuration_value'],
            'floor'    => $this->buildFloorConfigurations($floors, $calculatedUnitPricesPerFloor),
        ])->toArray();
    }

    /**
     * @param  Collection  $floors
     * @param  Collection  $calculatedUnitPricesPerFloor
     *
     * @return array
     */
    private function buildFloorConfigurations(Collection $floors, Collection $calculatedUnitPricesPerFloor): array
    {
        return $floors->map(fn ($floor, $key) => [
            'name'  => $floor['configuration_name'],
            'floor_value' => $floor['configuration_value'],
            'node'  => $calculatedUnitPricesPerFloor->get($key) ?? [],
        ])->toArray();
    }

    /**
     * @param  Collection  $floors
     * @param  Collection  $calculatedUnitPricesPerFloor
     * @param  array  $building
     *
     * @return array
     */
    private function insertBuildFloorConfigurations(Collection $floors, Collection $calculatedUnitPricesPerFloor, array $building, $id): array
    {
        return $floors->flatMap(function ($floor, $key) use ($calculatedUnitPricesPerFloor, $building, $id) {
            $unitPrices = $calculatedUnitPricesPerFloor->get($key);

            if (is_null($unitPrices)) {
                return [];
            }

            return $unitPrices->map(function ($item) use ($floor, $building, $id) {
                return [
                    'sale_average_price_id'=> $id,
                    'building'             => $building['configuration_value'],
                    'building_name'        => $building['configuration_name'],
                    'floor'                => $floor['configuration_value'],
                    'floor_name'           => $floor['configuration_name'],
                    'unit_price_name'      => $item['name'],
                    'suggest'              => $item['suggest'],
                    'default'              => $item['default'],
                    'equipment_group_name' => $item['equipment_group_name'],
                    'layout_setting_name'  => $item['layout_setting_name'],
                    'equipment_group_id'   => $item['equipment_group_id'],
                    'layout_setting_id'    => $item['layout_setting_id'],
                ];
            });
        })->toArray();
    }
}
