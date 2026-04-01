<?php

declare(strict_types=1);

namespace App\Services\Space\Role;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmLanguageConfigurationRepository;
use App\Repositories\Space\CrmLanguageSystemRepository;

final class ShowService extends Service
{
    public function __construct(
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
        $configurationType = request()->type;

        $configuration     = $this->crmLanguageConfigurationRepository->findByType($configurationType);
        $configurationData = $configuration->toArray();
        $languageSystem    = $this->crmLanguageSystemRepository->findByType($configurationType)->first();

        $result = [
            'list' => [],
        ];

        if (empty($configurationData)) {
            return $result;
        }

        foreach ($configurationData as $data) {
            if (!isset($data['language_id'], $data['language'])) {
                continue;
            }

            if (!isset($result['list'][$data['language_id']])) {
                $result['list'][$data['language_id']] = [
                    'language'    => $data['language'],
                    'language_id' => $data['language_id'],
                    'rule_data'   => [],
                    'default'     => $data['language_id'] == $languageSystem?->language_id ?? false,
                ];
            }

            $result['list'][$data['language_id']]['rule_data'][] = [
                'configuration_id'    => $data['configuration_id'],
                'configuration_name'  => $data['configuration_name'],
                'configuration_type'  => $data['configuration_type'],
                'configuration_value' => $data['configuration_value'],
                'floor_type'          => $data['floor_type'],
            ];
        }

        array_walk($result['list'], fn (&$languageData) => usort(
            $languageData['rule_data'],
            fn ($a, $b) => strnatcmp($a['configuration_value'], $b['configuration_value'])
        ));

        uasort($result['list'], fn ($a, $b) => $a['language_id'] <=> $b['language_id']);

        $result['list'] = array_values($result['list']);

        return $result;
    }
}
