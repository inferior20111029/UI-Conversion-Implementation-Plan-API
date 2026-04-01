<?php

declare(strict_types=1);

namespace App\Services\RentalSaleAmounts\AveragePriceVariance;

use App\Support\Abstract\Service;
use Illuminate\Support\Collection;

use App\Repositories\RentalSaleAmounts\UnitPriceRepository;
use App\Repositories\Space\CrmLanguageConfigurationRepository;
use App\Repositories\Space\CrmLanguageSystemRepository;

final class CalculateService extends Service
{
    use \App\Support\Trait\RentalSaleAmounts\CalculateTrait;

    public function __construct(
        private readonly UnitPriceRepository                $unitPriceRepository,
        private readonly CrmLanguageSystemRepository        $crmLanguageSystemRepository,
        private readonly CrmLanguageConfigurationRepository $crmLanguageConfigurationRepository,
    ) {
    }

    /**
     * 回傳計算結果
     *
     * @return array
     */
    public function execute(): array
    {
        $condition = [
            'comid'      => crm('community_id'),
            'company_id' => crm('company_id'),
        ];

        $floorAmounts = $this->calculateFloorAmounts(
            $this->getRequestInt('total_floor'),
            $this->getRequestInt('middle_floor'),
            $this->getRequestInt('median_amount'),
            $this->getRequestInt('downward_mean_deviation'),
            $this->getRequestInt('upward_mean_deviation')
        );

        $unitPrices = $this->getUnitPrices(request()->get('equipment_group') ?? []);

        $calculatedUnitPricesPerFloor = $this->calculateUnitPricesPerFloor($floorAmounts, $unitPrices);

        $crmLanguageConfigurations = $this->getCrmLanguageConfigurations($condition);

        return $this->buildBuildingConfigurations($crmLanguageConfigurations, $calculatedUnitPricesPerFloor);
    }
}