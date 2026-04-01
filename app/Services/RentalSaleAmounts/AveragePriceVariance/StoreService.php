<?php

declare(strict_types=1);

namespace App\Services\RentalSaleAmounts\AveragePriceVariance;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmLanguageSystemRepository;
use App\Repositories\RentalSaleAmounts\UnitPriceRepository;
use App\Repositories\Space\CrmLanguageConfigurationRepository;
use App\Repositories\RentalSaleAmounts\SaleAveragePriceRepository;
use App\Repositories\RentalSaleAmounts\SaleAveragePriceCalculateRepository;

final class StoreService extends Service
{
    use \App\Support\Trait\RentalSaleAmounts\CalculateTrait;

    public function __construct(
        private readonly SaleAveragePriceRepository          $saleAveragePriceRepository,
        private readonly SaleAveragePriceCalculateRepository $saleAveragePriceCalculateRepository,
        private readonly UnitPriceRepository                 $unitPriceRepository,
        private readonly CrmLanguageSystemRepository         $crmLanguageSystemRepository,
        private readonly CrmLanguageConfigurationRepository  $crmLanguageConfigurationRepository,
    ) {
    }

    public function create()
    {
        $insertData = [
            'company_id'              => crm('company_id'),
            'comid'                   => crm('community_id'),
            'total_floor'             => request()->post('total_floor'),
            'middle_floor'            => request()->post('middle_floor'),
            'median_amount'           => request()->post('median_amount'),
            'downward_mean_deviation' => request()->post('downward_mean_deviation'),
            'upward_mean_deviation'   => request()->post('upward_mean_deviation'),
            'equipment_group'         => json_encode(request()->post('equipment_group') ?? []),
            'floor_amount'            => json_encode(request()->post('floor_amount') ?? []),
        ];

        $id = $this->saleAveragePriceRepository->createOrUpdate($insertData)->id;

        if(is_null($id)) {
            $id = $this->saleAveragePriceRepository->find()->id;
        } else {
            $this->saleAveragePriceCalculateRepository->forceDelete($id);
        }

        $this->saleAveragePriceCalculateRepository->insert(self::execute($id));
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function execute($id): array
    {
       return collect(request()->post('floor_amount'))
            ->flatMap(fn ($building) => collect($building['floor'])
                ->flatMap(fn ($floor) => collect($floor['node'])
                    ->map(fn ($node) => [
                        'sale_average_price_id' => $id,
                        'building'              => $building['building_value'],
                        'building_name'         => $building['name'],
                        'floor'                 => $floor['floor_value'],
                        'floor_name'            => $floor['name'],
                        'unit_price_name'       => $node['name'],
                        'suggest'               => (int) $node['suggest'],
                        'default'               => (int) $node['default'],
                        'equipment_group_name'  => $node['equipment_group_name'],
                        'layout_setting_name'   => $node['layout_setting_name'],
                        'equipment_group_id'    => $node['equipment_group_id'],
                        'layout_setting_id'     => $node['layout_setting_id'],
                    ])
                )
            )
            ->toArray();
    }
}