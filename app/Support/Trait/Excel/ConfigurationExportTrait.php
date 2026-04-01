<?php

namespace App\Support\Trait\Excel;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use App\Support\Enum\CrmHouseType;
use App\Support\Enum\LandUseZoningType;

trait ConfigurationExportTrait
{
    /**
     * @param $type
     *
     * @return array
     */
    public function fetchOptionData($type): array
    {
        $condition = [
            'comid'      => crm('community_id'),
            'company_id' => crm('company_id'),
        ];

        $languageSystem = $this->crmLanguageSystemRepository
            ->findAll($condition)
            ->pluck('language_id', 'space_type');

        return $this->crmLanguageConfigurationRepository->findAll($condition + ['configuration_type' => $type])
            ->filter(function ($item) use ($languageSystem, $type) {
                $languageSystemId = Arr::get($languageSystem, $item->configuration_type);

                return $item->language_id === $languageSystemId && $item->configuration_type === $type;
            })
            ->pluck('configuration_name')
            ->toArray();
    }

    /**
     * @return Collection
     */
    public function fetchOptionGroupBy(): Collection
    {
        $condition = [
            'comid'      => crm('community_id'),
            'company_id' => crm('company_id'),
        ];

        $languageSystem = $this->crmLanguageSystemRepository
            ->findAll($condition)
            ->pluck('language_id', 'space_type');

        return $this->crmLanguageConfigurationRepository->findAll($condition)
            ->filter(function ($item) use ($languageSystem) {
                $languageSystemId = Arr::get($languageSystem, $item->configuration_type);

                return $item->language_id === $languageSystemId;
            })
            ->groupBy('configuration_type');
    }

    /**
     * @param  string  $type
     *
     * @return array[]
     */
    private function fetchOption(string $type): array
    {
     return  [
            [
                'col' => 'A',
                'selects' => self::fetchOptionData('district') ?? [],
                'count' => 300
            ],
            [
                'col' => 'B',
                'selects' => self::fetchOptionData('building'),
                'count' => 300
            ],
            [
                'col' => 'C',
                'selects' => self::fetchOptionData('staircase'),
                'count' => 300
            ],
            [
                'col' => 'D',
                'selects' => self::fetchOptionData('floor'),
                'count' => 300
            ],
            [
                'col' => 'E',
                'selects' => self::fetchOptionData($type),
                'count' => 300
            ],
            [
                'col' => 'M',
                'selects' => CrmHouseType::array(),
                'count' => 300
            ],
            [
                 'col' => 'N',
                 'selects' => LandUseZoningType::array(),
                 'count' => 300
            ],
        ];
    }
}