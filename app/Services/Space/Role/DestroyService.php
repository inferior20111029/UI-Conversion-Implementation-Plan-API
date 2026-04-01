<?php

declare(strict_types=1);

namespace App\Services\Space\Role;

use App\Support\Abstract\Service;

use App\Repositories\Space\CrmLanguageConfigurationRepository;

final class DestroyService extends Service
{
    public function __construct(
        private readonly CrmLanguageConfigurationRepository $crmLanguageConfigurationRepository,
    ) {
    }

    /**
     * 刪除空間規則
     *
     * @return void
     */
    public function execute($id): void
    {
        $identity           = request()->identity;
        $configurationType  = request()->type;
        $configurationValue = request()->value;

        if ($identity !== 'language') {
            $this->crmLanguageConfigurationRepository->forceDelete(
                crm('company_id'),
                crm('community_id'),
                $configurationValue,
                $configurationType
            );

            $configurations = $this->crmLanguageConfigurationRepository->findByType($configurationType);

            $groupedConfigurations = $this->processGroupedConfigurations($configurations, $configurationType);

            $this->crmLanguageConfigurationRepository->upsert($groupedConfigurations);
            return;
        }

        $destroyData = [
            'configuration_type' => $configurationType,
            'language_id'        => $id,
        ];

        $this->crmLanguageConfigurationRepository->destroyById($destroyData);
    }

    /**
     * @param $configurations
     * @param  string  $configurationType
     *
     * @return mixed
     */
    private function processGroupedConfigurations($configurations, string $configurationType)
    {
        return $configurations
            ->groupBy('language_id')
            ->flatMap(function ($items) use ($configurationType) {
                return $this->mapConfigurationValues($items, $configurationType);
            })->toArray();
    }

    /**
     * @param $items
     * @param  string  $configurationType
     *
     * @return mixed
     */
    private function mapConfigurationValues($items, string $configurationType)
    {
        $sortedItems = $items->sortBy('configuration_value');

        return $sortedItems->values()->map(function ($item, $key) use ($configurationType) {
            return [
                ...$item->toArray(),
                'configuration_value' => $configurationType . '.' . ($key + 1),
            ];
        });
    }
}
