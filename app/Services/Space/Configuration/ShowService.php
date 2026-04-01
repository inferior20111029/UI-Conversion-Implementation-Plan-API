<?php

declare(strict_types=1);

namespace App\Services\Space\Configuration;

use App\Support\Abstract\Service;
use App\Support\Enum\CrmHouseType;

use App\Repositories\Space\CrmBuildingSpaceRepository;
use App\Repositories\Space\CrmLanguageSystemRepository;
use App\Repositories\Space\CrmLanguageConfigurationRepository;

final class ShowService extends Service
{
    public function __construct(
        private readonly CrmBuildingSpaceRepository         $crmBuildingSpaceRepository,
        private readonly CrmLanguageConfigurationRepository $crmLanguageConfigurationRepository,
        private readonly CrmLanguageSystemRepository        $crmLanguageSystemRepository
    ) {
    }

    /**
     * 回傳空間組態
     *
     * @return array
     */
    public function execute(): array
    {
        $filterKey = request()->get('filter_key', []);
        $type      = (int)request()->get('type', 1);
        $companyId = crm('company_id');
        $comid     = crm('community_id');

        $filteredData = array_filter($filterKey, fn ($value) => !is_null($value));

        $crmBuildingSpace = $this->crmBuildingSpaceRepository
            ->spaceConfigurationPage($companyId, $comid, $filteredData, $type);

        return $this->paginateResponseFormat(
            $crmBuildingSpace,
            $crmBuildingSpace->getCollection()->map(fn ($item) => [
                ...$item->toArray(),
                ...['main_application_value' => CrmHouseType::array()[$item['main_application']] ?? null]
            ])
        );
    }

    public function create(): array
    {
        return self::option();
    }

    public function option(): array
    {
        $type = request()->get('type') === '0' ? 'public' : 'privacy';

        $data = [
            'company_id' => crm('company_id'),
            'comid'      => crm('community_id'),
        ];

        $crmLanguageSystems = $this->crmLanguageSystemRepository
            ->findAll($data)
            ->groupBy('space_type');

        $crmLanguageConfigurations = $this->crmLanguageConfigurationRepository->findAll($data);

        if (empty($crmLanguageConfigurations)) {
            return [];
        }

        $options = [];

        foreach ($crmLanguageConfigurations as $item) {
            $configurationType = $item['configuration_type'];
            $defaultLanguageId = $crmLanguageSystems[$configurationType]->first()->language_id ?? '';

            if ($item['language_id'] === $defaultLanguageId) {
                $options[$configurationType][] = $item;
            }
        }

        unset($options[$type == 'privacy' ? 'public' : 'privacy']);

        $houseTypeKeys = $type === 'privacy'
            ? ['H001', 'H002', 'H003', 'H004', 'H005', 'H006', 'H007']
            : ['H008', 'H009', 'H010', 'H011', 'H012', 'H013', 'H014'];

        $options['house_type'] = array_intersect_key(
            CrmHouseType::array(),
            array_flip($houseTypeKeys)
        );

        return $options;
    }
}